<?php
session_start();
if (!isset($_SESSION['temp_reg'])) {
    header("Location: register.php");
    exit;
}
$reg = $_SESSION['temp_reg'];

if ($_POST['action'] === 'login') {
    $_SESSION['user_id'] = $reg['user_id']; // real login
    unset($_SESSION['temp_reg']);
    header("Location: dashboard.php");
    exit;
} else {
    unset($_SESSION['temp_reg']);
    header("Location: login.php");
    exit;
}
