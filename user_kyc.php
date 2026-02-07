<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = "";

/* Fetch existing KYC once */
$kyc = null;
$res = $conn->query("SELECT * FROM kyc WHERE user_id = $user_id LIMIT 1");
if ($res && $res->num_rows > 0) {
    $kyc = $res->fetch_assoc();
}

/* Handle form submit */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name      = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $bank_name      = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
    $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';
    $ifsc_code      = isset($_POST['ifsc_code']) ? $_POST['ifsc_code'] : '';
    $account_holder = isset($_POST['account_holder']) ? $_POST['account_holder'] : '';
    $id_proof_type  = isset($_POST['id_proof_type']) ? $_POST['id_proof_type'] : '';

    // Keep old values if no new file uploaded
    $id_proof_file = isset($kyc['id_proof_file']) ? $kyc['id_proof_file'] : null;
    $photo = isset($kyc['photo']) ? $kyc['photo'] : null;

    // Upload ID proof
    if (!empty($_FILES['id_proof_file']['name'])) {
        $target_dir = "uploads/kyc/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $id_proof_file = $target_dir . time() . "_" . basename($_FILES["id_proof_file"]["name"]);
        move_uploaded_file($_FILES["id_proof_file"]["tmp_name"], $id_proof_file);
    }

    // Upload Photo
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/kyc/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $photo = $target_dir . time() . "_" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    // Upsert
    if ($kyc) {
        $sql = "UPDATE kyc 
                SET full_name=?, bank_name=?, account_number=?, ifsc_code=?, account_holder=?, photo=?, id_proof_type=?, id_proof_file=?, status='Pending'
                WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $full_name, $bank_name, $account_number, $ifsc_code, $account_holder, $photo, $id_proof_type, $id_proof_file, $user_id);
    } else {
        $sql = "INSERT INTO kyc (user_id, full_name, bank_name, account_number, ifsc_code, account_holder, photo, id_proof_type, id_proof_file) 
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssss", $user_id, $full_name, $bank_name, $account_number, $ifsc_code, $account_holder, $photo, $id_proof_type, $id_proof_file);
    }

    if ($stmt && $stmt->execute()) {
        $msg = "<div class='alert alert-success'>✅ KYC details submitted successfully! Waiting for admin approval.</div>";
        $res = $conn->query("SELECT * FROM kyc WHERE user_id = $user_id LIMIT 1");
        if ($res && $res->num_rows > 0) $kyc = $res->fetch_assoc();
    } else {
        $err = $stmt ? $stmt->error : $conn->error;
        $msg = "<div class='alert alert-danger'>❌ Error: " . htmlspecialchars($err) . "</div>";
    }
}

include "includes/header.php";
?>

<div class="container mt-4">
    <h2>User KYC Verification</h2>
    <?= $msg ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?= isset($kyc['full_name']) ? htmlspecialchars($kyc['full_name']) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" value="<?= isset($kyc['bank_name']) ? htmlspecialchars($kyc['bank_name']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Account Number</label>
            <input type="text" name="account_number" class="form-control" value="<?= isset($kyc['account_number']) ? htmlspecialchars($kyc['account_number']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">IFSC Code</label>
            <input type="text" name="ifsc_code" class="form-control" value="<?= isset($kyc['ifsc_code']) ? htmlspecialchars($kyc['ifsc_code']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Account Holder</label>
            <input type="text" name="account_holder" class="form-control" value="<?= isset($kyc['account_holder']) ? htmlspecialchars($kyc['account_holder']) : '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Photo</label>
            <?php if ($kyc && !empty($kyc['photo'])): ?>
                <br><img src="<?= htmlspecialchars($kyc['photo']) ?>" width="120" alt="Photo"><br>
            <?php endif; ?>
            <input type="file" name="photo" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label class="form-label">ID Proof Type</label>
            <select name="id_proof_type" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="Aadhaar" <?= (isset($kyc['id_proof_type']) && $kyc['id_proof_type']=='Aadhaar') ? 'selected' : '' ?>>Aadhaar</option>
                <option value="PAN" <?= (isset($kyc['id_proof_type']) && $kyc['id_proof_type']=='PAN') ? 'selected' : '' ?>>PAN</option>
                <option value="Passport" <?= (isset($kyc['id_proof_type']) && $kyc['id_proof_type']=='Passport') ? 'selected' : '' ?>>Passport</option>
                <option value="Driving License" <?= (isset($kyc['id_proof_type']) && $kyc['id_proof_type']=='Driving License') ? 'selected' : '' ?>>Driving License</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload ID Proof</label>
            <?php if ($kyc && !empty($kyc['id_proof_file'])): ?>
                <br><img src="<?= htmlspecialchars($kyc['id_proof_file']) ?>" width="120" alt="ID Proof"><br>
            <?php endif; ?>
            <input type="file" name="id_proof_file" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-primary">Submit KYC</button>
    </form>

    <?php if ($kyc): ?>
        <div class="card mt-4 p-3">
            <?php
            $badgeClass = 'warning';
            if ($kyc['status'] == 'Approved') $badgeClass = 'success';
            elseif ($kyc['status'] == 'Rejected') $badgeClass = 'danger';
            ?>
            <h5>KYC Status: <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($kyc['status']) ?></span></h5>
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
