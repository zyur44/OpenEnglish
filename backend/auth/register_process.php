<?php
require_once "../config/database.php";

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role)
                        VALUES (?, ?, ?, 'user')");
$stmt->execute([$fullname, $email, $password]);

echo json_encode(["status" => "success", "message" => "Register successful"]);
?>