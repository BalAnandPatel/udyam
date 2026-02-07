<?php
include "config/db.php"; 
ini_set('display_errors', 1);
error_reporting(E_ALL);

$msg = "";

// ✅ Delete reward
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM rewards WHERE id=$id")) {
        $msg = "<div class='alert alert-success'>Reward deleted successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error: ".$conn->error."</div>";
    }
}

// ✅ Add new reward
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $reward_amount = $_POST['reward_amount'];

    $sql = "INSERT INTO rewards (title, description, reward_amount) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $title, $description, $reward_amount);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Reward added successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="container mt-4">
    <h2>Add Reward</h2>
    <?= $msg ?>

    <!-- Add Reward Form -->
    <form method="post" class="card p-4 shadow-sm mb-4" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">Reward Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Reward Amount (₹)</label>
            <input type="number" step="0.01" name="reward_amount" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Reward</button>
    </form>

    <!-- Reward List -->
    <h3>Existing Rewards</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Amount (₹)</th>
                    <th>Created At</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM rewards ORDER BY created_at DESC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".htmlspecialchars($row['title'])."</td>";
                    echo "<td>".substr(htmlspecialchars($row['description']),0,50)."...</td>";
                    echo "<td>₹".number_format($row['reward_amount'],2)."</td>";
                    echo "<td>".date("d M Y H:i", strtotime($row['created_at']))."</td>";
                    echo "<td>
                            <a href='edit_reward.php?id=".$row['id']."' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='add_reward.php?delete=".$row['id']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this reward?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No rewards found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "includes/footer.php"; ?>
