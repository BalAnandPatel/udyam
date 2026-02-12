<?php
session_start();
require '../config/db.php'; // adjust path if needed

// (Optional) check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Handle Approve / Reject
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $action = $_POST['action'];

    if ($action == "approve") {
        $sql = "UPDATE withdrawals SET status='approved', approved_at=NOW() WHERE id=$id";
    } elseif ($action == "reject") {
        $sql = "UPDATE withdrawals SET status='rejected', approved_at=NOW() WHERE id=$id";
    }

    if (isset($sql) && $conn->query($sql)) {
        $message = "<div class='alert alert-success'>✅ Request #$id updated successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
}

// Fetch all withdrawal requests
$sql = "SELECT w.*, u.name 
        FROM withdrawals w 
        LEFT JOIN users u ON w.user_id = u.id 
        ORDER BY w.id DESC";
$res = $conn->query($sql) or die("SQL Error: " . $conn->error);

include 'includes/header.php';
?>

<div class="container mt-4">
    <h3>💵 Manage Withdrawal Requests</h3>
    <?= $message ?>

    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>User Id</th>
                <th>Requested Amount</th>
                <th>Admin Charge (10%)</th>
                <th>TDS (5%)</th>
                <th>Renewal Charges (5%)</th>
                <th>Net Amount</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Approved At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars(isset($row['name']) ? $row['name'] : 'User#'.$row['user_id']) ?></td>
                <td><?= $row['user_id'] ?></td>
                <td>₹<?= number_format($row['amount'], 2) ?></td>
                <td>₹<?= number_format($row['admin_charge'], 2) ?></td>
                <td>₹<?= number_format($row['tds'], 2) ?></td>
                <td>₹<?= number_format($row['renewal_charge'], 2) ?></td>
                <td><b>₹<?= number_format($row['net_amount'], 2) ?></b></td>
                <td>
                    <span class="badge bg-<?= $row['status']=='approved' ? 'success' : ($row['status']=='rejected' ? 'danger' : 'warning') ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td><?= date("Y-m-d H:i:s", strtotime($row['created_at'])) ?></td>
                <td>
                    <?php 
                        if (!empty($row['approved_at']) && $row['approved_at'] != '0000-00-00 00:00:00') {
                            echo date("Y-m-d H:i:s", strtotime($row['approved_at']));
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
