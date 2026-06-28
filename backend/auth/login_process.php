<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth_functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method không hợp lệ']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

$result = loginUser($email, $password);

if ($result['status'] === 'error') {
    http_response_code(400);
}

echo json_encode($result);
?>