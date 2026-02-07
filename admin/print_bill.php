<?php
// session_start();
require 'config/db.php'; // your DB connection
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// exit;

if (!isset($_SESSION['admin_id'])) {
    header("Location: register.php");
    exit;
}

$reg = $_SESSION['temp_reg'];
$user_id = (int)$reg['user_id'];

// Fetch bill details
$query = $conn->prepare("SELECT * FROM bills WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$bill = $result->fetch_assoc();
$query->close();

// If no bill found, create default (for demo)
if (!$bill) {
    $bill = [
        'id' => 0,
        'name' => $reg['name'],
        'base_amount' => 1270,
        'gst_amount' => 230,
        'transaction_charge' => 1.40,
        'amount' => 1500,
        'payment_method' => 'Voucher PIN',
        'description' => 'Joining Fee for new account activation (Inclusive of GST)',
        'date' => date('Y-m-d')
    ];
}

// Format values
$base_amount  = number_format($bill['base_amount'], 2);
$gst_amount   = number_format($bill['gst_amount'], 2);
$transaction_charge = isset($bill['transaction_charge']) ? number_format($bill['transaction_charge'], 2) : '1.40';
$total_amount = number_format($bill['amount'], 2); // total including GST + transaction charge
$date         = date("d-m-Y", strtotime($bill['date']));
$name         = htmlspecialchars($bill['name']);
$method       = htmlspecialchars($bill['payment_method']);
$desc         = htmlspecialchars($bill['description']);
$bill_id      = $bill['id'];

// Company Info
$company_name    = "Udyami Bazaar";
$company_address = "Pithipur Atrampur Prayagraj 229412";
$company_gst     = "09ACHFA3172A1ZS";
$company_email   = "support@udyamibazaar.com";
$company_phone   = "+91-7754010524";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Print Bill - <?= htmlspecialchars($company_name) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; font-family: Arial, sans-serif; }
.invoice-box {
    max-width: 750px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
.header img { width: 90px; }
.header h2 { margin: 10px 0 0; font-weight: bold; color: #2b2b2b; }
.company-info { font-size: 13px; color: #555; }
table { width: 100%; margin-top: 15px; border-collapse: collapse; }
th, td { border: 1px solid #ddd; padding: 8px; }
th { background: #f2f2f2; text-align: center; }
td { vertical-align: middle; }
.text-right { text-align: right; }
.no-border td { border: none; }
.footer { text-align: center; font-size: 12px; margin-top: 20px; color: #555; }
.total-row td { font-weight: bold; background: #f2f2f2; }
@media print {
    .no-print { display: none; }
    body { background: #fff; }
}
</style>
</head>
<body>
<div class="invoice-box">
  <div class="header">
    <img src="../images/logo.jpg" alt="Logo">
    <h2><?= htmlspecialchars($company_name) ?></h2>
    <div class="company-info">
      <?= htmlspecialchars($company_address) ?><br>
      GSTIN: <?= htmlspecialchars($company_gst) ?><br>
      Email: <?= htmlspecialchars($company_email) ?> | Phone: <?= htmlspecialchars($company_phone) ?>
    </div>
  </div>

  <h4 class="text-center mb-3">Tax Invoice / Bill Receipt</h4>

  <table class="mb-3">
    <tr>
      <td><strong>Bill No:</strong> #<?= $bill_id ?: 'N/A' ?></td>
      <td class="text-right"><strong>Date:</strong> <?= $date ?></td>
    </tr>
    <tr>
      <td><strong>Billed To:</strong> <?= $name ?></td>
      <td class="text-right"><strong>User ID:</strong> <?= $user_id ?></td>
    </tr>
  </table>

  <table>
    <thead>
      <tr>
        <th>Description</th>
        <th class="text-right">Base Amount (₹)</th>
        <th class="text-right">GST (18%)</th>
        <th class="text-right">Transaction Fee (₹)</th>
        <th class="text-right">Total (₹)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $desc ?></td>
        <td class="text-right"><?= $base_amount ?></td>
        <td class="text-right"><?= $gst_amount ?></td>
        <td class="text-right"><?= $transaction_charge ?></td>
        <td class="text-right"><?= $total_amount ?></td>
      </tr>
      <tr class="total-row">
        <td colspan="4" class="text-right">Grand Total</td>
        <td class="text-right">₹<?= $total_amount ?></td>
      </tr>
    </tbody>
  </table>

  <table class="no-border mt-3">
    <tr>
      <td><strong>Payment Method:</strong> <?= $method ?></td>
      <td class="text-right"><strong>Amount Paid:</strong> ₹<?= $total_amount ?></td>
    </tr>
  </table>

  <div class="footer mt-4">
    Thank you for registering with <strong><?= htmlspecialchars($company_name) ?></strong>.<br>
    This is a computer-generated invoice and does not require a signature.<br>
    <small>Note: The total amount includes ₹<?= $transaction_charge ?> as transaction charge.</small>
  </div>
</div>

<div class="text-center no-print mb-5">
  <button onclick="window.print()" class="btn btn-primary mt-3">🖨️ Print Bill</button>
  <a href="registration_success.php" class="btn btn-secondary mt-3">Back</a>
</div>

<script>
// Automatically open print dialog when page loads
window.onload = function() {
    window.print();
}
</script>
</body>
</html>
