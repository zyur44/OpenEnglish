<?php
require_once __DIR__ . '/../../connectdb/db.php';

header('Content-Type: application/json; charset=utf-8');

/**
 * Lấy danh sách tất cả khóa học
 * 
 * @return array Kết quả với success, data hoặc message
 */
function getCourses() {
    global $pdo;
    
    try {
        $sql = "
            SELECT 
                c.id,
                c.title,
                c.description,
                c.thumbnail_url,
                COUNT(u.id) AS total_units
            FROM courses c
            LEFT JOIN units u ON c.id = u.course_id
            GROUP BY c.id, c.title, c.description, c.thumbnail_url
            ORDER BY c.id ASC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'data' => $courses
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Lỗi lấy danh sách khóa học',
            'error' => $e->getMessage()
        ];
    }
}

/**
 * API Endpoint: Lấy danh sách khóa học
 * GET: /backend/course/get_courses.php
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = getCourses();
    echo json_encode($result);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method không hợp lệ']);
}
?>