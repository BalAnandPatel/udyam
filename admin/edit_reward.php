<?php
include "config/db.php"; 
ini_set('display_errors', 1);
error_reporting(E_ALL);

$msg = "";

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}
$id = (int)$_GET['id'];

// ✅ Fetch reward
$res = $conn->query("SELECT * FROM rewards WHERE id=$id");
if ($res->num_rows == 0) {
    die("Reward not found.");
}
$reward = $res->fetch_assoc();

// ✅ Update reward
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $reward_amount = $_POST['reward_amount'];

    $sql = "UPDATE rewards SET title=?, description=?, reward_amount=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $title, $description, $reward_amount, $id);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Reward updated successfully!</div>";
        // refresh values
        $reward['title'] = $title;
        $reward['description'] = $description;
        $reward['reward_amount'] = $reward_amount;
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="container mt-4">
    <h2>Edit Reward (ID: <?= $id ?>)</h2>
    <?= $msg ?>
    <form method="post" class="card p-4 shadow-sm" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">Reward Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($reward['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($reward['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Reward Amount (₹)</label>
            <input type="number" step="0.01" name="reward_amount" class="form-control" value="<?= htmlspecialchars($reward['reward_amount']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Reward</button>
        <a href="add_reward.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include "includes/footer.php"; ?>
