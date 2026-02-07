<?php
session_start();
require '../config/db.php';

// ✅ Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// ✅ Helper: generate 10-char voucher code (works on old PHP too)
function generateVoucherCode() {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
}

// ✅ Approve / Reject
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    // Fetch pending request
    $sqlFetch = "SELECT * FROM voucher_purchase_requests WHERE id=$id AND status='pending'";
    $res = $conn->query($sqlFetch);
    if (!$res) {
        die("SQL Error (fetch request): " . htmlspecialchars($conn->error));
    }

    if ($res->num_rows > 0) {
        $req = $res->fetch_assoc();
        $user_id = (int) $req['user_id'];
        $qty     = (int) $req['quantity'];

        if ($action === 'approve') {
            // Generate & assign vouchers
            for ($i = 1; $i <= $qty; $i++) {
                $code = generateVoucherCode();

                // NOTE: backticks around column names; amount is fixed 1270 (BV)
                $sql = "INSERT INTO `vouchers` (`code`, `amount`, `owner_id`) VALUES (?, 1270, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Prepare failed (INSERT vouchers): " . htmlspecialchars($conn->error) . " | SQL: " . $sql);
                }

                // 's' (string) for code, 'i' (int) for owner_id
                if (!$stmt->bind_param("si", $code, $user_id)) {
                    die("bind_param failed (INSERT vouchers): " . htmlspecialchars($stmt->error));
                }
                if (!$stmt->execute()) {
                    die("Execute failed (INSERT vouchers): " . htmlspecialchars($stmt->error));
                }
                $stmt->close();
            }

            // Update request status -> approved
            $updSql = "UPDATE voucher_purchase_requests 
                       SET status='approved', approved_at=NOW(), approved_by={$_SESSION['admin_id']} 
                       WHERE id=$id";
            if (!$conn->query($updSql)) {
                die("SQL Error (update request approved): " . htmlspecialchars($conn->error));
            }

            $message = "<div class='alert alert-success'>✅ Request Approved & {$qty} PIN(s) assigned to User #{$user_id}.</div>";

        } elseif ($action === 'reject') {
            $updSql = "UPDATE voucher_purchase_requests 
                       SET status='rejected', approved_at=NOW(), approved_by={$_SESSION['admin_id']} 
                       WHERE id=$id";
            if (!$conn->query($updSql)) {
                die("SQL Error (update request rejected): " . htmlspecialchars($conn->error));
            }
            $message = "<div class='alert alert-danger'>❌ Request Rejected.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>⚠ Invalid or already processed request.</div>";
    }
}

include 'includes/header.php';
?>
<div class="container mt-4">
    <h3>📌 PIN Purchase Requests</h3>
    <?= $message ?>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Txn ID</th>
            <th>Status</th>
            <th>Requested At</th>
            <th>Action</th>
        </tr>
        <?php
        $listSql = "SELECT * FROM voucher_purchase_requests ORDER BY id DESC";
        $listRes = $conn->query($listSql);
        if (!$listRes) {
            die("SQL Error (list requests): " . htmlspecialchars($conn->error));
        }
        while ($row = $listRes->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['quantity']}</td>
                <td>₹{$row['amount']}</td>
                <td>{$row['payment_mode']}</td>
                <td>{$row['txn_id']}</td>
                <td>{$row['status']}</td>
                <td>{$row['requested_at']}</td>
                <td>";
                if ($row['status'] == 'pending') {
                    echo "<a href='pin_requests.php?action=approve&id={$row['id']}' class='btn btn-success btn-sm'>Approve</a>
                          <a href='pin_requests.php?action=reject&id={$row['id']}' class='btn btn-danger btn-sm'>Reject</a>";
                } else {
                    echo "-";
                }
            echo "</td>
            </tr>";
        }
        ?>
    </table>
</div>
<?php include 'includes/footer.php'; ?>
