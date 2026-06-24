<?php
require_once '../../connectdb/db.php';

/**
 * Cập nhật tiến trình khóa học dựa trên:
 * - Tiến trình unit = (video xem + quiz pass) / 2 * 100
 * - Tiến trình khóa học = số unit hoàn thành / tổng unit * 100
 * 
 * @param int $userId ID của học sinh
 * @param int $courseId ID của khóa học
 * @return array Kết quả với status, message, và dữ liệu tiến trình
 */
function updateCourseProgress($userId, $courseId) {
    global $pdo;
    
    try {
        // 1. Lấy tất cả units của khóa học
        $stmt = $pdo->prepare("
            SELECT id FROM units 
            WHERE course_id = :courseId 
            ORDER BY order_index ASC
        ");
        $stmt->execute([':courseId' => $courseId]);
        $units = $stmt->fetchAll();
        
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
        
        // 2. Tính tiến trình của từng unit
        foreach ($units as $unit) {
            $unitId = $unit['id'];
            
            // Lấy thông tin video progress
            $videoStmt = $pdo->prepare("
                SELECT uvp.is_watched
                FROM user_video_progress uvp
                INNER JOIN videos v ON uvp.video_id = v.id
                WHERE uvp.user_id = :userId AND v.unit_id = :unitId
            ");
            $videoStmt->execute([':userId' => $userId, ':unitId' => $unitId]);
            $videoProgress = $videoStmt->fetch();
            
            $videoWatched = ($videoProgress && $videoProgress['is_watched']) ? 1 : 0;
            
            // Lấy thông tin quiz progress (xem quiz đã hoàn thành hay chưa)
            $quizStmt = $pdo->prepare("
                SELECT uqa.is_passed
                FROM user_quiz_attempts uqa
                INNER JOIN quizzes q ON uqa.quiz_id = q.id
                WHERE uqa.user_id = :userId AND q.unit_id = :unitId
                ORDER BY uqa.created_at DESC
                LIMIT 1
            ");
            $quizStmt->execute([':userId' => $userId, ':unitId' => $unitId]);
            $quizProgress = $quizStmt->fetch();
            
            $quizPassed = ($quizProgress && $quizProgress['is_passed']) ? 1 : 0;
            
            // Tính tiến trình unit: (video xem + quiz pass) / 2 * 100
            $unitProgress = (($videoWatched + $quizPassed) / 2) * 100;
            
            $unitProgressDetails[] = [
                'unitId' => $unitId,
                'videoWatched' => $videoWatched,
                'quizPassed' => $quizPassed,
                'progress' => round($unitProgress, 2)
            ];
            
            // Nếu unit hoàn thành (progress >= 100), tính vào số unit đã hoàn thành
            if ($unitProgress >= 100) {
                $completedUnits++;
            }
        }
        
        // 3. Tính tiến trình khóa học: số unit hoàn thành / tổng unit * 100
        $courseProgress = ($completedUnits / $totalUnits) * 100;
        $courseProgress = round($courseProgress, 2);
        
        // 4. Cập nhật vào bảng enrollments
        $updateStmt = $pdo->prepare("
            UPDATE enrollments 
            SET course_progress = :courseProgress, updated_at = NOW()
            WHERE user_id = :userId AND course_id = :courseId
        ");
        
        $updateResult = $updateStmt->execute([
            ':courseProgress' => $courseProgress,
            ':userId' => $userId,
            ':courseId' => $courseId
        ]);
        
        if (!$updateResult) {
            return [
                'status' => false,
                'message' => 'Cập nhật tiến trình thất bại',
                'data' => null
            ];
        }
        
        return [
            'status' => true,
            'message' => 'Cập nhật tiến trình thành công',
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

/**
 * API Endpoint: Cập nhật tiến trình khóa học
 * POST: /backend/progress/update_progress.php
 * Dữ liệu cần gửi: { "userId": 1, "courseId": 2 }
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['userId']) || !isset($data['courseId'])) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Thiếu userId hoặc courseId'
        ]);
        exit;
    }
    
    $result = updateCourseProgress($data['userId'], $data['courseId']);
    echo json_encode($result);
}
?>