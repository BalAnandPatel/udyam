<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized");
}

$user_id = (int) $_SESSION['user_id'];

$sql = "SELECT id, amount, admin_charge, tds,renewal_charge, net_amount, status, created_at, approved_at 
        FROM withdrawals 
        WHERE user_id=$user_id 
        ORDER BY id DESC";

if (!isset($_GET['all']) || $_GET['all'] == 0) {
    $sql .= " LIMIT 3";
}

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    echo "<table class='table table-bordered table-striped table-sm'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Requested Amount</th>
                    <th>Admin Charge (10%)</th>
                    <th>TDS (5%)</th>
                    <th>Renewal Charge (5%)</th>
                    <th>Net Amount</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Approved At</th>
                </tr>
            </thead>
            <tbody>";
    while($row = $res->fetch_assoc()){
        echo "<tr>
                <td>{$row['id']}</td>
                <td>₹".number_format((float)$row['amount'],2)."</td>
                <td>₹".number_format((float)$row['admin_charge'],2)."</td>
                <td>₹".number_format((float)$row['tds'],2)."</td>
                <td>₹".number_format((float)$row['renewal_charge'],2)."</td>
                <td><b>₹".number_format((float)$row['net_amount'],2)."</b></td>
                <td>".ucfirst($row['status'])."</td>
                <td>".date("Y-m-d H:i:s", strtotime($row['created_at']))."</td>
                <td>".(!empty($row['approved_at']) ? date("Y-m-d H:i:s", strtotime($row['approved_at'])) : "-")."</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>No withdrawal requests found.</div>";
}
?>
