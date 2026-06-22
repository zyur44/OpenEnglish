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

// Kiểm tra user tồn tại
$check = $db->prepare("SELECT id FROM users WHERE id = ? AND deleted_at IS NULL LIMIT 1");
$check->execute([$userId]);
if (!$check->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy tài khoản']);
    exit;
}

// Validate
$errors = [];
if (isset($body['full_name']) && empty(trim($body['full_name'])))
    $errors['full_name'] = 'Họ tên không được để trống';

if (isset($body['email'])) {
    if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    } else {
        $dup = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL LIMIT 1");
        $dup->execute([$body['email'], $userId]);
        if ($dup->fetch()) $errors['email'] = 'Email đã dùng bởi tài khoản khác';
    }
}

if (isset($body['role']) && !in_array($body['role'], ['admin', 'teacher', 'student']))
    $errors['role'] = 'Role không hợp lệ';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ', 'errors' => $errors]);
    exit;
}

$allowed    = ['full_name', 'email', 'role'];
$setClauses = [];
$params     = [];

foreach ($allowed as $field) {
    if (array_key_exists($field, $body)) {
        $setClauses[] = "{$field} = ?";
        $params[]     = $body[$field];
    }
}

if (empty($setClauses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Không có dữ liệu để cập nhật']);
    exit;
}

$setClauses[] = 'updated_at = NOW()';
$params[]     = $userId;

$db->prepare("UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = ?")
   ->execute($params);

$stmt = $db->prepare("SELECT id, full_name, email, role, status, updated_at FROM users WHERE id = ?");
$stmt->execute([$userId]);

echo json_encode([
    'success' => true,
    'message' => 'Cập nhật thành công',
    'data'    => $stmt->fetch(PDO::FETCH_ASSOC),
]);


?>