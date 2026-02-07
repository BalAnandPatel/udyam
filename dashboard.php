<?php
// Show errors in development
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'config/db.php';

// Ensure DB connection
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Database connection not found. Please check config/db.php (expected \$conn).");
}

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ---------------------------
   1) Total Direct Members
--------------------------- */
$total_directs = 0;
$sql = "SELECT COUNT(*) AS total_directs FROM users WHERE sponsor_id = $user_id";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    $total_directs = (int)$row['total_directs'];
}

/* ---------------------------
   2) Total Team (BFS up to 12 levels)
--------------------------- */
$total_team = 0;
$max_level = 12;
$queue = [$user_id];
$current_level = 0;

while (!empty($queue) && $current_level < $max_level) {
    $next = [];
    foreach ($queue as $s) {
        $sql = "SELECT id FROM users WHERE sponsor_id = " . (int)$s;
        $res = $conn->query($sql);
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $total_team++;
                $next[] = (int)$r['id'];
            }
        }
    }
    $queue = $next;
    $current_level++;
}

/* ---------------------------
   3) Earnings, Wallet, Withdrawals
--------------------------- */
$total_earnings = 0.00;
$wallet_adjustments = 0.00;
$total_withdraw = 0.00;

// Earnings
$sql = "SELECT IFNULL(SUM(amount),0) AS total_earnings FROM earnings WHERE user_id = $user_id";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    $total_earnings = floatval($row['total_earnings']);
}

// Wallet Adjustments
$sql = "SELECT IFNULL(SUM(balance),0) AS wallet_adjustments FROM wallet WHERE user_id = $user_id";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    $wallet_adjustments = floatval($row['wallet_adjustments']);
}

// Withdrawals
$sql = "SELECT IFNULL(SUM(amount),0) AS total_withdraw FROM withdrawals WHERE user_id = $user_id";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    $total_withdraw = floatval($row['total_withdraw']);
}

// ✅ Final Wallet Balance
$total_wallet = ($total_earnings + $wallet_adjustments) - $total_withdraw;

/* ---------------------------
   4) Income Breakup
--------------------------- */
$referral_income = 0.00;
$level_income = 0.00;
$video_income = 0.00;

// Referral
$sql = "SELECT IFNULL(SUM(amount),0) AS total FROM earnings WHERE user_id = $user_id AND type='referral'";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) $referral_income = floatval($row['total']);

// Level
$sql = "SELECT IFNULL(SUM(amount),0) AS total FROM earnings WHERE user_id = $user_id AND type='level'";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) $level_income = floatval($row['total']);

// Video

$sql = "SELECT IFNULL(SUM(amount),0) AS total FROM earnings WHERE user_id = $user_id AND type='watch'";
//$sql = "SELECT IFNULL(SUM(amount),0) AS total FROM watch_history WHERE user_id = $user_id";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) $video_income = floatval($row['total']);

// Grand Total
$grand_income = $referral_income + $level_income + $video_income;

/* ---------------------------
   5) Latest Reward (for marquee)
--------------------------- */
$latestReward = null;
$sql = "SELECT title, description, reward_amount, created_at 
        FROM rewards ORDER BY created_at DESC LIMIT 1";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    $latestReward = $res->fetch_assoc();
}

include 'includes/header.php';
?>

<!-- ✅ Marquee for Latest Reward -->
<?php if ($latestReward): ?>
<div class="bg-warning text-dark py-2">
    <marquee behavior="scroll" direction="left" scrollamount="6" 
             onmouseover="this.stop();" onmouseout="this.start();">
        🎁 <strong>Latest Reward:</strong> <?= htmlspecialchars($latestReward['title']) ?> - 
        <?= htmlspecialchars($latestReward['description']) ?> 
        (₹<?= number_format($latestReward['reward_amount'], 2) ?>) 
        | Added on: <?= date("d M Y", strtotime($latestReward['created_at'])) ?>
    </marquee>
