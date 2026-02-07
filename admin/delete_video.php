<?php
session_start();
require 'config/db.php'; // adjust path if needed

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: upload_video.php?err=invalid");
    exit;
}

$video_id = (int)$_GET['id'];

// Fetch video file to remove
$res = mysqli_query($conn, "SELECT file_path FROM videos WHERE id=$video_id");
$row = mysqli_fetch_assoc($res);

if ($row && !empty($row['file_path'])) {
    $file = '../public/uploads/' . $row['file_path'];
    if (file_exists($file)) {
        unlink($file); // delete file
    }
}

// Delete from DB
mysqli_query($conn, "DELETE FROM videos WHERE id=$video_id");

// Redirect back
header("Location:videos.php?msg=deleted");
exit;
