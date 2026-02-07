<?php
include "../config/db.php";
session_start();

$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");

include "includes/header.php";
?>

<div class="container mt-4">
    <h2>Manage Products</h2>
    <a href="add_product.php" class="btn btn-success mb-3">+ Add New Product</a>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price (₹)</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>₹<?= number_format($row['price'], 2) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="5" class="text-center">No products found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "includes/footer.php"; ?>
