<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['video_id']) || !is_numeric($_POST['video_id'])) {
    header("Location: dashboard.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$video_id = (int) $_POST['video_id'];

// Fetch video
$stmt = $conn->prepare("SELECT earn_amount FROM videos WHERE id = ?");
$stmt->bind_param("i", $video_id);
if (!$stmt->execute()) die("Video query failed: " . $stmt->error);
$res = $stmt->get_result();
$video = $res->fetch_assoc();
$stmt->close();

if (!$video) die("Invalid video.");
$amount = $video['earn_amount'];

// Already rewarded?
$stmt = $conn->prepare("SELECT id FROM watch_history WHERE user_id=? AND video_id=?");
$stmt->bind_param("ii", $user_id, $video_id);
if (!$stmt->execute()) die("Check history failed: " . $stmt->error);
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    header("Location: user_dashboard.php?msg=already_watched");
    exit;
}
$stmt->close();

// Insert watch_history
$stmt = $conn->prepare("INSERT INTO watch_history (user_id, video_id, watched_at) VALUES (?,?,NOW())");
$stmt->bind_param("ii", $user_id, $video_id);
if (!$stmt->execute()) die("Insert watch_history failed: " . $stmt->error);
$stmt->close();

// Insert earning

$stmt = $conn->prepare("INSERT INTO wallet (user_id, balance) VALUES (?,?) 
                        ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)");
if (!$stmt) {
    die("Prepare failed (wallet): " . $conn->error);
}
$stmt->bind_param("id", $user_id, $amount);

// $stmt = $conn->prepare("INSERT IGNORE INTO earnings (user_id, video_id, amount, income_type) VALUES (?,?,?,'video')");
// $stmt->bind_param("iid", $user_id, $video_id, $amount);
// if (!$stmt->execute()) die("Insert earnings failed: " . $stmt->error);
// $stmt->close();

// Update wallet (requires user_id as PRIMARY KEY)
$stmt = $conn->prepare("INSERT INTO wallet (user_id, balance) VALUES (?,?) 
                        ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)");
$stmt->bind_param("id", $user_id, $amount);
if (!$stmt->execute()) die("Update wallet failed: " . $stmt->error);
$stmt->close();

header("Location: user_dashboard.php?msg=reward_success");
exit;
?>
