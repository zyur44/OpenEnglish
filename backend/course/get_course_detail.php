<?php
require_once __DIR__ . '/../../connectdb/db.php';

header('Content-Type: application/json; charset=utf-8');

$courseId = 0;

if (isset($_GET['course_id'])) {
    $courseId = (int) $_GET['course_id'];
}

if ($courseId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu mã khóa học'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sqlCourse = "
        SELECT 
            id,
            title,
            description,
            level,
            thumbnail
        FROM courses
        WHERE id = :course_id
        AND is_active = 1
        LIMIT 1
    ";

    $stmtCourse = $pdo->prepare($sqlCourse);
    $stmtCourse->execute([
        ':course_id' => $courseId
    ]);

    $course = $stmtCourse->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy khóa học'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sqlUnits = "
        SELECT 
            id,
            title,
            description,
            sort_order
        FROM units
        WHERE course_id = :course_id
        AND is_active = 1
        ORDER BY sort_order ASC
    ";

    $stmtUnits = $pdo->prepare($sqlUnits);
    $stmtUnits->execute([
        ':course_id' => $courseId
    ]);

    $units = $stmtUnits->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'course' => $course,
            'units' => $units
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Không lấy được chi tiết khóa học',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}