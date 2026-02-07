<?php
include "config/db.php";
//session_start();

// Check if ID and Status provided
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("❌ Invalid request.");
}

$id = (int) $_GET['id'];
$status = $_GET['status'];

// Allow only valid statuses
$valid_status = ['Approved', 'Rejected', 'Pending'];
if (!in_array($status, $valid_status)) {
    die("❌ Invalid status.");
}

// Update KYC status
$stmt = $conn->prepare("UPDATE kyc SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    $_SESSION['msg'] = "✅ KYC status updated to <b>$status</b>";
} else {
    $_SESSION['msg'] = "❌ Error updating status: " . $stmt->error;
}

// Redirect back to Manage KYC page
header("Location: manage_kyc.php");
exit;
