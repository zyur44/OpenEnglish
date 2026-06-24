<?php
    // 1. Validate đầu vào
function validateRegisterInput(array $data): array
{
    $errors = [];

    if (empty($data['full_name'])) {
        $errors['full_name'] = 'Họ tên không được để trống';
    }

    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    }

    if (empty($data['password']) || strlen($data['password']) < 8) {
        $errors['password'] = 'Mật khẩu tối thiểu 8 ký tự';
    } elseif (!preg_match('/[A-Z]/', $data['password']) || !preg_match('/[0-9]/', $data['password'])) {
        $errors['password'] = 'Mật khẩu cần có chữ hoa và số';
    }

    return ['isValid' => empty($errors), 'errors' => $errors];
}


// 2. Kiểm tra email đã tồn tại chưa
function checkEmailExists(PDO $db, string $email): bool
{
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}


// 3. Hash mật khẩu
function hashPassword(string $plainPassword): string
{
    return password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);
}


// 4. Tạo user mới
function createUser(PDO $db, array $data): array
{
    $stmt = $db->prepare("
        INSERT INTO users (full_name, email, password_hash, role, status, created_at)
        VALUES (?, ?, ?, 'student', 'unverified', NOW())
    ");
    $stmt->execute([
        $data['full_name'],
        $data['email'],
        $data['password_hash'],
    ]);

    $userId = $db->lastInsertId();

    return [
        'id'        => $userId,
        'full_name' => $data['full_name'],
        'email'     => $data['email'],
        'role'      => 'student',
        'status'    => 'unverified',
    ];
}


// 5. Tạo token xác thực email
function generateVerificationToken(PDO $db, int $userId): string
{
    $token     = bin2hex(random_bytes(32)); // 64 ký tự hex
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $stmt = $db->prepare("
        INSERT INTO email_verification_tokens (user_id, token, expires_at, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$userId, $token, $expiresAt]);

    return $token;
}


// 6. Gửi email xác thực
function sendVerificationEmail(string $email, string $token): bool
{
    $link    = "https://yourdomain.com/verify-email?token={$token}";
    $subject = 'Xác thực tài khoản học tiếng Anh';
    $message = "Chào bạn,\n\nVui lòng click link bên dưới để xác thực tài khoản:\n{$link}\n\nLink hết hạn sau 24 giờ.";
    $headers = 'From: noreply@yourdomain.com';

    // Dùng mail() thuần hoặc thay bằng PHPMailer/SendGrid
    return mail($email, $subject, $message, $headers);
}


// 7. Xác thực token từ link email
function verifyEmailToken(PDO $db, string $token): array|false
{
    $stmt = $db->prepare("
        SELECT * FROM email_verification_tokens
        WHERE token = ? AND used_at IS NULL AND expires_at > NOW()
        LIMIT 1
    ");
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false; // Token không hợp lệ hoặc hết hạn
    }

    // Kích hoạt tài khoản
    $db->prepare("
        UPDATE users SET status = 'active', email_verified_at = NOW()
        WHERE id = ?
    ")->execute([$row['user_id']]);

    // Đánh dấu token đã dùng
    $db->prepare("
        UPDATE email_verification_tokens SET used_at = NOW() WHERE id = ?
    ")->execute([$row['id']]);

    return ['user_id' => $row['user_id']];
}
?>