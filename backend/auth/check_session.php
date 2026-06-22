<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

echo json_encode([
    "status" => "success",
    "user_id" => $_SESSION['user_id'],
    "role" => $_SESSION['role']
]);
?>