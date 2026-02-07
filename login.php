<?php
require 'config/db.php'; 
//session_start();

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login']); // can be email or user_id
    $password = $_POST['password'];

    // prepare query: check if input matches email OR id
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? OR id = ?");
    mysqli_stmt_bind_param($stmt, "si", $login, $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user) {
        // check MD5 password (since registration uses md5)
        if ($user['password'] === md5($password)) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $err = "⚠️ Invalid Email / ID or Password.";
        }
    } else {
        $err = "⚠️ Invalid Email / ID or Password.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login | Video Earn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="text-center mb-4">User Login</h3>

          <?php if(!empty($err)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
          <?php endif; ?>

          <form method="post">
            <div class="mb-3">
  <label class="form-label">Email or User ID</label>
  <input type="text" name="login" class="form-control" required>
</div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>
          </form>

          <p class="text-center mt-3">
            Don’t have an account? <a href="register.php">Register</a>
          </p>
          
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
