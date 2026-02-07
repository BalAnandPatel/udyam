<?php
include 'includes/header.php';

// Fetch Founder Club achievers
$sql = "
    SELECT u.id, u.name, u.email, COUNT(d.id) AS direct_count
    FROM users u
    JOIN users d ON d.sponsor_id = u.id
    GROUP BY u.id
    HAVING direct_count >= 64
    ORDER BY direct_count DESC
";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
    <h3>🏆 Founder Club Members</h3>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Direct Members</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td class="text-success"><strong><?= $row['direct_count'] ?></strong></td>
                <td>
                    <a href="founder_directs.php?user_id=<?= $row['id'] ?>"
                       class="btn btn-sm btn-primary">
                       View Direct Members
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
