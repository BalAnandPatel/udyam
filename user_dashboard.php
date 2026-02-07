<?php
// Turn on errors during development (remove / disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'config/db.php';

// Ensure we have the expected mysqli connection variable ($conn).
if (!isset($conn) || !($conn instanceof mysqli)) {
    // try common alternate names
    if (isset($mysqli) && ($mysqli instanceof mysqli)) {
        $conn = $mysqli;
    } elseif (isset($db) && ($db instanceof mysqli)) {
        $conn = $db;
    } else {
        die("Database connection not found. Please check config/db.php (expected \$conn).");
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ---------------------------
   1) Total Direct Members
   --------------------------- */
$stmt = $conn->prepare("SELECT COUNT(*) AS total_directs FROM users WHERE sponsor_id = ?");
if (!$stmt) {
    die("Prepare failed (directs): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$total_directs = isset($row['total_directs']) ? (int)$row['total_directs'] : 0;

//$total_directs = (int) ($row['total_directs'] ?? 0);
$stmt->close();

/* ---------------------------
   2) Total Team (BFS up to 12 levels)
   Efficient: prepare once and reuse
   --------------------------- */
$total_team = 0;
$max_level = 12;

// Prepare ONE statement to fetch children by sponsor_id
$stmtTeam = $conn->prepare("SELECT id FROM users WHERE sponsor_id = ?");
if (!$stmtTeam) {
    die("Prepare failed (team): " . $conn->error);
}
// bind a variable by reference; we'll set $sponsor_id inside the loop
$sponsor_id = 0;
$stmtTeam->bind_param('i', $sponsor_id);

$queue = [$user_id];
$current_level = 0;

while (!empty($queue) && $current_level < $max_level) {
    $next = [];
    foreach ($queue as $s) {
        $sponsor_id = (int)$s;            // update referenced variable
        if (!$stmtTeam->execute()) {
            die("Execute failed (team): " . $stmtTeam->error);
        }
        $res = $stmtTeam->get_result();
        while ($r = $res->fetch_assoc()) {
            $total_team++;
            $next[] = (int)$r['id'];
        }
    }
    $queue = $next;
    $current_level++;
}
$stmtTeam->close();

/* ---------------------------
   3) Total Wallet Balance
   --------------------------- */
$stmt = $conn->prepare("SELECT balance FROM wallet WHERE user_id = ?");
if (!$stmt) {
    die("Prepare failed (wallet): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$total_wallet = $row ? floatval($row['balance']) : 0.00;
$stmt->close();

/* ---------------------------
   4) Total Withdraw Amount
   --------------------------- */
$stmt = $conn->prepare("SELECT IFNULL(SUM(amount),0) AS total_withdraw FROM withdrawals WHERE user_id = ?");
if (!$stmt) {
    die("Prepare failed (withdraw): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$total_withdraw = isset($row['total_directs']) ? (int)$row['total_directs'] : 0;

//$total_withdraw = floatval($row['total_withdraw'] ?? 0);
$stmt->close();

include 'includes/header.php';
?>

<div class="container mt-4">
    <h3 class="mb-4">User Dashboard</h3>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h5>👥 Total Team</h5>
                <h3><?= $total_team ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h5>💰 Wallet Balance</h5>
                <h3>₹<?= number_format($total_wallet, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h5>👤 Direct Members</h5>
                <h3><?= $total_directs ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3">
                <h5>🏦 Withdrawn</h5>
                <h3>₹<?= number_format($total_withdraw, 2) ?></h3>
            </div>
        </div>
    </div>
</div>
<!-- Next Video to Watch -->
<div class="container mt-5">
    <h4 class="mb-3">📺 Your Next Video</h4>
    <div class="row">
        <?php
        // Fetch the latest video not watched by this user
        $stmt = $conn->prepare("
            SELECT v.id, v.title, v.description, v.embed_url, v.file_path, v.earn_amount
            FROM videos v
            WHERE v.id NOT IN (
                SELECT video_id FROM watch_history WHERE user_id = ?
            )
            ORDER BY v.id DESC
            LIMIT 1
        ");
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $video = $res->fetch_assoc();

                // Fix YouTube URL for embedding
                $embed_url = "";
                if (!empty($video['embed_url'])) {
                    $embed_url = $video['embed_url'];
                    if (strpos($embed_url, "watch?v=") !== false) {
                        $embed_url = str_replace("watch?v=", "embed/", $embed_url);
                    }
                }
                ?>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="ratio ratio-16x9">
                            <?php if (!empty($embed_url)): ?>
                                <iframe src="<?= htmlspecialchars($embed_url) ?>" frameborder="0" allowfullscreen></iframe>
                            <?php elseif (!empty($video['file_path']) && file_exists('uploads/' . $video['file_path'])): ?>
                                <video controls>
                                    <source src="uploads/<?= htmlspecialchars($video['file_path']) ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <div class="alert alert-danger m-2">No preview available</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($video['title']) ?></h5>
                            <p class="card-text small text-muted"><?= htmlspecialchars(substr($video['description'], 0, 80)) ?>...</p>
                            <p class="text-success"><strong>Reward:</strong> ₹<?= number_format($video['earn_amount'], 2) ?></p>
                            <a href="watch_video.php?id=<?= (int)$video['id'] ?>" class="btn btn-primary">▶ Watch & Earn</a>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo "<div class='col-12'><div class='alert alert-info'>🎉 You have watched all available videos!</div></div>";
            }
            $stmt->close();
        } else {
            echo "<div class='col-12'><div class='alert alert-danger'>Error: " . $conn->error . "</div></div>";
        }
        ?>
    </div>
</div>


<?php include 'includes/footer.php'; ?>
