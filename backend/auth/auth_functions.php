<?php
require_once __DIR__ . '/../../connectdb/db.php';

function ensureSessionStarted(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function getUserByEmail(string $email): ?array
{
    global $pdo;

    $sql = "SELECT u.id, u.full_name, u.email, u.password_hash AS password_hash, u.role_id, COALESCE(r.name, 'Student') AS role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE u.email = ? LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([trim($email)]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

function getCurrentUserProfile(): ?array
{
    ensureSessionStarted();

    if (empty($_SESSION['user_id'])) {
        return null;
    }

    global $pdo;

    $stmt = $pdo->prepare("SELECT id, full_name, email, role_id FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([(int)$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return null;
    }

    $email = $user['email'] ?? '';
    $username = $email;
    $atPos = strpos($email, '@');

    if ($atPos !== false) {
        $username = substr($email, 0, $atPos);
    } elseif (!empty($user['full_name'])) {
        $username = trim($user['full_name']);
    }

    $user['username'] = $username;

    return $user;
}

function normalizeRoleName(string $roleName): string
{
    $roleName = strtolower(trim($roleName));
    if ($roleName === 'admin' || $roleName === 'administrator') {
        return 'admin';
    }
    return 'student';
}

function validateRegisterInput(array $data): array
{
    $errors = [];

    if (empty(trim($data['full_name'] ?? ''))) {
        $errors['full_name'] = 'Họ tên không được để trống';
    }

    if (empty(trim($data['email'] ?? '')) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    }

    if (empty($data['password']) || strlen($data['password']) < 8) {
        $errors['password'] = 'Mật khẩu tối thiểu 8 ký tự';
    } elseif (!preg_match('/[A-Z]/', $data['password']) || !preg_match('/[0-9]/', $data['password'])) {
        $errors['password'] = 'Mật khẩu cần có chữ hoa và số';
    }

    return ['isValid' => empty($errors), 'errors' => $errors];
}

function isEmailTaken(string $email): bool
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([trim($email)]);
    return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
}

function registerUser(array $data): array
{
    global $pdo;

    $validation = validateRegisterInput($data);
    if (!$validation['isValid']) {
        return [
            'status' => 'error',
            'message' => 'Dữ liệu đăng ký không hợp lệ',
            'errors' => $validation['errors']
        ];
    }

    if (isEmailTaken($data['email'])) {
        return [
            'status' => 'error',
            'message' => 'Email đã tồn tại',
            'errors' => ['email' => 'Email đã được sử dụng']
        ];
    }

    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $roleId = 2; // student

    try {
        $stmt = $pdo->prepare("INSERT INTO users (role_id, full_name, email, password_hash, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $roleId,
            trim($data['full_name']),
            trim($data['email']),
            $passwordHash
        ]);

        $userId = $pdo->lastInsertId();

        return [
            'status' => 'success',
            'message' => 'Đăng ký thành công',
            'data' => [
                'user_id' => $userId,
                'full_name' => trim($data['full_name']),
                'email' => trim($data['email']),
                'role_id' => $roleId
            ]
        ];
    } catch (PDOException $e) {
        return [
            'status' => 'error',
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ];
    }
}

function loginUser(string $email, string $password): array
{
    if (trim($email) === '' || trim($password) === '') {
        return [
            'status' => 'error',
            'message' => 'Missing email or password'
        ];
    }

    $user = getUserByEmail($email);
    if (!$user) {
        return [
            'status' => 'error',
            'message' => 'Wrong credentials'
        ];
    }

    if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
        return [
            'status' => 'error',
            'message' => 'Wrong credentials'
        ];
    }

    ensureSessionStarted();

    $role = normalizeRoleName($user['role_name']);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'] ?? '';
    $_SESSION['role'] = $role;
    $_SESSION['role_id'] = $user['role_id'];

    return [
        'status' => 'success',
        'message' => 'Login successful',
        'data' => [
            'user_id' => $user['id'],
            'role' => $role
        ]
    ];
}

function getSessionStatus(): array
{
    ensureSessionStarted();

    if (!isset($_SESSION['user_id'])) {
        return [
            'status' => 'error',
            'message' => 'Not logged in'
        ];
    }

    $role = $_SESSION['role'] ?? 'student';

    return [
        'status' => 'success',
        'user_id' => $_SESSION['user_id'],
        'role' => $role,
        'is_admin' => $role === 'admin'
    ];
}

function logoutUser(): array
{
    ensureSessionStarted();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    return [
        'status' => 'success',
        'message' => 'Logged out'
    ];
}
?>