<?php
require_once __DIR__ . '/../../connectdb/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "
        SELECT 
            c.id,
            c.title,
            c.description,
            c.level,
            c.thumbnail,
            COUNT(u.id) AS total_units
        FROM courses c
        LEFT JOIN units u ON c.id = u.course_id
        WHERE c.is_active = 1
        GROUP BY c.id, c.title, c.description, c.level, c.thumbnail
        ORDER BY c.id ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $courses
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Không lấy được danh sách khóa học',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}