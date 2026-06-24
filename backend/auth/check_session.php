<?php
session_start();
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            "status"=>"error",
            "message"=>"Not logged in"
        ]);
        exit;
    }

    $role = $_SESSION['role'];

    echo json_encode([
        "status"=>"success",
        "user_id"=>$_SESSION['user_id'],
        "role"=>$role,

        "is_admin" => ($role === "admin")
    ]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>"Server error"]);
}
?>