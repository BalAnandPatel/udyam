<?php
session_start();
require 'config/db.php';

// ensure $conn exists and is mysqli
if (!isset($conn) || !($conn instanceof mysqli)) {
    if (isset($mysqli) && ($mysqli instanceof mysqli)) $conn = $mysqli;
    elseif (isset($db) && ($db instanceof mysqli)) $conn = $db;
    else die("Database connection not found. Check config/db.php (expected \$conn).");
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = (int) $_SESSION['user_id'];

include 'includes/header.php';
?>
<div class="container mt-4">
    <h3>💰 Wallet Transactions</h3>

    <?php
    // Wallet balance (from wallet table)
    $wallet_balance = 0.00;
    $r = mysqli_query($conn, "SELECT balance FROM wallet WHERE user_id = {$user_id} LIMIT 1");
    if ($r && mysqli_num_rows($r) > 0) {
        $row = mysqli_fetch_assoc($r);
        $wallet_balance = (float)$row['balance'];
        mysqli_free_result($r);
    }
    ?>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card p-3">
                <h6>Current Wallet Balance</h6>
                <h4>₹<?= number_format($wallet_balance, 2) ?></h4>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3">
                <h6>Earnings Summary</h6>
                <?php
                // Total earned (all income types)
                $sqlTotal = "SELECT IFNULL(SUM(amount),0) AS total_earned FROM earnings WHERE user_id = {$user_id}";
                $rt = mysqli_query($conn, $sqlTotal);
                $total_earned = 0.00;
                if ($rt) {
                    $tr = mysqli_fetch_assoc($rt);
                    $total_earned = (float)$tr['total_earned'];
                    mysqli_free_result($rt);
                }

                // Breakdown by income_type
                $breakdown = [];
                $sqlBreak = "SELECT type, IFNULL(SUM(amount),0) AS total FROM earnings WHERE user_id = {$user_id} GROUP BY type";
                $rb = mysqli_query($conn, $sqlBreak);
                if ($rb) {
                    while ($br = mysqli_fetch_assoc($rb)) {
                        $breakdown[$br['type']] = (float)$br['total'];
                    }
                    mysqli_free_result($rb);
                }
                ?>
                <div class="mb-2">Total Earned: <strong>₹<?= number_format($total_earned, 2) ?></strong></div>
                <div>
                    <?php if (!empty($breakdown)): ?>
                        <ul class="mb-0">
                            <?php foreach ($breakdown as $type => $amt): ?>
                                <li><?= htmlspecialchars(ucfirst($type)) ?>: ₹<?= number_format($amt, 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <small class="text-muted">No earnings yet.</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed list -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Amount (₹)</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // list all earnings for this user (latest first)
                // Using column name income_type; if your table uses `type` change it here.
                $sql = "SELECT amount, type, created_at FROM earnings WHERE user_id = {$user_id} ORDER BY created_at DESC";
                $res = mysqli_query($conn, $sql);

                if ($res === false) {
                    echo "<tr><td colspan='4' class='text-danger'>Database query failed: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
                } else {
                    if (mysqli_num_rows($res) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($res)) {
                            $amount = number_format((float)$row['amount'], 2);
                            $type = htmlspecialchars($row['type']);
                            $date = date('d M Y H:i', strtotime($row['created_at']));
                            echo "<tr>
                                    <td>{$i}</td>
                                    <td>₹{$amount}</td>
                                    <td>{$type}</td>
                                    <td>{$date}</td>
                                  </tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No wallet transactions found.</td></tr>";
                    }
                    mysqli_free_result($res);
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
