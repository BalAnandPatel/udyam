<?php
require 'config/db.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No product ID']);
    exit;
}

$id = (int)$_GET['id'];
$q = $conn->prepare("SELECT image FROM products WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res = $q->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if (!empty($row['image'])) {
        $photoUrl = "uploads/products/" . htmlspecialchars($row['image']);
        echo json_encode(['success' => true, 'photo' => $photoUrl]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No image found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}

$q->close();
$conn->close();
