<?php
require_once __DIR__ . '/../backend/auth/auth_functions.php';

// Log out and redirect to home
logoutUser();
header('Location: home.php');
exit;
?>