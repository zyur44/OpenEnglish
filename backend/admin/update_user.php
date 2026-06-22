<?php
require_once __DIR__ . '/../../connectdb/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Cập nhật thông tin người dùng
 * 
 * @param int $userId ID của người dùng
 * @param array $data Dữ liệu cần cập nhật (full_name, email, role_id)
 * @return array Kết quả với status, message, data
 */
function updateUser($userId, $data) {
    global $pdo;
    
    $userId = (int)$userId;
    
    if ($userId <= 0) {
        return [
            'success' => false,
            'message' => 'ID người dùng không hợp lệ',
            'data' => null
        ];
    }
    
    // Kiểm tra user tồn tại
    $check = $pdo->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
    $check->execute([$userId]);
    if (!$check->fetch()) {
        return [
            'success' => false,
            'message' => 'Không tìm thấy tài khoản',
            'data' => null
        ];
    }
    
    $errors = [];
    
    // Validate
    if (isset($data['full_name']) && empty(trim($data['full_name'])))
        $errors['full_name'] = 'Họ tên không được để trống';
    
    if (isset($data['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } else {
            $dup = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
            $dup->execute([$data['email'], $userId]);
            if ($dup->fetch()) $errors['email'] = 'Email đã dùng bởi tài khoản khác';
        }
    }
    
    if (isset($data['role_id']) && !in_array($data['role_id'], [1, 2]))
        $errors['role_id'] = 'Role không hợp lệ (1=Admin, 2=Student)';
    
    if (!empty($errors)) {
        return [
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $errors,
            'data' => null
        ];
    }
    
    try {
        $allowed = ['full_name', 'email', 'role_id'];
        $setClauses = [];
        $params = [];
        
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $setClauses[] = "{$field} = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($setClauses)) {
            return [
                'success' => false,
                'message' => 'Không có dữ liệu để cập nhật',
                'data' => null
            ];
        }
        
        $setClauses[] = 'updated_at = NOW()';
        $params[] = $userId;
        
        $pdo->prepare("UPDATE users SET " . implode(', ', $setClauses) . " WHERE id = ?")
            ->execute($params);
        
        // Lấy thông tin user vừa cập nhật
        $getStmt = $pdo->prepare("SELECT id, full_name, email, role_id, created_at, updated_at FROM users WHERE id = ?");
        $getStmt->execute([$userId]);
        $user = $getStmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'message' => 'Cập nhật thành công',
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
 * API Endpoint: Cập nhật người dùng
 * POST: /backend/admin/update_user.php
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
    
    if ($_SESSION['role'] !== 'Admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Không có quyền']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $userId = (int)($data['id'] ?? 0);
    
    if ($userId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id người dùng']);
        exit;
    }
    
    unset($data['id']); // Không cho phép cập nhật ID
    $result = updateUser($userId, $data);
    
    if (!$result['success']) {
        http_response_code($result['errors'] ? 422 : 400);
    }
    
    echo json_encode($result);
}
?>