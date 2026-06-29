<?php
require_once __DIR__ . '/../../connectdb/db.php';


/**
 * Lấy chi tiết unit cùng với video và tài liệu
 * 
 * @param int $unitId ID của unit
 * @return array Kết quả với success, data hoặc message
 */
function getUnitDetail($unitId) {
    global $pdo;
    
    $unitId = (int)$unitId;
    
    if ($unitId <= 0) {
        return [
            'success' => false,
            'message' => 'Thiếu mã Unit'
        ];
    }
    
    try {
        // Lấy thông tin unit
        $sqlUnit = "
            SELECT 
                u.id,
                u.course_id,
                u.title,
                u.order_index,
                c.title AS course_title
            FROM units u
            INNER JOIN courses c ON u.course_id = c.id
            WHERE u.id = :unit_id
            LIMIT 1
        ";
        
        $stmtUnit = $pdo->prepare($sqlUnit);
        $stmtUnit->execute([':unit_id' => $unitId]);
        
        $unit = $stmtUnit->fetch(PDO::FETCH_ASSOC);
        
        if (!$unit) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy Unit'
            ];
        }
        
        // Lấy danh sách videos
        $sqlVideos = "
            SELECT 
                id,
                title,
                video_url,
                duration
            FROM videos
            WHERE unit_id = :unit_id
            ORDER BY id ASC
        ";
        
        $stmtVideos = $pdo->prepare($sqlVideos);
        $stmtVideos->execute([':unit_id' => $unitId]);
        
        $videos = $stmtVideos->fetchAll(PDO::FETCH_ASSOC);
        
        // Lấy danh sách tài liệu
        $sqlDocuments = "
            SELECT 
                id,
                title,
                file_url
            FROM documents
            WHERE unit_id = :unit_id
            ORDER BY id ASC
        ";
        
        $stmtDocuments = $pdo->prepare($sqlDocuments);
        $stmtDocuments->execute([':unit_id' => $unitId]);
        
        $documents = $stmtDocuments->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'data' => [
                'unit' => $unit,
                'videos' => $videos,
                'documents' => $documents
            ]
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Không lấy được nội dung Unit',
            'error' => $e->getMessage()
        ];
    }
}

/**
 * API Endpoint: Lấy chi tiết unit
 * GET: /backend/course/get_unit_detail.php?unit_id=1
 */
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $unitId = $_GET['unit_id'] ?? 0;
        $result = getUnitDetail($unitId);
        echo json_encode($result);
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method không hợp lệ']);
}
?>