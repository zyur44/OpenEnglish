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

$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Validate
$errors = [];
if (empty(trim($body['full_name'] ?? '')))
    $errors['full_name'] = 'Họ tên không được để trống';

if (empty($body['email']) || !filter_var($body['email'], FILTER_VALIDATE_EMAIL))
    $errors['email'] = 'Email không hợp lệ';

if (!empty($body['role']) && !in_array($body['role'], ['admin', 'teacher', 'student']))
    $errors['role'] = 'Role không hợp lệ';

// Kiểm tra email trùng
if (empty($errors['email'])) {
    $check = $db->prepare("SELECT id FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1");
    $check->execute([$body['email']]);
    if ($check->fetch()) $errors['email'] = 'Email đã được sử dụng';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ', 'errors' => $errors]);
    exit;
}

// Tạo mật khẩu tạm
$chars   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$tempPass = '';
for ($i = 0; $i < 10; $i++) $tempPass .= $chars[random_int(0, strlen($chars) - 1)];

$r
?>
