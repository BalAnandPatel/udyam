<?php
require 'config/db.php';
$result = $conn->query("SELECT b.*, u.name FROM bills b LEFT JOIN users u ON b.user_id=u.id ORDER BY b.id DESC");
echo "<h3>All Generated Bills</h3><table border='1' cellpadding='8'><tr><th>ID</th><th>User</th><th>File</th><th>Date</th></tr>";
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td><a href='bills/{$row['file_name']}' target='_blank'>View PDF</a></td>
            <td>{$row['created_at']}</td>
          </tr>";
}
echo "</table>";
?>
