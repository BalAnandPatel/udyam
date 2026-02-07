<?php
// Show all errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("config/db.php"); // DB connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch vouchers safely
$sql = "SELECT code, amount, status, created_at, used_at 
        FROM vouchers 
        WHERE owner_id = ? 
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result(); 
$stmt->bind_result($code, $amount, $status, $created_at, $used_at);

// Include header
include("includes/header.php"); 
?>

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Your Purchase Codes</h4>
      <!-- Back button -->
      <a href="dashboard.php" class="btn btn-light btn-sm">⬅ Go Back</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Voucher Code</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Used At</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $counter = 1;
          if ($stmt->num_rows > 0) {
              while ($stmt->fetch()) {
                  // Badge for status
                  if ($status === 'used') {
                      $statusBadge = '<span class="badge bg-success">Used</span>';
                  } elseif ($status === 'expired') {
                      $statusBadge = '<span class="badge bg-danger">Expired</span>';
                  } else {
                      $statusBadge = '<span class="badge bg-warning text-dark">Unused</span>';
                  }

                  echo "<tr>";
                  echo "<td>".$counter++."</td>";
                  echo "<td><strong>".htmlspecialchars($code)."</strong></td>";
                  echo "<td>₹".htmlspecialchars($amount)."</td>";
                  echo "<td>".$statusBadge."</td>";
                  echo "<td>".date("d M Y, h:i A", strtotime($created_at))."</td>";
                  echo "<td>".($used_at ? date("d M Y, h:i A", strtotime($used_at)) : "Not Used")."</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6' class='text-center text-muted'>No purchase codes found.</td></tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
