<?php
require_once __DIR__ . '/../../connectdb/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Tính tiến trình khóa học dựa trên:
 * - Tiến trình unit = (video xem + quiz pass) / 2 * 100
 * - Tiến trình khóa học = số unit hoàn thành / tổng unit * 100
 *
 * @param int $userId ID của học sinh
 * @param int $courseId ID của khóa học
 * @return array Kết quả với status, message, và dữ liệu tiến trình
 */
function getCourseProgressDetails($userId, $courseId) {
    global $pdo;

    $userId = (int)$userId;
    $courseId = (int)$courseId;

    try {
        $stmt = $pdo->prepare("
            SELECT id FROM units
            WHERE course_id = :courseId
            ORDER BY order_index ASC
        ");
        $stmt->execute([':courseId' => $courseId]);
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($units)) {
            return [
                'status' => false,
                'message' => 'Khóa học không có unit nào',
                'data' => null
            ];
        }

        $totalUnits = count($units);
        $completedUnits = 0;
        $unitProgressDetails = [];

        foreach ($units as $unit) {
            $unitId = (int)$unit['id'];

            $videoStmt = $pdo->prepare("
                SELECT uvp.is_watched
                FROM user_video_progress uvp
                INNER JOIN videos v ON uvp.video_id = v.id
                WHERE uvp.user_id = :userId AND v.unit_id = :unitId
            ");
            $videoStmt->execute([':userId' => $userId, ':unitId' => $unitId]);
            $videoProgress = $videoStmt->fetch(PDO::FETCH_ASSOC);

            $videoWatched = ($videoProgress && (int)$videoProgress['is_watched'] === 1) ? 1 : 0;

            $quizCheckStmt = $pdo->prepare("
                SELECT id
                FROM quizzes
                WHERE unit_id = :unitId
                LIMIT 1
            ");
            $quizCheckStmt->execute([':unitId' => $unitId]);
            $quizExists = $quizCheckStmt->fetch(PDO::FETCH_ASSOC) !== false;

            $quizPassed = 0;
            if ($quizExists) {
                $quizStmt = $pdo->prepare("
                    SELECT uqa.is_passed
                    FROM user_quiz_attempts uqa
                    INNER JOIN quizzes q ON uqa.quiz_id = q.id
                    WHERE uqa.user_id = :userId AND q.unit_id = :unitId
                    ORDER BY uqa.created_at DESC
                    LIMIT 1
                ");
                $quizStmt->execute([':userId' => $userId, ':unitId' => $unitId]);
                $quizProgress = $quizStmt->fetch(PDO::FETCH_ASSOC);

                $quizPassed = ($quizProgress && (int)$quizProgress['is_passed'] === 1) ? 1 : 0;
            }

            $unitProgress = $quizExists
                ? (($videoWatched + $quizPassed) / 2) * 100
                : ($videoWatched * 100);

            $unitProgressDetails[] = [
                'unitId' => $unitId,
                'videoWatched' => $videoWatched,
                'quizPassed' => $quizPassed,
                'progress' => round($unitProgress, 2)
            ];

            if ($unitProgress >= 100) {
                $completedUnits++;
            }
        }

        $courseProgress = $totalUnits > 0 ? ($completedUnits / $totalUnits) * 100 : 0;
        $courseProgress = round($courseProgress, 2);

        return [
            'status' => true,
            'message' => 'Lấy tiến trình thành công',
            'data' => [
                'userId' => $userId,
                'courseId' => $courseId,
                'courseProgress' => $courseProgress,
                'completedUnits' => $completedUnits,
                'totalUnits' => $totalUnits,
                'unitDetails' => $unitProgressDetails
            ]
        ];
    } catch (PDOException $e) {
        return [
            'status' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage(),
            'data' => null
        ];
    }
}

function markVideoAsWatched($userId, $videoId) {
    global $pdo;

    $userId = (int)$userId;
    $videoId = (int)$videoId;

    if ($userId <= 0 || $videoId <= 0) {
        return [
            'status' => false,
            'message' => 'Thiếu userId hoặc videoId',
            'data' => null
        ];
    }

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO user_video_progress (user_id, video_id, is_watched, updated_at)
             VALUES (:userId, :videoId, 1, NOW())
             ON DUPLICATE KEY UPDATE is_watched = 1, updated_at = NOW()"
        );
        $stmt->execute([
            ':userId' => $userId,
            ':videoId' => $videoId
        ]);

        return [
            'status' => true,
            'message' => 'Đã ghi nhận video đã xem xong',
            'data' => [
                'userId' => $userId,
                'videoId' => $videoId
            ]
        ];
    } catch (PDOException $e) {
        return [
            'status' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage(),
            'data' => null
        ];
    }
}

function updateCourseProgress($userId, $courseId) {
    global $pdo;

    $progressResult = getCourseProgressDetails($userId, $courseId);

    if (!$progressResult['status']) {
        return $progressResult;
    }

    $data = $progressResult['data'];

    $updateStmt = $pdo->prepare("
        UPDATE enrollments
        SET course_progress = :courseProgress, updated_at = NOW()
        WHERE user_id = :userId AND course_id = :courseId
    ");

    $updateStmt->execute([
        ':courseProgress' => $data['courseProgress'],
        ':userId' => $userId,
        ':courseId' => $courseId
    ]);

    return [
        'status' => true,
        'message' => 'Cập nhật tiến trình thành công',
        'data' => $data
    ];
}

/**
 * API Endpoint: Cập nhật tiến trình khóa học
 * POST: /backend/progress/update_progress.php
 * Dữ liệu cần gửi: { "userId": 1, "courseId": 2 }
 */
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        $data = [];
    }

    $userId = isset($data['userId']) ? (int)$data['userId'] : (int)($_SESSION['user_id'] ?? 0);

    if (!empty($data['videoId'])) {
        $result = markVideoAsWatched($userId, (int)$data['videoId']);
        echo json_encode($result);
        exit;
    }

    if (!isset($data['courseId'])) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Thiếu courseId'
        ]);
        exit;
    }

    $result = updateCourseProgress($userId, (int)$data['courseId']);
    echo json_encode($result);
}
?>