<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config/db.php'; // must define $conn (mysqli)

$err = '';

/* ======================================================
   Helper Functions (same as before)
====================================================== */
function findPlacement($rootId, $conn) {
    $check = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE parent_id='$rootId'");
    $row = $check->fetch_assoc();
    if ($row['cnt'] < 2) return $rootId;

    $queue = [$rootId];
    while (!empty($queue)) {
        $current = array_shift($queue);
        $children = $conn->query("SELECT id FROM users WHERE parent_id='$current'");
        if ($children->num_rows < 2) return $current;
        while ($child = $children->fetch_assoc()) {
            $queue[] = $child['id'];
        }
    }
    return null;
}

function giveReferralIncome($newUserId, $sponsorId, $amount, $conn) {
    if (!$sponsorId) return;
    $referral_income = ($amount * 10) / 100;
    $meta = "Direct Referral from User $newUserId";

    $stmt = $conn->prepare("INSERT INTO earnings (user_id, type, amount, meta) VALUES (?, 'direct_referral', ?, ?)");
    $stmt->bind_param("ids", $sponsorId, $referral_income, $meta);
    $stmt->execute();
    $stmt->close();

    $conn->query("UPDATE users SET wallet_balance = wallet_balance + $referral_income WHERE id='$sponsorId'");
}

function getLevelMemberCount($userId, $level, $conn) {
    $currentLevel = [$userId];
    $count = 0;
    for ($i = 1; $i <= $level; $i++) {
        $nextLevel = [];
        foreach ($currentLevel as $uid) {
            $res = $conn->query("SELECT id FROM users WHERE parent_id='$uid'");
            while ($row = $res->fetch_assoc()) {
                $nextLevel[] = $row['id'];
                if ($i == $level) $count++;
            }
        }
        $currentLevel = $nextLevel;
    }
    return $count;
}

function distributeLevelIncome($newUserId, $amount, $conn) {
    $current = $newUserId;
    for ($level = 1; $level <= 12; $level++) {
        $q = $conn->query("SELECT parent_id FROM users WHERE id='$current'");
        $row = $q->fetch_assoc();
        if (!$row || !$row['parent_id']) break;

        $upline = $row['parent_id'];
        $expectedCount = pow(2, $level);
        $actualCount   = getLevelMemberCount($upline, $level, $conn);

        if ($actualCount == $expectedCount) {
            $commission = ($amount * 1 / 100) * $expectedCount;
            $meta = "Level $level income completed with $expectedCount members";

            $dupCheck = $conn->prepare("SELECT id FROM earnings WHERE user_id=? AND type='level' AND meta LIKE ?");
            $likeMeta = "Level $level income%";
            $dupCheck->bind_param("is", $upline, $likeMeta);
            $dupCheck->execute();
            $dupCheck->store_result();

            if ($dupCheck->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO earnings (user_id, type, amount, meta) VALUES (?, 'level', ?, ?)");
                $stmt->bind_param("ids", $upline, $commission, $meta);
                $stmt->execute();
                $stmt->close();
                $conn->query("UPDATE users SET wallet_balance = wallet_balance + $commission WHERE id='$upline'");
            }
            $dupCheck->close();
        }
        $current = $upline;
    }
}

