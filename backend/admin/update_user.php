<?php
session_start();
require_once "../config/database.php";

if ($_SESSION['role'] != 'admin') exit;

$id = $_POST['id'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$role = $_POST['role'];

$stmt = $conn->prepare("UPDATE users
                        SET fullname=?, email=?, role=?
                        WHERE id=?");

$stmt->execute([$fullname, $email, $role, $id]);

echo json_encode(["status" => "success"]);
?>