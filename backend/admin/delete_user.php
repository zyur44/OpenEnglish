<?php
session_start();
require_once "../config/database.php";

if ($_SESSION['role'] != 'admin') exit;

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["status" => "success"]);
?>