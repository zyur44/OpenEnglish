<?php
require_once __DIR__ . '/../../connectdb/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Xóa tài khoản người dùng (soft delete)
 * 
 * @param int $userId ID của người dùng cần xóa
 * @param int $currentUserId ID của người dùng hiện tại (để ngăn tự xóa)
 * @return array Kết quả với status, message
 */
function deleteUser($userId, $currentUserId = null) {
    global $pdo;
    
    $userId = (int)$userId;
    
    if ($userId <= 0) {
        return [
            'success' => false,
            'message' => 'ID người dùng không hợp lệ'
        ];
    }
    
    // Không cho tự xóa chính mình
    if ($currentUserId && $userId === (int)$currentUserId) {
        return [
            'success' => false,
            'message' => 'Không thể xóa chính tài khoản của mình'
        ];
    }
    
    // Kiểm tra tồn tại
    $check = $pdo->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
    $check->execute([$userId]);
    if (!$check->fetch()) {
        return [
            'success' => false,
            'message' => 'Không tìm thấy tài khoản'
        ];
    }
    
    try {
        // Soft delete
        $stmt = $pdo->prepare("
            UPDATE users 
            SET updated_at = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        
        return [
            'success' => true,
            'message' => 'Đã xóa tài khoản'
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ];
    }
}

/**
 * API Endpoint: Xóa người dùng
 * POST: /backend/admin/delete_user.php
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $userId = (int)($data['id'] ?? 0);
    
    if ($userId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id người dùng']);
        exit;
    }
    
    $result = deleteUser($userId, $_SESSION['user_id']);
    
    if (!$result['success']) {
        http_response_code(400);
    }
    
    echo json_encode($result);
}
?>