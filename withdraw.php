<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// =============================
// Calculate totals
// =============================
$sql = "SELECT 
            IFNULL((SELECT SUM(amount) FROM earnings WHERE user_id=$user_id),0) AS total_earnings,
            IFNULL((SELECT SUM(balance) FROM wallet WHERE user_id=$user_id),0) AS total_wallet,
            IFNULL((SELECT SUM(amount) FROM withdrawals WHERE user_id=$user_id AND status='approved'),0) AS total_withdrawn
        FROM DUAL";

$res = $conn->query($sql) or die("SQL Error: " . $conn->error);
$row = $res->fetch_assoc();

$total_earnings    = (float)$row['total_earnings'];
$total_wallet      = (float)$row['total_wallet'];
$total_withdrawn   = (float)$row['total_withdrawn'];
$available_balance = ($total_earnings - $total_withdrawn);

// =============================
// Handle withdrawal request (POST)
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (float) $_POST['amount'];

    // ✅ NEW: Check direct referrals
    $refCheck = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE sponsor_id=$user_id");
    $refRow   = $refCheck->fetch_assoc();
    $direct_refs = (int)$refRow['cnt'];

    if ($direct_refs < 2) {
        $_SESSION['flash_message'] = "<div class='alert alert-warning'>⚠ You need at least 2 direct referrals before applying for withdrawal.</div>";
        header("Location: withdraw.php");
        exit;
    }

    if ($amount > 0 && $amount <= $available_balance) {
        // Charges
        $admin_charge = round($amount * 0.10, 2); // 10%
        $tds          = round($amount * 0.05, 2); // 5%
        $net_amount   = round($amount - $admin_charge - $tds, 2);

        $sqlInsert = "INSERT INTO withdrawals 
            (user_id, amount, admin_charge, tds, net_amount, status, created_at) 
            VALUES (?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $conn->prepare($sqlInsert);

        if (!$stmt) {
            $_SESSION['flash_message'] = "<div class='alert alert-danger'>❌ Prepare failed: " . htmlspecialchars($conn->error) . "</div>";
            header("Location: withdraw.php"); exit;
        }

        $stmt->bind_param("idddd", $user_id, $amount, $admin_charge, $tds, $net_amount);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "<div class='alert alert-success'>✅ Withdrawal request submitted successfully!</div>";
        } else {
            $_SESSION['flash_message'] = "<div class='alert alert-danger'>❌ Execute failed: " . htmlspecialchars($stmt->error) . "</div>";
        }
        $stmt->close();
    } else {
        $_SESSION['flash_message'] = "<div class='alert alert-warning'>⚠ Invalid amount or insufficient balance.</div>";
    }

    header("Location: withdraw.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <h3>💵 Withdraw Funds</h3>

    <!-- Flash Message -->
    <?php 
    if (isset($_SESSION['flash_message'])) {
        echo $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    }
    ?>

    <!-- Summary Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Total Earnings</h6>
                    <h4>₹<?= number_format($total_earnings, 2) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Total Wallet</h6>
                    <h4>₹<?= number_format($total_wallet, 2) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6>Total Withdrawn</h6>
                    <h4>₹<?= number_format($total_withdrawn, 2) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h6>Available Balance</h6>
                    <h4 class="text-success">₹<?= number_format($available_balance, 2) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Form -->
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="amount" class="form-label">Enter Amount (₹)</label>
            <input type="number" step="0.01" min="1" class="form-control" id="amount" name="amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Request Withdrawal</button>
    </form>

    <hr>
    <h4>Your Withdrawal Requests</h4>
    <div id="withdrawals-container"></div>
    <div class="text-center mt-2">
        <button id="toggle-btn" class="btn btn-info">View All</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    let showAll = false;

    function loadWithdrawals() {
        $.get("fetch_withdrawals.php", { all: showAll ? 1 : 0 }, function (data) {
            $("#withdrawals-container").html(data);
            $("#toggle-btn").text(showAll ? "Hide" : "View All");
        });
    }
    loadWithdrawals();

    $("#toggle-btn").on("click", function(){
        showAll = !showAll;
        loadWithdrawals();
    });
});
</script>

<?php include 'includes/footer.php'; ?>
