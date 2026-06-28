<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth_functions.php';

$result = logoutUser();

echo json_encode($result);
?>