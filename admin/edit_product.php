<?php
include "config/db.php";

$msg = "";

if (!isset($_GET['id'])) {
    die("Invalid request");
}
$id = (int)$_GET['id'];

// Fetch existing product
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$product) {
    die("Product not found!");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $old_image = $product['image'];

    $new_image = $old_image; // Default: keep old image

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "../uploads/products/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                // Delete old image if exists
                if (!empty($old_image) && file_exists($targetDir . $old_image)) {
                    unlink($targetDir . $old_image);
                }
                $new_image = $fileName;
            } else {
                $msg = "<div class='alert alert-danger'>❌ Failed to upload new image.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>❌ Only JPG, JPEG, PNG, GIF files are allowed.</div>";
        }
    }

    // Update database
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $new_image, $id);

    if ($stmt->execute()) {
        header("Location: add_product.php?msg=Product updated successfully");
        exit;
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
    }
}

include "includes/header.php";
?>

<div class="container mt-4">
    <h2>Edit Product</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if (!empty($product['image'])): ?>
                <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="Product" style="width:100px; height:100px; object-fit:cover; border-radius:5px;">
            <?php else: ?>
                <span class="text-muted">No image uploaded</span>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Change Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="add_product.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include "includes/footer.php"; ?>