</div>
<?php endif; ?>

<div class="container mt-4">
    <h3 class="mb-4">User Dashboard</h3>

    <!-- ✅ New Income Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 h-100">
                <h5>👤 Referral Income</h5>
                <h3>₹<?= number_format($referral_income, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 h-100">
                <h5>📊 Level Income</h5>
                <h3>₹<?= number_format($level_income, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 h-100">
                <h5>🎥 Video Income</h5>
                <h3>₹<?= number_format($video_income, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 h-100">
                <h5>💵 Total Income</h5>
                <h3>₹<?= number_format($grand_income, 2) ?></h3>
            </div>
        </div>
    </div>

    <!-- ✅ Old Summary Cards -->
    <div class="row g-4">
        <div class="col-md-3">
            <a href="team_list.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-3 h-100">
                    <h5>👥 Total Team</h5>
                    <h3><?= $total_team ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="wallet_details.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-3 h-100">
                    <h5>💰 Wallet Balance</h5>
                    <h3>₹<?= number_format($total_wallet, 2) ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="direct_list.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-3 h-100">
                    <h5>👤 Direct Members</h5>
                    <h3><?= $total_directs ?></h3>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="withdraw_history.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-3 h-100">
                    <h5>🏦 Withdrawn</h5>
                    <h3>₹<?= number_format($total_withdraw, 2) ?></h3>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- ✅ Next Video to Watch -->
<div class="container mt-5">
    <h4 class="mb-3">📺 Your Next Video</h4>
    <div class="row">
        <?php
        $sql = "
            SELECT v.id, v.title, v.description, v.embed_url, v.file_path, v.earn_percent, v.earn_amount,
                   v.created_at,
                   TIMESTAMPDIFF(SECOND, NOW(), v.created_at + INTERVAL 24 HOUR) AS seconds_left
            FROM videos v
            WHERE v.status = 'active'
              AND NOW() < v.created_at + INTERVAL 24 HOUR
              AND v.id NOT IN (
                  SELECT video_id FROM watch_history WHERE user_id = $user_id
              )
            ORDER BY v.id DESC
            LIMIT 1
        ";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            $video = $res->fetch_assoc();
            $secondsLeft = (int)$video['seconds_left'];
            ?>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="ratio ratio-16x9">
                        <?php
                        if (!empty($video['embed_url'])) {
                            $embed_url = str_replace("watch?v=", "embed/", $video['embed_url']);
                            echo "<iframe src='" . htmlspecialchars($embed_url) . "' frameborder='0' allowfullscreen></iframe>";
                        } elseif (!empty($video['file_path']) && file_exists('uploads/' . $video['file_path'])) {
                            echo "<video controls><source src='uploads/" . htmlspecialchars($video['file_path']) . "' type='video/mp4'></video>";
                        } else {
                            echo "<div class='alert alert-danger m-2'>No preview available</div>";
                        }
                        ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($video['title']) ?></h5>
                        <p class="card-text small text-muted"><?= htmlspecialchars(substr($video['description'], 0, 80)) ?>...</p>
                        <p class="text-info"><strong>Earning %:</strong> <?= number_format($video['earn_percent'], 2) ?>%</p>
                        <p class="text-success"><strong>Reward Amount:</strong> ₹<?= number_format($video['earn_amount'], 2) ?></p>
                        <p class="text-danger countdown" data-seconds="<?= $secondsLeft ?>"></p>
                        <a href="watch_video.php?id=<?= (int)$video['id'] ?>" class="btn btn-primary">▶ Watch & Earn</a>
                    </div>
                </div>
            </div>
            <script>
            document.querySelectorAll(".countdown").forEach(function(el){
                let sec = parseInt(el.dataset.seconds);
                function tick(){
                    if(sec <= 0){
                        el.textContent = "❌ Expired";
                        return;
                    }
                    let h = Math.floor(sec / 3600);
                    let m = Math.floor((sec % 3600) / 60);
                    let s = sec % 60;
                    el.textContent = "⏳ Time left: " + h + "h " + m + "m " + s + "s";
                    sec--;
                    setTimeout(tick, 1000);
                }
                tick();
            });
            </script>
            <?php
        } else {
            echo "<div class='col-12'><div class='alert alert-info'>🎉 You have watched all available videos or all videos have expired!</div></div>";
        }
        ?>
    </div>
</div>

<!-- ✅ Payment Collection Details Section -->
<div class="container mt-5">
    <h4 class="mb-3">🏦 Payment Collection Details</h4>
    <div class="row">
        <?php
        $sql = "SELECT * FROM payment_details ORDER BY id DESC LIMIT 5";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-4'>";
                echo "<div class='card shadow-sm p-3 mb-4'>";
                echo "<strong>Method:</strong> " . htmlspecialchars($row['method_type']) . "<br>";

                if ($row['method_type'] == "BANK") {
                    echo "Bank: " . htmlspecialchars($row['bank_name']) . "<br>";
                    echo "A/C No: " . htmlspecialchars($row['account_number']) . "<br>";
                    echo "IFSC: " . htmlspecialchars($row['ifsc_code']) . "<br>";
                    echo "Holder: " . htmlspecialchars($row['account_holder']) . "<br>";
                }
                if ($row['method_type'] == "UPI") {
                    echo "UPI ID: " . htmlspecialchars($row['upi_id']) . "<br>";
                }
                if ($row['method_type'] == "QR" && $row['qr_image']) {
                    // Base URL of your project
$baseUrl = '';

// Clean the image path coming from database
$qrImage = $row['qr_image'];

// Remove any ../ or leading slashes (VERY IMPORTANT)
$qrImage = str_replace('../', '', $qrImage);
$qrImage = ltrim($qrImage, '/');

// Output image
echo "<img src='{$baseUrl}{$qrImage}' class='img-fluid' alt='QR Code'>";

                }
                if ($row['method_type'] == "WALLET") {
                    $walletId = "wallet-".$row['id'];
                    echo "Wallet Address: <span id='".$walletId."'>" . htmlspecialchars($row['wallet_address']) . "</span><br>";
                    echo "<button class='btn btn-sm btn-outline-primary mt-2' onclick=\"copyWallet('".$walletId."')\">Copy</button>";
                }
                echo "</div></div>";
            }
        } else {
            echo "<div class='col-12'><div class='alert alert-info'>No payment details available.</div></div>";
        }
        ?>
    </div>
