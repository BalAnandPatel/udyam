<?php
include '../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// ✅ Protect Admin Access
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$msg = "";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("<div class='alert alert-danger text-center mt-5'>Invalid News ID.</div>");
}

// ✅ Fetch existing record
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("<div class='alert alert-warning text-center mt-5'>No news found with this ID.</div>");
}
$news = $result->fetch_assoc();

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title)) {
        $update = $conn->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
        $update->bind_param("ssi", $title, $content, $id);
        if ($update->execute()) {
            $msg = "<div class='alert alert-success'>✅ News updated successfully!</div>";
            // Refresh the record after update
            $stmt->execute();
            $news = $stmt->get_result()->fetch_assoc();
        } else {
            $msg = "<div class='alert alert-danger'>❌ Error updating news: " . $conn->error . "</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>Please enter a title.</div>";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">📰 Edit News</h4>
    <a href="manage_news.php" class="btn btn-secondary btn-sm">← Back to News List</a>
  </div>

  <?= $msg ?>

  <form method="post" class="card shadow-sm p-4 border-0" style="max-width:800px; margin:auto;">
    <div class="mb-3">
      <label class="form-label fw-semibold">News Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($news['title']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">News Content</label>
      <textarea name="content" class="form-control" rows="6" placeholder="Enter detailed news content..."><?= htmlspecialchars($news['content']) ?></textarea>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary px-4">Update News</button>
    </div>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
