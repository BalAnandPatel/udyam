<?php
require 'config/db.php'; // $conn connection

echo "<h3>Cleaning Duplicate Level Incomes...</h3>";

// STEP 1: Delete duplicate level incomes
$sql = "
DELETE e1 FROM earnings e1
JOIN earnings e2
  ON e1.user_id = e2.user_id
 AND e1.type = 'level'
 AND e2.type = 'level'
 AND e1.meta = e2.meta
 AND e1.id > e2.id
";
if ($conn->query($sql)) {
    echo "✅ Duplicate level incomes removed.<br>";
} else {
    echo "❌ Error deleting duplicates: " . $conn->error . "<br>";
}

// STEP 2: Reset all wallet balances
$conn->query("UPDATE users SET wallet_balance = 0");

// STEP 3: Recalculate balances from earnings table
$sql = "SELECT user_id, SUM(amount) as total FROM earnings GROUP BY user_id";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $uid = $row['user_id'];
    $total = $row['total'];
    $conn->query("UPDATE users SET wallet_balance=$total WHERE id=$uid");
}

echo "✅ Wallet balances recalculated successfully.<br>";
echo "<hr>Cleanup Completed!";
?>