</div>

<script>
function copyWallet(elementId) {
    var text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert("✅ Wallet address copied: " + text);
    }).catch(function(err) {
        alert("❌ Failed to copy: " + err);
    });
}
</script>

<!-- ✅ Rewards Section -->
<div class="container mt-5">
    <h4 class="mb-3">🎁 Latest Rewards</h4>
    <div class="row">
        <?php
        $sql = "SELECT * FROM rewards ORDER BY created_at DESC LIMIT 5";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($reward = $result->fetch_assoc()) {
                echo "<div class='col-md-4'>";
                echo "<div class='card shadow-sm p-3 mb-4 h-100'>";
                echo "<h5>".htmlspecialchars($reward['title'])."</h5>";
                echo "<p>".htmlspecialchars($reward['description'])."</p>";
                echo "<p><strong>Reward:</strong> ₹".number_format($reward['reward_amount'],2)."</p>";
                echo "<p class='text-muted small'>Added on: ".date('d M Y', strtotime($reward['created_at']))."</p>";
                echo "</div></div>";
            }
        } else {
            echo "<div class='col-12'><div class='alert alert-info'>No rewards available right now.</div></div>";
        }
        ?>
    </div>
    <a href="user_rewards.php" class="btn btn-primary">View All Rewards</a>
</div>

<?php include 'includes/footer.php'; ?>
