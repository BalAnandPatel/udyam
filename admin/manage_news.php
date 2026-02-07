<?php
include "../config/db.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

$msg = "";

// Protect admin session if needed (optional, keep if using session protection in header)
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: index.php");
//     exit;
// }

// ✅ Delete News
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM news WHERE id=$id")) {
        $msg = "<div class='alert alert-success'>🗑️ News deleted successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: ".$conn->error."</div>";
    }
}

// ✅ Add New News
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO news (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ News added successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="container mt-4">
    <h2 class="mb-3">📰 Manage News</h2>
    <?php echo $msg; ?>

    <!-- Add News Form -->
    <form method="post" class="card p-4 shadow-sm mb-4" style="max-width:700px;">
        <div class="mb-3">
            <label class="form-label fw-bold">News Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter news title" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Content</label>
            <textarea name="content" class="form-control" rows="4" placeholder="Write news content here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publish News</button>
    </form>

    <!-- Search News -->
    <form method="GET" class="mb-4" style="max-width:400px;">
        <div class="input-group">
            <?php
            // Replace null coalescing with ternary to support older PHP
            $search_value = isset($_GET['search']) ? $_GET['search'] : '';
            ?>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_value); ?>" class="form-control" placeholder="Search news...">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- News List -->
    <h3>📋 Existing News</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Use the sanitized $search_value in query (note: for full safety use prepared statements)
            $search_escaped = $conn->real_escape_string($search_value);
            $result = $conn->query("SELECT * FROM news WHERE title LIKE '%$search_escaped%' OR content LIKE '%$search_escaped%' ORDER BY created_at DESC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>". $row['id'] ."</td>";
                    echo "<td>". htmlspecialchars($row['title']) ."</td>";
                    echo "<td>". htmlspecialchars(mb_substr($row['content'], 0, 70)) ."...</td>";
                    echo "<td>". date("d M Y, h:i A", strtotime($row['created_at'])) ."</td>";
                    echo "<td>
                            <a href='edit_news.php?id=". $row['id'] ."' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='manage_news.php?delete=". $row['id'] ."' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this news?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center text-muted'>No news found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "includes/footer.php"; ?>
