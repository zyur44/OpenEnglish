<?php
// ===== Xử lý đăng xuất và chuyển hướng về trang chủ =====
require_once __DIR__ . '/../backend/auth/auth_functions.php';

logoutUser();
header('Location: home.php');
exit;
?>