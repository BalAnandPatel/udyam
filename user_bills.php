<?php
session_start();
require 'config/db.php';
$user_id = $_SESSION['user_id']; // your logged-in user id

$result = $conn->query("SELECT * FROM bills WHERE user_id='$user_id' ORDER BY id DESC");
echo "<h3>Your Bills</h3><table border='1' cellpadding='8'><tr><th>ID</th><th>File</th><th>Date</th></tr>";
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td><a href='bills/{$row['file_name']}' target='_blank'>View PDF</a></td>
            <td>{$row['created_at']}</td>
          </tr>";
}
echo "</table>";
?>
