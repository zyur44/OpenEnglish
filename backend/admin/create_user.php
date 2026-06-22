<?php
session_start();
header('Content-Type: application/json');
require_once "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"No permission"]);
    exit;
}

try {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("
        INSERT INTO users(fullname,email,password,role)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([$fullname, $email, $password, $role]);

    echo json_encode(["status"=>"success"]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Create failed"]);
}
?>