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
$sqlUser = "SELECT wallet_balance FROM users WHERE id = $user_id";
$resultUser = mysqli_query($conn, $sqlUser);
$user = mysqli_fetch_assoc($resultUser);

$wallet = isset($user['wallet_balance']) ? $user['wallet_balance'] : 0;
//print_r($user);
// ✅ Fetch earnings history (last 20 entries)
$sqlEarnings = "SELECT * FROM earnings WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 20";
$resultEarnings = mysqli_query($conn, $sqlEarnings);

// ✅ Fetch grouped level incomes
$sqlLevels = "
    SELECT meta, SUM(amount) as total_amount, MAX(created_at) as last_date
    FROM earnings 
    WHERE user_id = $user_id AND type='level'
    GROUP BY meta
    ORDER BY last_date ASC
";
$resLevels = mysqli_query($conn, $sqlLevels);
$level_incomes = [];
while ($row = mysqli_fetch_assoc($resLevels)) {
    $level_incomes[] = $row;
}

// ✅ Helper function to count members at each level
function getLevelMemberCount($userId, $level, $conn) {
    $currentLevel = [$userId];
    $count = 0;
    for ($i = 1; $i <= $level; $i++) {
        $nextLevel = [];
        foreach ($currentLevel as $uid) {
            $res = $conn->query("SELECT id FROM users WHERE parent_id='$uid'");
            while ($row = $res->fetch_assoc()) {
                $nextLevel[] = $row['id'];
                if ($i == $level) {
                    $count++;
                }
            }
        }
        $currentLevel = $nextLevel;
    }
    return $count;
}
?>

<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h4>💰 Wallet Balance</h4>
                <h2 class="text-success">₹<?= number_format($wallet,2) ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Level Income Summary -->
<h4 class="mb-3">📊 Level Income Summary</h4>
<div class="table-responsive mb-4">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Level</th>
                <th>Required Members</th>
                <th>Current Members</th>
                <th>Progress</th>
                <th>Total Earned (₹)</th>
                <th>Status</th>
                <th>Last Payout</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        for ($level=1; $level<=12; $level++): 
            $expected = pow(2,$level);
            $actual   = getLevelMemberCount($user_id,$level,$conn);
            $earned   = 0;
            $lastDate = "-";

            foreach ($level_incomes as $inc) {
                if (strpos($inc['meta'],"Level $level income") !== false) {
                    $earned = $inc['total_amount'];
                    $lastDate = $inc['last_date'];
                }
            }

            $status = ($earned > 0) 
                ? "<span class='badge bg-success'>Completed</span>" 
                : "<span class='badge bg-warning text-dark'>In Progress</span>";

            $progressPercent = min(100, round(($actual / $expected) * 100));
        ?>
            <tr>
                <td><?= $level ?></td>
                <td><?= $expected ?></td>
                <td><?= $actual ?></td>
                <td style="width:220px;">
                    <div class="progress" style="height:20px;">
                        <div class="progress-bar <?= ($progressPercent==100?'bg-success':'bg-info') ?>" 
                             role="progressbar" 
                             style="width: <?= $progressPercent ?>%;" 
                             aria-valuenow="<?= $progressPercent ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?= $progressPercent ?>%
                        </div>
                    </div>
                </td>
                <td>₹<?= number_format($earned,2) ?></td>
                <td><?= $status ?></td>
                <td><?= ($lastDate != "-") ? date("d M Y H:i", strtotime($lastDate)) : "-" ?></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
</div>

<!-- ✅ Recent Earnings -->
<h4 class="mb-3">📝 Recent Earnings</h4>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Amount (₹)</th>
                <th>Details</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($resultEarnings) > 0): 
                $i = 1;
                while($row = mysqli_fetch_assoc($resultEarnings)): ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= ucfirst($row['type']) ?></td>
                <td class="text-success">₹<?= number_format($row['amount'],2) ?></td>
                <td><?= htmlspecialchars($row['meta']) ?></td>
                <td><?= date("d M Y H:i", strtotime($row['created_at'])) ?></td>
            </tr>
            <?php 
                $i++;
                endwhile;
            else: ?>
            <tr>
                <td colspan="5" class="text-center">No earnings yet.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
