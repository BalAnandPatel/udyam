<?php
include "../config/db.php";

$msg = "";

// Handle insert
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "../uploads/products/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // create folder if not exists
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image = $fileName;
            } else {
                $msg = "<div class='alert alert-danger'>❌ Failed to upload image.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>❌ Only JPG, JPEG, PNG, GIF files are allowed.</div>";
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?,?,?,?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);

    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Product added successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");

include "includes/header.php";
?>

<div class="container mt-4">
    <h2>Add Product</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm mb-4" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <button type="submit" name="add_product" class="btn btn-primary">Save Product</button>
    </form>

    <h3 class="mb-3">Product List</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price (₹)</th>
                    <th>Created At</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="../uploads/products/<?= htmlspecialchars($row['image']) ?>" alt="Product" style="width:60px; height:60px; object-fit:cover; border-radius:5px;">
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>₹<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No products added yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "includes/footer.php"; ?>
