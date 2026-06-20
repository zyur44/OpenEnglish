<?php
require_once __DIR__ . '/../../connectdb/db.php';

header('Content-Type: application/json; charset=utf-8');

$unitId = 0;

if (isset($_GET['unit_id'])) {
    $unitId = (int) $_GET['unit_id'];
}

if ($unitId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu mã Unit'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sqlUnit = "
        SELECT 
            u.id,
            u.course_id,
            u.title,
            u.description,
            u.sort_order,
            c.title AS course_title
        FROM units u
        INNER JOIN courses c ON u.course_id = c.id
        WHERE u.id = :unit_id
        AND u.is_active = 1
        LIMIT 1
    ";

    $stmtUnit = $pdo->prepare($sqlUnit);
    $stmtUnit->execute([
        ':unit_id' => $unitId
    ]);

    $unit = $stmtUnit->fetch(PDO::FETCH_ASSOC);

    if (!$unit) {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy Unit'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sqlVideos = "
        SELECT 
            id,
            title,
            video_url,
            duration
        FROM videos
        WHERE unit_id = :unit_id
        AND is_active = 1
        ORDER BY id ASC
    ";

    $stmtVideos = $pdo->prepare($sqlVideos);
    $stmtVideos->execute([
        ':unit_id' => $unitId
    ]);

    $videos = $stmtVideos->fetchAll(PDO::FETCH_ASSOC);

    $sqlDocuments = "
        SELECT 
            id,
            title,
            file_url
        FROM documents
        WHERE unit_id = :unit_id
        AND is_active = 1
        ORDER BY id ASC
    ";

    $stmtDocuments = $pdo->prepare($sqlDocuments);
    $stmtDocuments->execute([
        ':unit_id' => $unitId
    ]);

    $documents = $stmtDocuments->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'unit' => $unit,
            'videos' => $videos,
            'documents' => $documents
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Không lấy được nội dung Unit',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}