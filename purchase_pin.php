<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity']);
    $payment_mode = trim($_POST['payment_mode']);
    $txn_id = trim($_POST['txn_id']);

    if ($quantity > 0 && !empty($payment_mode) && !empty($txn_id)) {
        $amount = $quantity * 1500; // user pays 1500 per pin
        $stmt = $conn->prepare("INSERT INTO voucher_purchase_requests (user_id, quantity, amount, payment_mode, txn_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidss", $user_id, $quantity, $amount, $payment_mode, $txn_id);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ PIN purchase request submitted successfully. Wait for admin approval.</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>⚠ Please fill all fields properly.</div>";
    }
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <h3>🛒 Purchase PINs</h3>
    <?= $message ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Number of PINs</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Payment Mode</label>
            <select name="payment_mode" class="form-control" required>
                <option value="">-- Select --</option>
                <option value="UPI">UPI</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Cash">Cash</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Transaction ID / Reference</label>
            <input type="text" name="txn_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>

    <hr>
    <h4>📜 My Purchase Requests</h4>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Mode</th>
            <th>Txn ID</th>
            <th>Status</th>
            <th>Requested At</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM voucher_purchase_requests WHERE user_id=$user_id ORDER BY id DESC");
        while ($row = $res->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['quantity']}</td>
                    <td>₹{$row['amount']}</td>
                    <td>{$row['payment_mode']}</td>
                    <td>{$row['txn_id']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['requested_at']}</td>
                </tr>";
        }
        ?>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
