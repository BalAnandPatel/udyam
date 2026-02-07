<?php
// ✅ Always start session first
session_start();

// ✅ Database connection
require 'config/db.php';

// ✅ Protect page: only logged in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Ensure UTF-8 encoding
header("Content-Type: text/html; charset=UTF-8");
mysqli_set_charset($conn, "utf8");

// ✅ Get video details (example)
$video_title = "ABC";
$video_desc  = "ABC";
$video_reward = 1.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Task</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- ✅ Navbar -->
<nav style="background:#1e1f22; padding:15px; color:#fff; display:flex; justify-content:space-between;">
    <div style="font-weight:bold;">🎬 Video Earn</div>
    <div>
        <a href="dashboard.php" style="margin-right:15px; color:#ccc;">Dashboard</a>
        <a href="wallet.php" style="margin-right:15px; color:#ccc;">Wallet</a>
        <a href="referrals.php" style="margin-right:15px; color:#ccc;">Referrals</a>
        <a href="watch.php" style="margin-right:15px; color:#ccc;">🎥 Watch Videos</a>
        <a href="logout.php" style="color:red;">Logout</a>
    </div>
</nav>

<!-- ✅ Video Task -->
<section style="max-width:700px; margin:40px auto; background:#fff; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1); padding:20px;">
    <h2>🎥 Your Video Task</h2>
    <div style="border:1px solid #ddd; border-radius:8px; padding:20px; margin-top:15px;">
        <h3><?php echo htmlspecialchars($video_title); ?></h3>
        <p><?php echo htmlspecialchars($video_desc); ?></p>
        <p><strong>Reward:</strong> ₹<?php echo number_format($video_reward, 2); ?></p>
        <a href="watch_video.php?id=1" style="display:inline-block; background:#1d9b63; color:#fff; padding:10px 20px; text-decoration:none; border-radius:6px; margin-top:10px;">
            ▶ Watch Now
        </a>
    </div>
</section>

<!-- ✅ Footer -->
<footer style="text-align:center; padding:20px; margin-top:40px; color:#555;">
    <a href="dashboard.php">← Back to Dashboard</a><br><br>
    <small>© 2025 Video Earn. All Rights Reserved.</small>
</footer>

</body>
</html>
