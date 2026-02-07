<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php'; // adjust path if needed

$user = null;
if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];
    $result = $conn->query("SELECT id, name FROM users WHERE id = $uid LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
}
?>
<?php if (isset($_SESSION['is_impersonating'])): ?>
  <div class="alert alert-warning text-center mb-0">
    ⚠️ You are impersonating <strong><?= htmlspecialchars($user['name']) ?> (ID: <?= $user['id'] ?>)</strong>.
    <a href="admin/stop_impersonate.php" class="btn btn-sm btn-dark ms-2">Return to Admin</a>
  </div>
<?php endif; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Video Earn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">🎬 Video Earn</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id']) && $user): ?>
          <!-- Show logged-in user name + ID -->
          <li class="nav-item">
            <span class="navbar-text text-white me-3">
              👤 <?= htmlspecialchars($user['name']) ?> (ID: <?= $user['id'] ?>)
            </span>
          </li>
          <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
          <li class="nav-item"><a href="wallet.php" class="nav-link">Wallet</a></li>
          <li class="nav-item"><a href="withdraw.php" class="nav-link">Withdraw</a></li>
          <li class="nav-item"><a href="genealogy.php" class="nav-link">My Genealogy</a></li>
          <li class="nav-item"><a href="direct_list.php" class="nav-link">Direct Member</a></li>
          <li class="nav-item"><a href="purchase_pin.php" class="nav-link">Purchase Pin</a></li>
          <li class="nav-item"><a href="user_kyc.php" class="nav-link">User KYC</a></li>
          <li class="nav-item"><a href="my_vouchers.php" class="nav-link">My Pins</a></li>
          <li class="nav-item"><a href="referral.php" class="nav-link">Referrals</a></li>
          <li class="nav-item"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
          <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
