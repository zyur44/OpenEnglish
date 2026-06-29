<?php
require_once __DIR__ . '/../../connectdb/db.php';


/**
 * Lấy chi tiết khóa học cùng với danh sách units
 * 
 * @param int $courseId ID của khóa học
 * @return array Kết quả với success, data hoặc message
 */
function getCourseDetail($courseId) {
    global $pdo;
    
    $courseId = (int)$courseId;
    
    if ($courseId <= 0) {
        return [
            'success' => false,
            'message' => 'Thiếu mã khóa học'
        ];
    }
    
    try {
        // Lấy thông tin khóa học
        $sqlCourse = "
            SELECT 
                id,
                title,
                description,
                thumbnail_url
            FROM courses
            WHERE id = :course_id
            LIMIT 1
        ";
        
        $stmtCourse = $pdo->prepare($sqlCourse);
        $stmtCourse->execute([':course_id' => $courseId]);
        
        $course = $stmtCourse->fetch(PDO::FETCH_ASSOC);
        
        if (!$course) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy khóa học'
            ];
        }
        
        // Lấy danh sách units
        $sqlUnits = "
            SELECT 
                id,
                title,
                order_index
            FROM units
            WHERE course_id = :course_id
            ORDER BY order_index ASC
        ";
        
        $stmtUnits = $pdo->prepare($sqlUnits);
        $stmtUnits->execute([':course_id' => $courseId]);
        
        $units = $stmtUnits->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'data' => [
                'course' => $course,
                'units' => $units
            ]
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Không lấy được chi tiết khóa học',
            'error' => $e->getMessage()
        ];
    }
}

/**
 * API Endpoint: Lấy chi tiết khóa học
 * GET: /backend/course/get_course_detail.php?course_id=1
 */
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $courseId = $_GET['course_id'] ?? 0;
        $result = getCourseDetail($courseId);
        echo json_encode($result);
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method không hợp lệ']);
}
?>