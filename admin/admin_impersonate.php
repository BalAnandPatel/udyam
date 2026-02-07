<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config/db.php';

// 1) security: only logged-in admin can impersonate
if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Admin not logged in.");
}

// 2) accept only POST with a target user_id
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
    die("Invalid request.");
}

$userId = (int)$_POST['user_id'];

// 3) validate target user exists
$stmt = $conn->prepare("SELECT id, name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res  = $stmt->get_result();
$user = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$user) {
    die("User not found.");
}

// 4) mark impersonation in session
$_SESSION['impersonating_admin'] = $_SESSION['admin_id']; // keep who is impersonating
$_SESSION['is_impersonating']    = true;

// 5) switch to user session
$_SESSION['user_id'] = (int)$user['id'];

// 6) redirect to user dashboard
header("Location: ../dashboard.php?impersonated=1");

exit;
