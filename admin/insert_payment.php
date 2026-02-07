<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config/db.php";

/* ===============================
   CONFIG
================================ */
$projectRoot = realpath(__DIR__ . "/..");

$msg = "";

/* ===============================
   SUCCESS MESSAGE (PRG)
================================ */
if (isset($_GET['success'])) {
    $msg = "<div class='alert alert-success'>✅ Payment method added successfully!</div>";
}

/* ===============================
   INLINE DELETE
================================ */
if (isset($_GET['delete_id'])) {

    $delete_id = (int) $_GET['delete_id'];

    $res = $conn->query("SELECT qr_image FROM payment_details WHERE id=$delete_id");
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (!empty($row['qr_image']) && file_exists($projectRoot . "/" . $row['qr_image'])) {
            unlink($projectRoot . "/" . $row['qr_image']);
        }
    }

    $conn->query("DELETE FROM payment_details WHERE id=$delete_id");
    header("Location: insert_payment.php");
    exit;
}

/* ===============================
   HANDLE INSERT
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $method_type = $_POST['method_type'];

    $bank_name = $account_number = $ifsc_code = $account_holder = null;
    $upi_id = $wallet_address = $qr_image = null;

    if ($method_type === "BANK") {
        $bank_name      = $_POST['bank_name'] ?? null;
        $account_number = $_POST['account_number'] ?? null;
        $ifsc_code      = $_POST['ifsc_code'] ?? null;
        $account_holder = $_POST['account_holder'] ?? null;
    }

    if ($method_type === "UPI") {
        $upi_id = $_POST['upi_id'] ?? null;
    }

    if ($method_type === "WALLET") {
        $wallet_address = $_POST['wallet_address'] ?? null;
    }

    if ($method_type === "QR") {

        if (!isset($_FILES['qr_image'])) {
            die("❌ QR file not received");
        }

        if ($_FILES['qr_image']['error'] !== UPLOAD_ERR_OK) {
            die("❌ Upload error code: " . $_FILES['qr_image']['error']);
        }

        $uploadsDir = $projectRoot . "/uploads";
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777);
        }

        $qrDir = $uploadsDir . "/qr";
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0777);
        }

        if (!is_writable($qrDir)) {
            die("❌ uploads/qr is not writable");
        }

        $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $_FILES['qr_image']['name']);
        $qr_image = "uploads/qr/" . $safeName;

        if (!move_uploaded_file($_FILES['qr_image']['tmp_name'], $qrDir . "/" . $safeName)) {
            die("❌ Failed to save QR image");
        }
    }

    $sql = "INSERT INTO payment_details
            (method_type, bank_name, account_number, ifsc_code, account_holder, upi_id, qr_image, wallet_address)
            VALUES (?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssss",
        $method_type,
        $bank_name,
        $account_number,
        $ifsc_code,
        $account_holder,
        $upi_id,
        $qr_image,
        $wallet_address
    );

    if ($stmt->execute()) {
        header("Location: insert_payment.php?success=1");
        exit;
    } else {
        $msg = "<div class='alert alert-danger'>❌ Database error</div>";
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="container mt-4">
    <h2>Add Payment Collection Detail</h2>
    <?= $msg ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm" style="max-width:600px;">
        <label>Method Type</label>
        <select name="method_type" id="method_type" class="form-select mb-3" required>
            <option value="">-- Select --</option>
            <option value="BANK">Bank</option>
            <option value="UPI">UPI</option>
            <option value="QR">QR</option>
            <option value="WALLET">Wallet</option>
        </select>

        <div id="bank" class="hidden">
            <input name="bank_name" class="form-control mb-2" placeholder="Bank Name">
            <input name="account_number" class="form-control mb-2" placeholder="Account Number">
            <input name="ifsc_code" class="form-control mb-2" placeholder="IFSC Code">
            <input name="account_holder" class="form-control mb-2" placeholder="Account Holder">
        </div>

        <div id="upi" class="hidden">
            <input name="upi_id" class="form-control" placeholder="UPI ID">
        </div>

        <div id="qr" class="hidden">
            <input type="file" name="qr_image" class="form-control">
        </div>

        <div id="wallet" class="hidden">
            <input name="wallet_address" class="form-control" placeholder="Wallet Address">
        </div>

        <button class="btn btn-primary mt-3">Save</button>
    </form>

    <h3 class="mt-5">Existing Payment Methods</h3>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Method</th>
                <th>Details</th>
                <th>QR</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $baseUrl = "../";
        $res = $conn->query("SELECT * FROM payment_details ORDER BY id DESC");

        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {

                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['method_type']}</td>";

                echo "<td>";
                if ($row['method_type'] === "BANK") {
                    echo "Bank: {$row['bank_name']}<br>
                          A/C: {$row['account_number']}<br>
                          IFSC: {$row['ifsc_code']}<br>
                          Holder: {$row['account_holder']}";
                }
                if ($row['method_type'] === "UPI") echo "UPI: {$row['upi_id']}";
                if ($row['method_type'] === "WALLET") echo "Wallet: {$row['wallet_address']}";
                echo "</td>";

                echo "<td>";
                if ($row['method_type'] === "QR" && $row['qr_image']) {
                    $qr = ltrim(str_replace('../', '', $row['qr_image']), '/');
                    echo "<img src='{$baseUrl}{$qr}' width='80'>";
                }
                echo "</td>";

                echo "<td>
                    <a href='edit_payment.php?id={$row['id']}' class='btn btn-sm btn-warning'>Update</a>
                    <a href='insert_payment.php?delete_id={$row['id']}'
                       onclick='return confirm(\"Delete this payment method?\")'
                       class='btn btn-sm btn-danger'>Delete</a>
                </td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById("method_type").addEventListener("change", function () {
    ['bank','upi','qr','wallet'].forEach(id => document.getElementById(id).classList.add('hidden'));
    if (this.value) document.getElementById(this.value.toLowerCase()).classList.remove('hidden');
});
</script>

<style>
.hidden { display:none; }
</style>

<?php include "includes/footer.php"; ?>
