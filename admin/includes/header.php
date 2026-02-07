<?php
include '../config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect admin pages FIRST (before output)
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$isImpersonating = isset($_SESSION['is_impersonating']) && isset($_SESSION['user_id']);
$impersonationBanner = ''; // Store banner HTML for later safe output

if ($isImpersonating) {
    $uid = (int)$_SESSION['user_id'];
    $u = $conn->query("SELECT name FROM users WHERE id = $uid");
    $uname = $u ? $u->fetch_assoc()['name'] : 'User';
    $impersonationBanner = '
        <div class="alert alert-warning text-center mb-0">
            ⚠️ You are impersonating <strong>' . htmlspecialchars($uname) . ' (ID: ' . $uid . ')</strong>.
            <a href="stop_impersonate.php" class="btn btn-sm btn-dark ms-2">Return to Admin</a>
        </div>';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Panel | Video Earn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../public/assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Show impersonation banner safely -->
<?= $impersonationBanner ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- Dashboard -->
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>

        <!-- Content Management -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="contentDropdown" role="button" data-bs-toggle="dropdown">
            Content
          </a>
          <ul class="dropdown-menu" aria-labelledby="contentDropdown">
            <li><a class="dropdown-item" href="videos.php">Videos</a></li>
            <li><a class="dropdown-item" href="watch_history.php">Watch History</a></li>
            <li><a class="dropdown-item" href="add_product.php">Add Product</a></li>
            <li><a class="dropdown-item" href="add_reward.php">Insert Reward</a></li>
          </ul>
        </li>

        <!-- User Management -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            Users
          </a>
          <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="users.php">Manage Users</a></li>
            <li><a class="dropdown-item" href="manage_kyc.php">KYC Verification</a></li>
          </ul>
        </li>

        <!-- Pin Management -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pinDropdown" role="button" data-bs-toggle="dropdown">
            Pins
          </a>
          <ul class="dropdown-menu" aria-labelledby="pinDropdown">
            <li><a class="dropdown-item" href="pin_requests.php">Pin Requests</a></li>
            <li><a class="dropdown-item" href="manage_pins.php">Manage Pins</a></li>
          </ul>
        </li>

        <!-- Finance -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown">
            Finance
          </a>
          <ul class="dropdown-menu" aria-labelledby="financeDropdown">
            <li><a class="dropdown-item" href="insert_payment.php">Insert Payment</a></li>
            <li><a class="dropdown-item" href="earnings.php">Earnings</a></li>
            <li><a class="dropdown-item" href="withdraw_requests.php">Withdrawals</a></li>
          </ul>
        </li>

        <!-- Website Content -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="websiteDropdown" role="button" data-bs-toggle="dropdown">
            Website Content
          </a>
          <ul class="dropdown-menu" aria-labelledby="websiteDropdown">
            <li><a class="dropdown-item" href="upload_legal.php">Legal Documents</a></li>
            <li><a class="dropdown-item" href="upload_business_plan.php">Business Plans</a></li>
            <li><a class="dropdown-item" href="manage_gallery.php">Gallery</a></li>
            <li><a class="dropdown-item" href="manage_news.php">Manage News</a></li>
            <li><a class="dropdown-item" href="slider.php">Admin Slide Update</a></li>
            <li><a class="dropdown-item" href="manage_team.php">Manage Team</a></li>
          </ul>
        </li>

        <!-- LOGOUT (Added at Last) -->
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
<!-- Page content continues here -->
