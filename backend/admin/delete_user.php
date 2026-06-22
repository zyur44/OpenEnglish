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

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["status"=>"success"]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Delete failed"]);
}
?>