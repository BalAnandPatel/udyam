<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config/db.php'; // must define $conn (mysqli object)

if (!isset($_SESSION['temp_reg'])) {
    header("Location: register.php");
    exit;
}

$reg = $_SESSION['temp_reg'];
$user_id = (int)$reg['user_id'];
$name    = $reg['name'];
$password= $reg['password'];

/* ---------- Default bill values ---------- */
$total_amount = 1500.00;          // total payable
$transactional_charge = 1.40;     // fixed charge
$main_amount = $total_amount - $transactional_charge; // 1498.60

$gst_rate = 18.0;
$base_amount = round($main_amount / (1 + $gst_rate / 100), 2);
$gst_amount  = round($main_amount - $base_amount, 2);
$payment_method = "Voucher PIN";
$description = "Joining Fee for new account activation (Inclusive of 18% GST)";
$transaction_note = "Includes ₹1.40 transaction charge.";

/* ---------- Check if bill already exists ---------- */
$exists = false;
$check_sql = "SELECT * FROM bills WHERE user_id = ?";
$check = $conn->prepare($check_sql);
if ($check) {
    $check->bind_param("i", $user_id);
    $check->execute();
    $bill_result = $check->get_result();
    if ($bill_result->num_rows > 0) {
        $exists = true;
        $bill = $bill_result->fetch_assoc();

        // Reuse values from existing bill
        $base_amount = $bill['base_amount'];
        $gst_amount  = $bill['gst_amount'];
        $total_amount = $bill['amount'];
        $transactional_charge = isset($bill['transaction_charge']) ? $bill['transaction_charge'] : 1.40;
    }
    $check->close();
}

/* ---------- Insert bill if not already exists ---------- */
if (!$exists) {
    $insert_sql = "INSERT INTO bills (user_id, name, base_amount, gst_amount, transaction_charge, amount, payment_method, description)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);

    if ($stmt) {
        $stmt->bind_param("isddddss", $user_id, $name, $base_amount, $gst_amount, $transactional_charge, $total_amount, $payment_method, $description);
        if (!$stmt->execute()) {
            echo "<div style='color:red'>Failed to insert bill: " . htmlspecialchars($stmt->error) . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div style='color:red'>Failed to insert bill: " . htmlspecialchars($conn->error) . "</div>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Registration Successful</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body text-center">
          <h3 class="mb-4 text-success">🎉 Registration Successful!</h3>
          <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
          <p><strong>User ID:</strong> <?= htmlspecialchars($user_id) ?></p>
          <p><strong>Password:</strong> <?= htmlspecialchars($password) ?></p>
          <p><strong>Base Amount:</strong> ₹<?= number_format($base_amount, 2) ?></p>
          <p><strong>GST (18%):</strong> ₹<?= number_format($gst_amount, 2) ?></p>
          <p><strong>Transaction Charge:</strong> ₹<?= number_format($transactional_charge, 2) ?></p>
          <hr>
          <p><strong>Total Paid:</strong> ₹<?= number_format($total_amount, 2) ?> (Incl. GST + Charges)</p>
          <p class="text-muted small"><?= htmlspecialchars($transaction_note) ?></p>
          <p class="text-muted small">⚠️ Please save these credentials safely.</p>

          <form method="post" action="registration_success_action.php" class="d-flex flex-column gap-2 align-items-center">
            <a href="download_bill.php" class="btn btn-success w-75">📄 Download Bill (PDF)</a>
            <button type="submit" name="action" value="login" class="btn btn-primary w-75">Login Now</button>
            <button type="submit" name="action" value="cancel" class="btn btn-secondary w-75">Cancel</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
