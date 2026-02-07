<?php
include '/config/db.php';
if (isset($_SESSION['user_id'])) header("Location: dashboard.php");
if (isset($_SESSION['admin_id'])) header("Location: dashboard.php");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Video Earn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="text-center py-5">
  <div class="container">
    <h1 class="mb-3">🎬 Video Earn Platform</h1>
    <p class="lead">Watch videos, earn money & invite friends.</p>
    <a href="login.php" class="btn btn-primary btn-lg me-2">Login</a>
    <a href="register.php" class="btn btn-success btn-lg">Register</a>
    <p class="mt-5"><a href="admin/index.php">Admin Login</a></p>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>
</html>
