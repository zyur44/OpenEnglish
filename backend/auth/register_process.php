<?php
require_once __DIR__ . '/auth_functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Method không hợp lệ']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data) || empty($data)) {
    $data = $_POST;
}

$confirmPassword = $data['confirm_password'] ?? null;
if (isset($data['password']) && $confirmPassword !== null) {
    unset($data['confirm_password']);
}

$result = null;
if ($confirmPassword !== null && ($data['password'] ?? '') !== $confirmPassword) {
    $result = [
        'status' => 'error',
        'message' => 'Mật khẩu xác nhận không khớp',
        'errors' => ['confirm_password' => 'Mật khẩu xác nhận không khớp']
    ];
} else {
    $result = registerUser($data);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isForm = !empty($_POST) || stripos($contentType, 'application/x-www-form-urlencoded') !== false || stripos($contentType, 'multipart/form-data') !== false;

if ($isForm) {
    if ($result['status'] === 'success') {
        header('Location: ../../ui/login.php?success=' . urlencode('Register Completed'));
        exit;
    }

    $errorText = $result['message'] ?? 'Registration failed';
    if (!empty($result['errors']) && is_array($result['errors'])) {
        $errorMessages = array_filter(array_map('strval', $result['errors']), fn($value) => trim($value) !== '');
        if (!empty($errorMessages)) {
            $errorText = implode(' | ', $errorMessages);
        }
    }

    $msg = urlencode($errorText);
    $query = '?error=' . $msg;
    if (!empty($_POST['full_name'])) {
        $query .= '&full_name=' . urlencode($_POST['full_name']);
    }
    if (!empty($_POST['email'])) {
        $query .= '&email=' . urlencode($_POST['email']);
    }

    header('Location: ../../ui/register.php' . $query);
    exit;
}

if ($result['status'] === 'error') {
    http_response_code(400);
}

header('Content-Type: application/json');
echo json_encode($result);
?>
