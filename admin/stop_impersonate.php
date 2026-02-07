<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Must be currently impersonating
if (!isset($_SESSION['is_impersonating']) || !isset($_SESSION['impersonating_admin'])) {
    die("You are not impersonating any user.");
}

// restore admin session
$_SESSION['admin_id'] = $_SESSION['impersonating_admin'];

// clean up impersonation flags + user session
unset($_SESSION['impersonating_admin']);
unset($_SESSION['is_impersonating']);
unset($_SESSION['user_id']);

// go back to admin area
header("Location: dashboard.php?back=1");
exit;
