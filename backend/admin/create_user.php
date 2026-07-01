<?php
require_once __DIR__ . '/../../connectdb/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Tạo tài khoản người dùng mới
 * 
 * @param array $data Dữ liệu người dùng (full_name, email, role, password)
 * @return array Kết quả với status, message, data
 */
function createUser($data) {
    global $pdo;
    
    $errors = [];
    
    // Validate
    if (empty(trim($data['full_name'] ?? '')))
        $errors['full_name'] = 'Họ tên không được để trống';
    
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Email không hợp lệ';
    
    if (!empty($data['role']) && !in_array($data['role'], [1, 2]))
        $errors['role'] = 'Role không hợp lệ (1=Admin, 2=Student)';
    
    // Kiểm tra email trùng
    if (empty($errors['email'])) {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->execute([$data['email']]);
        if ($check->fetch()) $errors['email'] = 'Email đã được sử dụng';
    }
    
    if (!empty($errors)) {
        return [
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $errors
        ];
    }
    
    try {
        // Mật khẩu mặc định hoặc từ input
        $password = $data['password'] ?? bin2hex(random_bytes(5));
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $role_id = $data['role'] ?? 2; // Mặc định là Student
        
        $stmt = $pdo->prepare("
            INSERT INTO users (role_id, full_name, email, password_hash, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([$role_id, $data['full_name'], $data['email'], $password_hash]);
        
        $userId = $pdo->lastInsertId();
        
        // Lấy thông tin user vừa tạo
        $getStmt = $pdo->prepare("SELECT id, full_name, email, role_id, created_at FROM users WHERE id = ?");
        $getStmt->execute([$userId]);
        $user = $getStmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'message' => 'Tạo tài khoản thành công',
            'data' => $user
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage(),
            'data' => null
        ];
    }
}

/**
 * API Endpoint: Tạo tài khoản mới
 * POST: /backend/admin/create_user.php
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $result = createUser($data);
    
    if (!$result['success']) {
        http_response_code($result['errors'] ? 422 : 400);
    }
    
    echo json_encode($result);
}
?>
