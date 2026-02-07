<?php
include 'includes/header.php';

// ✅ Config
$joining_amount = 1270; // fixed joining package amount

// ✅ Handle video upload
$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $earn_percent = floatval($_POST['earn_percent']); // percentage
    $embed_url = mysqli_real_escape_string($conn, $_POST['embed_url']);
    $file_path = '';

    // ✅ Calculate amount directly here
    $earn_amount = ($joining_amount * $earn_percent) / 100;

    // Handle file upload
    if (!empty($_FILES['video_file']['name'])) {
        $targetDir = '../public/uploads/';
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $file_name = time().'_'.basename($_FILES['video_file']['name']);
        $targetFile = $targetDir . $file_name;

        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFile)) {
            $file_path = $file_name;
        } else {
            $err = "⚠️ Failed to upload video file.";
        }
    }

    // Insert into database
    if (!$err) {
        // ✅ Save both percent and amount
        $sql = "INSERT INTO videos (title, description, file_path, embed_url, earn_percent, earn_amount, created_by) 
                VALUES ('$title', '$description', '$file_path', '$embed_url', $earn_percent, $earn_amount, {$_SESSION['admin_id']})";
        if (mysqli_query($conn, $sql)) {
            $success = "✅ Video uploaded successfully!";
        } else {
            $err = "⚠️ Error: " . mysqli_error($conn);
        }
    }
}

// ✅ Fetch all videos
$sqlVideos = "SELECT * FROM videos ORDER BY created_at DESC";
$resultVideos = mysqli_query($conn, $sqlVideos);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h3>Upload New Video</h3>
        <?php if($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Earning Percentage (%)</label>
                <input type="number" step="0.01" name="earn_percent" class="form-control" value="1.00" required>
                <div class="form-text">Example: Enter 2 for 2% of ₹1270 (₹25.40)</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Embed URL (optional)</label>
                <input type="url" name="embed_url" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Video File (optional)</label>
                <input type="file" name="video_file" accept="video/*" class="form-control">
            </div>
            <button class="btn btn-success">Upload Video</button>
        </form>
    </div>
</div>

<h3>All Uploaded Videos</h3>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Earning %</th>
                <th>Calculated Amount (₹)</th>
                <th>File / Embed</th>
                <th>Uploaded At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(mysqli_num_rows($resultVideos) > 0):
                $i = 1;
                while($video = mysqli_fetch_assoc($resultVideos)):

                    // ✅ Always take amount from DB
                    $earnPercent = isset($video['earn_percent']) ? (float)$video['earn_percent'] : 0;
                    $earnAmount  = isset($video['earn_amount']) ? (float)$video['earn_amount'] : ($joining_amount * $earnPercent / 100);
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($video['title']) ?></td>
                <td><?= htmlspecialchars($video['description']) ?></td>
                <td><?= $earnPercent ?>%</td>
                <td>₹<?= number_format($earnAmount,2) ?></td>
                <td>
                    <?php
                    if($video['embed_url']) echo htmlspecialchars($video['embed_url']);
                    else if($video['file_path']) echo htmlspecialchars($video['file_path']);
                    else echo 'N/A';
                    ?>
                </td>
                <td><?= date("d M Y H:i", strtotime($video['created_at'])) ?></td>
                <td>
                    <a href="delete_video.php?id=<?= (int)$video['id'] ?>" 
                       onclick="return confirm('Are you sure you want to delete this video?')" 
                       class="btn btn-sm btn-danger">🗑 Delete</a>
                </td>
            </tr>
            <?php
                $i++;
                endwhile;
            else:
            ?>
            <tr><td colspan="8" class="text-center">No videos uploaded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include 'includes/footer.php';
?>
