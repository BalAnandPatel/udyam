<?php
include "config/db.php";
//session_start();

// Run query
$sql = "SELECT k.*, u.name, u.email 
        FROM kyc k 
        JOIN users u ON k.user_id = u.id 
        ORDER BY k.created_at DESC";

$result = $conn->query($sql);

// Check query error
if (!$result) {
    die("<div class='alert alert-danger'>❌ Query Error: " . $conn->error . "</div>");
}

include "includes/header.php";
?>

<div class="container mt-4">
    <h2>KYC Submissions</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Full Name</th>
                    <th>Bank</th>
                    <th>Account</th>
                    <th>Photo</th>
                    <th>ID Proof</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th style="width:220px;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?><br><small><?= htmlspecialchars($row['email']) ?></small></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['bank_name']) ?></td>
                        <td><?= htmlspecialchars($row['account_number']) ?></td>
                        <td>
                            <?php if (!empty($row['photo'])): ?>
                                <img src="../<?= htmlspecialchars($row['photo']) ?>" width="50" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">No Photo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['id_proof_file'])): ?>
                                <a href="../<?= htmlspecialchars($row['id_proof_file']) ?>" target="_blank">
                                    <img src="../<?= htmlspecialchars($row['id_proof_file']) ?>" width="60" class="border">
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No Proof</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $badgeClass = 'warning';
                                if ($row['status']=='Approved') $badgeClass = 'success';
                                elseif ($row['status']=='Rejected') $badgeClass = 'danger';
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($row['status']) ?></span>
                        </td>
                        <td><?= date("d M Y H:i", strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="view_kyc.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                            <a href="update_kyc.php?id=<?= $row['id'] ?>&status=Approved" class="btn btn-sm btn-success">Approve</a>
                            <a href="update_kyc.php?id=<?= $row['id'] ?>&status=Rejected" class="btn btn-sm btn-danger">Reject</a>
                            <a href="update_kyc.php?id=<?= $row['id'] ?>&status=Pending" class="btn btn-sm btn-secondary">Reset</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center">No KYC submissions found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "includes/footer.php"; ?>
