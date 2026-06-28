<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth_functions.php';

$result = getSessionStatus();

if ($result['status'] === 'error') {
    http_response_code(401);
}

echo json_encode($result);
?>