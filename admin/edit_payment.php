<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config/db.php"; // DB connection

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}
$id = (int)$_GET['id'];

// Fetch record
$sql = "SELECT * FROM payment_details WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Record not found.");
}
$row = $result->fetch_assoc();

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $method_type    = $_POST['method_type'];
    $bank_name      = isset($_POST['bank_name']) ? $_POST['bank_name'] : null;
    $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : null;
    $ifsc_code      = isset($_POST['ifsc_code']) ? $_POST['ifsc_code'] : null;
    $account_holder = isset($_POST['account_holder']) ? $_POST['account_holder'] : null;
    $upi_id         = isset($_POST['upi_id']) ? $_POST['upi_id'] : null;
    $wallet_address = isset($_POST['wallet_address']) ? $_POST['wallet_address'] : null;

    // QR upload (optional)
    $qr_image = $row['qr_image']; // keep old one
    if (!empty($_FILES['qr_image']['name'])) {
        $target_dir = "../uploads/qr/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $new_file = $target_dir . time() . "_" . basename($_FILES["qr_image"]["name"]);
        if (move_uploaded_file($_FILES["qr_image"]["tmp_name"], $new_file)) {
            $qr_image = $new_file;
        }
    }

    $update_sql = "UPDATE payment_details 
                   SET method_type=?, bank_name=?, account_number=?, ifsc_code=?, account_holder=?, upi_id=?, qr_image=?, wallet_address=? 
                   WHERE id=?";
    if (!$update_stmt = $conn->prepare($update_sql)) {
        die("Prepare failed: " . $conn->error);
    }
    $update_stmt->bind_param("ssssssssi", $method_type, $bank_name, $account_number, $ifsc_code, $account_holder, $upi_id, $qr_image, $wallet_address, $id);

    if ($update_stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ Payment method updated successfully!</div>";
        // Refresh data
        $row = array_merge($row, $_POST);
        $row['qr_image'] = $qr_image;
    } else {
        $msg = "<div class='alert alert-danger'>❌ Error: " . $update_stmt->error . "</div>";
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="container mt-4">
    <h2 class="mb-4">Edit Payment Method (ID: <?= $id ?>)</h2>

    <?= $msg ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">Method Type:</label>
            <select name="method_type" id="method_type" class="form-select" required>
                <option value="">-- Select Method --</option>
                <option value="BANK" <?= ($row['method_type']=="BANK"?"selected":"") ?>>Bank Account</option>
                <option value="UPI" <?= ($row['method_type']=="UPI"?"selected":"") ?>>UPI</option>
                <option value="QR" <?= ($row['method_type']=="QR"?"selected":"") ?>>QR</option>
                <option value="WALLET" <?= ($row['method_type']=="WALLET"?"selected":"") ?>>Wallet</option>
            </select>
        </div>

        <div id="bank" class="<?= ($row['method_type']=="BANK"?"":"hidden") ?>">
            <label class="form-label">Bank Name:</label>
            <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($row['bank_name']) ?>">
            <label class="form-label">Account Number:</label>
            <input type="text" name="account_number" class="form-control" value="<?= htmlspecialchars($row['account_number']) ?>">
            <label class="form-label">IFSC Code:</label>
            <input type="text" name="ifsc_code" class="form-control" value="<?= htmlspecialchars($row['ifsc_code']) ?>">
            <label class="form-label">Account Holder:</label>
            <input type="text" name="account_holder" class="form-control" value="<?= htmlspecialchars($row['account_holder']) ?>">
        </div>

        <div id="upi" class="<?= ($row['method_type']=="UPI"?"":"hidden") ?>">
            <label class="form-label">UPI ID:</label>
            <input type="text" name="upi_id" class="form-control" value="<?= htmlspecialchars($row['upi_id']) ?>">
        </div>

        <div id="qr" class="<?= ($row['method_type']=="QR"?"":"hidden") ?>">
            <label class="form-label">QR Image:</label><br>
            <?php if(!empty($row['qr_image'])): ?>
                <img src="<?= htmlspecialchars($row['qr_image']) ?>" width="100"><br>
            <?php endif; ?>
            <input type="file" name="qr_image" class="form-control mt-2">
        </div>

        <div id="wallet" class="<?= ($row['method_type']=="WALLET"?"":"hidden") ?>">
            <label class="form-label">Wallet Address:</label>
            <input type="text" name="wallet_address" class="form-control" value="<?= htmlspecialchars($row['wallet_address']) ?>">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="insert_payment.php" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>

<script>
document.getElementById("method_type").addEventListener("change", function() {
    document.getElementById("bank").classList.add("hidden");
    document.getElementById("upi").classList.add("hidden");
    document.getElementById("qr").classList.add("hidden");
    document.getElementById("wallet").classList.add("hidden");

    var val = this.value.toLowerCase();
    if (val) {
        document.getElementById(val).classList.remove("hidden");
    }
});
</script>

<style>
.hidden { display: none; }
</style>

<?php include "includes/footer.php"; ?>
