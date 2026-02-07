<?php
require 'config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<span class='text-danger'>Invalid ID</span>";
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT name FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<span class='text-danger'>No sponsor found!</span>";
} else {
    $stmt->bind_result($name);
    $stmt->fetch();
    echo "<span class='text-success fw-bold'>Sponsor: " . htmlspecialchars($name) . "</span>";
}

$stmt->close();
$conn->close();
