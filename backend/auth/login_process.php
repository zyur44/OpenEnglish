<?php
session_start();
header('Content-Type: application/json');
require_once "../config/database.php";

try {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode(["status"=>"error","message"=>"Missing data"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(["status"=>"error","message"=>"Wrong credentials"]);
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    echo json_encode([
        "status"=>"success",
        "role"=>$user['role']
    ]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Server error"]);
}
?>