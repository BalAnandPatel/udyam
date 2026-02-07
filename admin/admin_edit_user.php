<?php
include 'includes/header.php';

// ✅ Validate user_id
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo "<div class='alert alert-danger'>Invalid User ID</div>";
    include 'includes/footer.php';
    exit;
}

$user_id = (int)$_GET['user_id'];

// ✅ Fetch user data
$res = $conn->query("SELECT * FROM users WHERE id = $user_id LIMIT 1");
if (!$res || $res->num_rows === 0) {
    echo "<div class='alert alert-danger'>User not found</div>";
    include 'includes/footer.php';
    exit;
}

$user = $res->fetch_assoc();

// ✅ Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = $conn->real_escape_string($_POST['name']);
    $email  = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    // $wallet = (float)$_POST['wallet_balance'];
    $active = isset($_POST['is_active']) ? 1 : 0;

    // Optional password update
    if (!empty($_POST['password'])) {
        $plain = $conn->real_escape_string($_POST['password']);
        $hash  = password_hash($plain, PASSWORD_DEFAULT);

        $conn->query("
            UPDATE users SET
                name='$name',
                email='$email',
                mobile='$mobile',
                wallet_balance='$wallet',
                is_active='$active',
                plain_password='$plain',
                password='$hash'
            WHERE id=$user_id
        ");
    } else {
        $conn->query("
            UPDATE users SET
                name='$name',
                email='$email',
                mobile='$mobile',
               
                is_active='$active'
            WHERE id=$user_id
        ");
    }

    echo "<div class='alert alert-success'>User updated successfully</div>";

    // Refresh data
    $res = $conn->query("SELECT * FROM users WHERE id = $user_id LIMIT 1");
    $user = $res->fetch_assoc();
}
?>

<div class="row">
<div class="col-md-8 offset-md-2">

<h3 class="mb-4">✏️ Edit User (ID: <?php echo $user_id; ?>)</h3>

<form method="post">

<div class="mb-3">
<label class="form-label">Name</label>
<input type="text" name="name" class="form-control"
value="<?php echo htmlspecialchars($user['name']); ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control"
value="<?php echo htmlspecialchars($user['email']); ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Mobile</label>
<input type="text" name="mobile" class="form-control"
value="<?php echo htmlspecialchars($user['mobile']); ?>">
</div>



<div class="mb-3">
<label class="form-label">New Password (leave blank to keep old)</label>
<input type="text" name="password" class="form-control">
</div>

<div class="form-check mb-3">
<input class="form-check-input" type="checkbox" name="is_active"
<?php echo !empty($user['is_active']) ? 'checked' : ''; ?>>
<label class="form-check-label">Active</label>
</div>

<button type="submit" class="btn btn-success">💾 Save Changes</button>
<a href="users.php" class="btn btn-secondary">⬅ Back</a>

</form>

</div>
</div>

<?php include 'includes/footer.php'; ?>
