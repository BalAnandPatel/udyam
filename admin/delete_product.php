<?php
include "../config/db.php";
//session_start();

if (!isset($_GET['id'])) {
    die("Invalid request");
}
$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: add_product.php?msg=Product deleted successfully");
    exit;
} else {
    echo "❌ Error: " . $stmt->error;
}
