<?php
include __DIR__ . "/config/db.php";
//session_start();

// ✅ Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user info
$sqlUser = "SELECT name, wallet_balance FROM users WHERE id = $user_id";
$resultUser = mysqli_query($conn, $sqlUser);
$user = mysqli_fetch_assoc($resultUser);

// ✅ Fetch referrals
$sqlRef = "SELECT * FROM users WHERE referrer_id = $user_id ORDER BY created_at DESC";
$resultRef = mysqli_query($conn, $sqlRef);
$totalReferrals = mysqli_num_rows($resultRef);

// ✅ Total referral earnings (from earnings table)
$sqlRefEarnings = "SELECT SUM(amount) as total FROM earnings WHERE user_id = $user_id AND type='referral'";
$resultRefEarnings = mysqli_query($conn, $sqlRefEarnings);
$refEarnings = mysqli_fetch_assoc($resultRefEarnings)['total'];
$refEarnings = $refEarnings ? number_format($refEarnings, 2) : "0.00";
?>

<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h4>🔗 Your Referral Link</h4>

                <?php $refLink = "https://" . $_SERVER['HTTP_HOST'] . "/register.php?ref=" . $user_id; ?>

                <div class="input-group mb-2">
                    <input type="text" id="refLink" class="form-control text-center" value="<?= $refLink ?>" readonly>
                    <button class="btn btn-outline-primary" type="button" onclick="copyReferral()">Copy</button>
                </div>

                <small class="text-muted">Share this link to earn referral bonuses</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Total Referrals: <span class="text-primary"><?= $totalReferrals ?></span></h5>
                <h5>Referral Earnings: <span class="text-success">₹<?= $refEarnings ?></span></h5>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3">👥 Referred Users</h4>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Wallet Balance (₹)</th>
                <th>Joined On</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(mysqli_num_rows($resultRef) > 0):
                $i = 1;
                while($row = mysqli_fetch_assoc($resultRef)):
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= number_format($row['wallet_balance'],2) ?></td>
                <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
            </tr>
            <?php
                $i++;
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="5" class="text-center">No referrals yet.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ✅ Bootstrap Toast for copy success -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        ✅ Referral link copied!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
function copyReferral() {
    var copyText = document.getElementById("refLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // mobile support
    navigator.clipboard.writeText(copyText.value).then(function() {
        var toast = new bootstrap.Toast(document.getElementById('copyToast'));
        toast.show();
    }).catch(function(err) {
        alert("❌ Failed to copy: " + err);
    });
}
</script>

<?php include 'includes/footer.php'; ?>
