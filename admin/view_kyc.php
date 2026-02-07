<?php
include "config/db.php";
//session_start();

if (!isset($_GET['id'])) {
    die("❌ Invalid request.");
}

$id = (int)$_GET['id'];

// Fetch KYC with user info
$sql = "SELECT k.*, u.name, u.email 
        FROM kyc k 
        JOIN users u ON k.user_id = u.id 
        WHERE k.id = $id LIMIT 1";
$res = $conn->query($sql);

if (!$res || $res->num_rows == 0) {
    die("❌ KYC record not found.");
}

$row = $res->fetch_assoc();

include "includes/header.php";
?>

<div class="container mt-4">
    <h2>KYC Details</h2>
    <div class="card p-4 shadow-sm">

        <h5>User Info</h5>
        <p><b>Username:</b> <?= htmlspecialchars($row['id']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($row['email']) ?></p>

        <h5 class="mt-3">Bank Details</h5>
        <p><b>Full Name:</b> <?= htmlspecialchars($row['full_name']) ?></p>
        <p><b>Bank Name:</b> <?= htmlspecialchars($row['bank_name']) ?></p>
        <p><b>Account Number:</b> <?= htmlspecialchars($row['account_number']) ?></p>
        <p><b>IFSC Code:</b> <?= htmlspecialchars($row['ifsc_code']) ?></p>
        <p><b>Account Holder:</b> <?= htmlspecialchars($row['account_holder']) ?></p>

        <h5 class="mt-3">Photo</h5>
        <?php if (!empty($row['photo'])): ?>
            <img src="../<?= htmlspecialchars($row['photo']) ?>" alt="User Photo" width="150" class="rounded border mb-3">
        <?php else: ?>
            <p class="text-muted">No Photo Uploaded</p>
        <?php endif; ?>

        <h5 class="mt-3">ID Proof</h5>
        <p><b>Type:</b> <?= htmlspecialchars($row['id_proof_type']) ?></p>
        <?php if (!empty($row['id_proof_file'])): ?>
            <a href="../<?= htmlspecialchars($row['id_proof_file']) ?>" target="_blank">
                <img src="../<?= htmlspecialchars($row['id_proof_file']) ?>" alt="ID Proof" width="200" class="border">
            </a>
        <?php else: ?>
            <p class="text-muted">No ID Proof Uploaded</p>
        <?php endif; ?>

        <h5 class="mt-3">Status</h5>
        <?php 
            $badgeClass = 'warning';
            if ($row['status'] == 'Approved') $badgeClass = 'success';
            elseif ($row['status'] == 'Rejected') $badgeClass = 'danger';
        ?>
        <p><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($row['status']) ?></span></p>

        <h5 class="mt-3">Submitted At</h5>
        <p><?= date("d M Y H:i", strtotime($row['created_at'])) ?></p>

        <div class="mt-4">
            <a href="update_kyc.php?id=<?= $row['id'] ?>&status=Approved" class="btn btn-success">Approve</a>
            <a href="update_kyc.php?id=<?= $row['id'] ?>&status=Rejected" class="btn btn-danger">Reject</a>
            <a href="update_kyc.php?id=<?= $row['id'] ?>&status=Pending" class="btn btn-secondary">Reset</a>
            <a href="manage_kyc.php" class="btn btn-dark">Back</a>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
