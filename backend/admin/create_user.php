<?php
session_start();
require_once "../config/database.php";

if ($_SESSION['role'] != 'admin') exit;

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role)
                        VALUES (?, ?, ?, ?)");
$stmt->execute([$fullname, $email, $password, $role]);

echo json_encode(["status" => "success"]);
?>