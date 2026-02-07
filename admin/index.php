<?php
include '../config/db.php';

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check admin credentials
    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) === 1) {
        $admin = mysqli_fetch_assoc($res);
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $err = "⚠️ Invalid username or password";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login | Video Earn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/assets/css/style.css" rel="stylesheet">

<style>
@media (max-width: 576px) {
    .card {
        width: 100% !important;
        max-width: 360px !important;
        margin: 0 auto !important;
        padding: 20px !important;
    }
    .card-body {
        padding: 25px !important;
    }
}
</style>


</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Admin Login</h3>

                    <?php if($err != ''): ?>
                        <div class="alert alert-danger"><?= $err ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100">Login</button>
                    </form>

                    <p class="text-center mt-3">
                        <a href="login.php">Back to User Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
