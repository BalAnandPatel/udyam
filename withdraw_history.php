<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// Fetch all withdrawal requests
$sql = "SELECT * FROM withdrawals WHERE user_id=$user_id ORDER BY id DESC";
$res = $conn->query($sql) or die("SQL Error: " . $conn->error);

include 'includes/header.php';
?>

<div class="container mt-4">
    <h3>📜 Withdrawal History</h3>
    <p class="text-muted">Here you can see all your withdrawal requests.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount (₹)</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Approved At</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($res->num_rows > 0): ?>
            <?php while ($row = $res->fetch_assoc()): ?>
                <?php
                $approvedAt = (!empty($row['approved_at']) && $row['approved_at'] != '0000-00-00 00:00:00')
                    ? $row['approved_at']
                    : '-';
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>₹<?= number_format($row['amount'], 2) ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php elseif ($row['status'] == 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['requested_at'] ?></td>
                    <td><?= $approvedAt ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No withdrawal history found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="withdraw.php" class="btn btn-secondary">⬅ Back to Withdraw</a>
</div>

<?php include 'includes/footer.php'; ?>
