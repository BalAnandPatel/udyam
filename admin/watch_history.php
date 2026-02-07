<?php
//session_start();
require '../config/db.php';

// Ensure admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Default date = today
$selected_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
//echo "$selected_date";
// Fetch users who watched videos on selected date
$sqlWatched = "SELECT u.id as user_id, u.name, v.title, w.watched_at 
FROM watch_history w
LEFT JOIN users u ON w.user_id = u.id
LEFT JOIN videos v ON w.video_id = v.id
WHERE DATE(w.watched_at) = '$selected_date'
ORDER BY u.name, w.watched_at DESC;";


// "SELECT u.id as user_id, u.name, v.title, w.watched_at 
//               FROM watch_history w
//               JOIN users u ON w.user_id = u.id
//               JOIN videos v ON w.video_id = v.id
//               WHERE DATE(w.watched_at) = '$selected_date'
//               ORDER BY u.name, w.watched_at DESC";
$resWatched = $conn->query($sqlWatched);

// Fetch users who did NOT watch videos on selected date
//echo "@@@@@@@@@@@@";
$sqlPending = "SELECT u.id, u.name 
               FROM users u
               WHERE u.id NOT IN (
                    SELECT user_id FROM watch_history WHERE DATE(watched_at) = '$selected_date'
               )
               ORDER BY u.name ASC";
$resPending = $conn->query($sqlPending);

include 'includes/header.php';

?>

<div class="container mt-4">
    <h3>🎥 User Watch History (Admin)</h3>

    <!-- Date Filter -->
    <form method="get" class="mb-3">
        <label for="date" class="form-label">Choose Date:</label>
        <input type="date" id="date" name="date" value="<?= $selected_date ?>" class="form-control" style="max-width:200px;display:inline-block;">
        <button type="submit" class="btn btn-primary">View</button>
    </form>

    <hr>

    <!-- Watched Users -->
    <h4>✅ Watched Videos (<?= $selected_date ?>)</h4>
    <?php if ($resWatched && $resWatched->num_rows > 0): ?>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>User</th>
                    <!--<th>Video Title</th>-->
                    <th>Watched At</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resWatched->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?> (ID: <?= $row['user_id'] ?>)</td>
                    <!--<td><//?= htmlspecialchars($row['title']) ?></td>-->
                    <td><?= $row['watched_at'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No users watched any video on this date.</p>
    <?php endif; ?>

    <hr>

    <!-- Pending Users -->
    <h4>⏳ Pending Users (<?= $selected_date ?>)</h4>
    <?php if ($resPending && $resPending->num_rows > 0): ?>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resPending->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?> (ID: <?= $row['id'] ?>)</td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">All users watched at least one video on this date 🎉</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
