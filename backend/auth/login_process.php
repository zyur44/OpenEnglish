<?php
require_once __DIR__ . '/auth_functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Method không hợp lệ']);
    exit;
}

// Support both JSON (API/AJAX) and standard form POST submissions
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data) || empty($data)) {
    $data = $_POST;
}

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$result = loginUser($email, $password);

// If the request was a normal form POST, redirect back to UI pages
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isForm = !empty($_POST) || stripos($contentType, 'application/x-www-form-urlencoded') !== false || stripos($contentType, 'multipart/form-data') !== false;

if ($isForm) {
    if ($result['status'] === 'success') {
        header('Location: ../../ui/home.php');
        exit;
    }

    // On failure redirect back to login with an error message
    $msg = urlencode($result['message'] ?? 'Login failed');
    header('Location: ../../ui/login.php?error=' . $msg);
    exit;
}

// Otherwise return JSON for API/AJAX callers
header('Content-Type: application/json');
if ($result['status'] === 'error') {
    http_response_code(400);
}

echo json_encode($result);
?>