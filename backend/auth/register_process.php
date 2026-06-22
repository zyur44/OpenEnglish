<?php
header('Content-Type: application/json');
require_once "../config/database.php";

try {

    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$fullname || !$email || !$password) {
        echo json_encode(["status"=>"error","message"=>"Missing data"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status"=>"error","message"=>"Email exists"]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users(fullname,email,password,role)
        VALUES (?,?,?,'user')
    ");

    $stmt->execute([$fullname, $email, $hash]);

    echo json_encode([
        "status"=>"success",
        "message"=>"Register success"
    ]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Server error"]);
}
?>