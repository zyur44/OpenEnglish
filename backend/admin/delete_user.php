<?php
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Không có quyền']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method không hợp lệ']);
    exit;
}

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$userId = (int)($body['id'] ?? 0);

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu id người dùng']);
    exit;
}

// Không cho tự xóa chính mình
if ($userId === (int)$_SESSION['user_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Không thể xóa chính tài khoản của mình']);
    exit;
}

// Kiểm tra tồn tại
$check = $db->prepare("SELECT id FROM users WHERE id = ? AND deleted_at IS NULL LIMIT 1");
$check->execute([$userId]);
if (!$check->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy tài khoản']);
    exit;
}

// Soft delete
$db->prepare("UPDATE users SET deleted_at = NOW(), updated_at = NOW() WHERE id = ?")
   ->execute([$userId]);

echo json_encode(['success' => true, 'message' => 'Đã xóa tài khoản']);
?>