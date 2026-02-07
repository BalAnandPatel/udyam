<?php
include 'includes/header.php';

if (!isset($_GET['user_id'])) {
    die("Invalid request");
}

$user_id = (int)$_GET['user_id'];

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total rows
$count_sql = "SELECT COUNT(*) AS total FROM users WHERE sponsor_id = $user_id";
$count_res = mysqli_query($conn, $count_sql);
$total_rows = mysqli_fetch_assoc($count_res)['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch direct members with pagination
$sql = "
    SELECT id, name, email, created_at
    FROM users
    WHERE sponsor_id = $user_id
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
    <h3>👥 Direct Members of User ID: <?= $user_id ?></h3>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>S.No</th>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Joined On</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $sn = $offset + 1; // serial number based on page
    while ($row = mysqli_fetch_assoc($result)): 
    ?>
        <tr>
            <td><?= $sn++; ?></td>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Pagination (Previous / Next Only) -->
<nav>
    <ul class="pagination">

        <!-- Previous -->
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?user_id=<?= $user_id ?>&page=<?= $page - 1 ?>">Previous</a>
        </li>

        <!-- Next -->
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?user_id=<?= $user_id ?>&page=<?= $page + 1 ?>">Next</a>
        </li>

    </ul>
</nav>

<a href="founder_club.php" class="btn btn-secondary mt-3">⬅ Back</a>
</div>

<?php include 'includes/footer.php'; ?>
