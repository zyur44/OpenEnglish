<?php
session_start();
header('Content-Type: application/json');
require_once "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"No permission"]);
    exit;
}

try {

    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("
        UPDATE users
        SET fullname=?, email=?, role=?
        WHERE id=?
    ");

    $stmt->execute([$fullname, $email, $role, $id]);

    echo json_encode(["status"=>"success"]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}
?>