/* ======================================================
   Main Registration Logic
====================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $mobile   = trim($_POST['mobile']);
    $raw_pass = $_POST['password'];
    $pin      = trim($_POST['pin']);
    $sponsor  = (isset($_POST['sponsor_id']) && is_numeric($_POST['sponsor_id'])) ? (int)$_POST['sponsor_id'] : NULL;
    $product_id    = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    $delivery_type = isset($_POST['delivery_type']) ? $_POST['delivery_type'] : null;

    $password = md5($raw_pass);

    // validate pin
    $pinId = null; $pinStatus = null;
    $checkPin = $conn->prepare("SELECT id, status FROM vouchers WHERE code = ?");
    $checkPin->bind_param("s", $pin);
    $checkPin->execute();
    $checkPin->store_result();
    if ($checkPin->num_rows === 0) {
        $err = "Invalid PIN!";
    } else {
        $checkPin->bind_result($pinId, $pinStatus);
        $checkPin->fetch();
        if ($pinStatus === 'used') $err = "This PIN has already been used!";
    }
    $checkPin->close();

    if (empty($err) && $sponsor) {
        $check = $conn->prepare("SELECT id FROM users WHERE id=?");
        $check->bind_param("i", $sponsor);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            $err = "Invalid Referral ID!";
            $sponsor = NULL;
        }
        $check->close();
    }

    if (empty($err)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) $err = "Email already registered!";
        $check->close();
    }

    if (empty($err)) {
        $is_active = 1;
        $stmt = $conn->prepare("INSERT INTO users 
            (sponsor_id,name,email,mobile,password,plain_password,pin,is_active,product_id,delivery_type,created_at) 
            VALUES (?,?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->bind_param("issssssiis", $sponsor, $name, $email, $mobile, $password, $raw_pass, $pin, $is_active, $product_id, $delivery_type);
        if ($stmt->execute()) {
            $new_user_id = $stmt->insert_id;
        } else {
            $err = "Registration failed: " . $stmt->error;
        }
        $stmt->close();
    }

    if (empty($err) && !empty($new_user_id)) {
        $upd = $conn->prepare("UPDATE vouchers SET status='used', used_at=NOW(), owner_id=? WHERE code=?");
        $upd->bind_param("is", $new_user_id, $pin);
        $upd->execute();
        $upd->close();

        $rootId   = 1;
        $parentId = findPlacement($rootId, $conn);

        $position = 'left';
        $check = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE parent_id='$parentId' AND position='left'");
        $row = $check->fetch_assoc();
        if ($row['cnt'] > 0) $position = 'right';

        $conn->query("UPDATE users SET parent_id='$parentId', position='$position' WHERE id='$new_user_id'");

        $joining_amount = 1270;
        giveReferralIncome($new_user_id, $sponsor, $joining_amount, $conn);
        distributeLevelIncome($new_user_id, $joining_amount, $conn);

        $_SESSION['temp_reg'] = [
            'user_id' => $new_user_id,
            'name'    => $name,
            'password'=> $raw_pass
        ];
        header("Location: registration_success.php");
        exit;
    }
}

$products = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>User Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg">
        <div class="card-body">
          <h3 class="mb-4">Register</h3>
          <?php if (!empty($err)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
          <?php endif; ?>
          <form method="post" autocomplete="off">
            <div class="mb-3"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Mobile Number</label><input type="text" name="mobile" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">PIN (Voucher Code)</label><input type="text" name="pin" class="form-control" required></div>
            <div class="mb-3">
              <label class="form-label">Referral ID</label>
              <input type="number" name="sponsor_id" id="sponsor_id" class="form-control"
                     value="<?= isset($_GET['ref']) ? (int)$_GET['ref'] : '' ?>"
                     <?= isset($_GET['ref']) ? 'readonly' : '' ?> required>
              <div id="sponsor_name" class="mt-1 small"></div>
            </div>
            
            <!-- Product selection with image preview -->
            <div class="mb-3">
              <label class="form-label">Select Product</label>
              <select name="product_id" id="product_id" class="form-select" required onchange="loadProductPhoto()">
                <option value="">-- Select Product --</option>
                <?php if ($products && $products->num_rows > 0): while ($p = $products->fetch_assoc()): ?>
                  <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> - ₹<?= number_format($p['price'], 2) ?></option>
                <?php endwhile; else: ?>
                  <option disabled>No products available</option>
                <?php endif; ?>
              </select>
            </div>

            <div class="text-center mb-3" id="product_photo_box" style="display:none;">
              <img id="product_photo" src="" alt="Product Photo" style="max-width:200px; border-radius:10px; box-shadow:0 0 5px rgba(0,0,0,0.2);">
            </div>

            <div class="mb-3">
              <label class="form-label">Delivery Type</label><br>
              <input type="radio" name="delivery_type" value="Booking" required> Booking
              <input type="radio" name="delivery_type" value="Instant" required> Instant Delivery
            </div>

            <button class="btn btn-success w-100">Register</button>
          </form>
        </div>
      </div>
      <div class="text-center mt-3">
        <p>Already registered? <a href="login.php">Login here</a></p>
      </div>
    </div>
  </div>
</div>

<script>
function loadSponsorName() {
    let sponsorId = document.getElementById("sponsor_id").value.trim();
    if (sponsorId) {
        fetch("sponsor_lookup.php?id=" + sponsorId)
          .then(res => res.text())
          .then(data => { document.getElementById("sponsor_name").innerHTML = data; })
          .catch(err => { document.getElementById("sponsor_name").innerHTML = "<span class='text-danger'>Error checking sponsor</span>"; });
    } else {
        document.getElementById("sponsor_name").innerHTML = "";
    }
}

document.getElementById("sponsor_id")?.addEventListener("input", loadSponsorName);
window.addEventListener("DOMContentLoaded", loadSponsorName);

// ✅ Load product photo dynamically
function loadProductPhoto() {
  const productId = document.getElementById("product_id").value;
  const box = document.getElementById("product_photo_box");
  const img = document.getElementById("product_photo");

  if (!productId) {
    box.style.display = "none";
    img.src = "";
    return;
  }

  fetch("get_product_photo.php?id=" + productId)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.photo) {
        img.src = data.photo;
        box.style.display = "block";
      } else {
        box.style.display = "none";
      }
    })
    .catch(err => {
      console.error(err);
      box.style.display = "none";
    });
}
</script>
</body>
</html>
