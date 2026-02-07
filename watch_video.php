<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* =============================
   Handle reward when form submitted
   ============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_id'])) {
    $video_id = (int) $_POST['video_id'];

    // 1) Check if already rewarded
    $chk = $conn->prepare("SELECT id FROM watch_history WHERE user_id = ? AND video_id = ?");
    if (!$chk) { die("Prepare failed (watch_history check): " . htmlspecialchars($conn->error)); }
    $chk->bind_param('ii', $user_id, $video_id);
    $chk->execute();
    $chk->store_result();
    $already_rewarded_now = ($chk->num_rows > 0);
    $chk->close();

    if (!$already_rewarded_now) {
        // 2) Fetch reward amount
        $stmt = $conn->prepare("SELECT earn_amount FROM videos WHERE id = ?");
        if (!$stmt) { die("Prepare failed (fetch video): " . htmlspecialchars($conn->error)); }
        $stmt->bind_param('i', $video_id);
        $stmt->execute();
        $stmt->bind_result($earn_amount);
        $have_video = $stmt->fetch();
        $stmt->close();

        if ($have_video) {
            $reward = (float) $earn_amount;

            // 3) Insert into watch_history
            $ins1 = $conn->prepare("INSERT INTO watch_history (user_id, video_id, watched_at) VALUES (?, ?, NOW())");
            if (!$ins1) { die("Prepare failed (insert watch_history): " . htmlspecialchars($conn->error)); }
            $ins1->bind_param('ii', $user_id, $video_id);
            $ins1->execute();
            $ins1->close();

            // 4) Insert into earnings with description
            $desc = "Reward for watching video ID " . $video_id;
            $ins2 = $conn->prepare("INSERT INTO earnings (user_id, amount, type, meta, created_at) VALUES (?, ?, 'watch', ?, NOW())");
            if(!$ins2) { die("Prepare failed (insert earnings): " . htmlspecialchars($conn->error)); }
            $ins2->bind_param('ids', $user_id, $reward, $desc);
            $ins2->execute();
            $ins2->close();

            $_SESSION['flash_message'] = "<div class='alert alert-success'>🎉 Reward of ₹" . number_format($reward,2) . " credited successfully!</div>";
        }
    }

    header("Location: dashboard.php");
    exit;
}

/* =============================
   Show video (GET)
   ============================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}
$video_id = (int) $_GET['id'];

/* Fetch video details */
$stmt = $conn->prepare("SELECT id, title, description, earn_amount, embed_url, file_path FROM videos WHERE id = ?");
if (!$stmt) { die("Prepare failed (video load): " . htmlspecialchars($conn->error)); }
$stmt->bind_param('i', $video_id);
$stmt->execute();
$stmt->bind_result($v_id, $v_title, $v_desc, $v_earn, $v_embed, $v_file);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    echo "Video not found.";
    exit;
}

/* Check if already rewarded */
$chk = $conn->prepare("SELECT id FROM watch_history WHERE user_id = ? AND video_id = ?");
if (!$chk) { die("Prepare failed (already rewarded check): " . htmlspecialchars($conn->error)); }
$chk->bind_param('ii', $user_id, $video_id);
$chk->execute();
$chk->store_result();
$already_rewarded = ($chk->num_rows > 0);
$chk->close();

/* Build YouTube embed URL */
$embed_url = "";
if (!empty($v_embed)) {
    $embed_url = $v_embed;
    if (strpos($embed_url, "watch?v=") !== false) {
        $embed_url = str_replace("watch?v=", "embed/", $embed_url);
    }
    $embed_url .= (strpos($embed_url, "?") === false ? "?" : "&") . "rel=0&modestbranding=1&controls=1&enablejsapi=1";
}

include 'includes/header.php';
?>
<div class="container mt-4">
    <h4>Now Watching</h4>

    <div class="card p-3 mb-3">
        <div class="ratio ratio-16x9">
            <?php if (!empty($embed_url)): ?>
                <!-- YouTube -->
                <iframe 
                    id="ytplayer"
                    src="<?= htmlspecialchars($embed_url) ?>" 
                    title="YouTube video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            <?php elseif (!empty($v_file) && file_exists('uploads/' . $v_file)): ?>
                <!-- Local -->
                <video id="localVideo" controls width="100%">
                    <source src="uploads/<?= htmlspecialchars($v_file) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php else: ?>
                <div class="alert alert-danger">No video available to play.</div>
            <?php endif; ?>
        </div>

        <h5 class="mt-3"><?= htmlspecialchars($v_title) ?></h5>
        <p><?= nl2br(htmlspecialchars($v_desc)) ?></p>
        <p><strong>Reward:</strong> ₹<?= number_format((float)$v_earn, 2) ?></p>

        <?php if ($already_rewarded): ?>
            <div class="alert alert-success">✅ You have already been rewarded for this video.</div>
        <?php else: ?>
            <div id="rewardMessage" class="alert alert-info d-none">⏳ Please watch the video till the end to get reward.</div>
            <form id="rewardForm" method="post" action="" class="d-none">
                <input type="hidden" name="video_id" value="<?= (int)$video_id ?>">
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- YouTube API -->
<script src="https://www.youtube.com/iframe_api"></script>
<script>
let ytPlayer;
function onYouTubeIframeAPIReady() {
    const el = document.getElementById('ytplayer');
    if (!el) return;
    ytPlayer = new YT.Player('ytplayer', {
        events: {
            'onStateChange': function(event) {
                if (event.data === YT.PlayerState.ENDED) {
                    submitReward();
                }
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    const localVideo = document.getElementById("localVideo");
    if (localVideo) {
        localVideo.addEventListener("ended", function() {
            submitReward();
        });
    }
});

function submitReward() {
    const rewardForm = document.getElementById("rewardForm");
    const rewardMessage = document.getElementById("rewardMessage");
    if (rewardForm) {
        rewardMessage.classList.remove("d-none");
        rewardMessage.classList.remove("alert-info");
        rewardMessage.classList.add("alert-success");
        rewardMessage.innerHTML = "🎉 Congratulations! Reward is being credited...";
        setTimeout(function(){ rewardForm.submit(); }, 2000);
    }
}
</script>
