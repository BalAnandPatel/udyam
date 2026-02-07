<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);

require 'config/db.php'; // must define $conn (mysqli)

// ---- CONFIG ----
$JOINING_AMOUNT = 1270;   // joining price
$LEVEL_PERCENT  = 1;      // 1% per member at that level
$MAX_LEVELS     = 12;     // depth to evaluate

// Detect optional columns in earnings
$has_created_at = false;
$colRes = $conn->query("SHOW COLUMNS FROM earnings LIKE 'created_at'");
if ($colRes && $colRes->num_rows > 0) $has_created_at = true;

// ---------------- Helpers ------------------
function levelCountForUser($conn, $userId, $level) {
    $userId = (int)$userId;
    $level  = (int)$level;
    if ($level <= 0) return 0;

    // BFS by parent_id (autopool placement)
    $current = array($userId);
    for ($i = 1; $i <= $level; $i++) {
        if (empty($current)) return 0;

        $ids = array();
        foreach ($current as $id) $ids[] = (int)$id;
        $in  = implode(',', $ids);

        $sql = "SELECT id FROM users WHERE parent_id IN ($in)";
        $res = $conn->query($sql);

        $next = array();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $next[] = (int)$row['id'];
            }
        }
        $current = $next;
    }
    return count($current); // members exactly at that level
}

function insertLevelEarning($conn, $userId, $level, $amount, $has_created_at) {
    $type = 'level';
    $meta = "Level " . (int)$level . " income completed";

    if ($has_created_at) {
        $stmt = $conn->prepare("INSERT INTO earnings (user_id, type, amount, meta, created_at) VALUES (?, ?, ?, ?, NOW())");
    } else {
        $stmt = $conn->prepare("INSERT INTO earnings (user_id, type, amount, meta) VALUES (?, ?, ?, ?)");
    }

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // i s d s
    $ok = $stmt->bind_param("isds", $userId, $type, $amount, $meta);
    if (!$ok) {
        $err = $stmt->error ? $stmt->error : 'bind_param failed';
        $stmt->close();
        throw new Exception($err);
    }

    $ok = $stmt->execute();
    if (!$ok) {
        $err = $stmt->error;
        $stmt->close();
        throw new Exception("Execute failed: " . $err);
    }
    $stmt->close();
    return true;
}

// ---------------- Main Repair Process ------------------
try {
    if (!$conn) {
        throw new Exception("DB connection missing.");
    }

    // Start transaction (PHP 5 friendly)
    $conn->query("START TRANSACTION");

    // 1) Wipe ONLY previous level incomes (keep referral/watch/etc.)
    if (!$conn->query("DELETE FROM earnings WHERE type='level'")) {
        throw new Exception("Delete old level earnings failed: " . $conn->error);
    }

    // 2) Loop every user and recompute completed levels
    $users = $conn->query("SELECT id FROM users");
    if (!$users) {
        throw new Exception("Fetching users failed: " . $conn->error);
    }

    $processed = 0;
    while ($u = $users->fetch_assoc()) {
        $uid = (int)$u['id'];

        for ($lvl = 1; $lvl <= $MAX_LEVELS; $lvl++) {
            $actual   = levelCountForUser($conn, $uid, $lvl);
            $expected = pow(2, $lvl);

            if ($actual == $expected) {
                // Commission = expected members * (joining * percent)
                $perMember = $JOINING_AMOUNT * ($LEVEL_PERCENT / 100.0);
                $amount    = $expected * $perMember;

                insertLevelEarning($conn, $uid, $lvl, $amount, $has_created_at);
            }
            // If not complete, insert nothing.
        }
        $processed++;
    }

    $conn->query("COMMIT");
    echo "✅ Level incomes repaired successfully (only completed levels inserted). Users processed: " . (int)$processed;

} catch (Exception $e) {
    if ($conn) { $conn->query("ROLLBACK"); }
    header("HTTP/1.1 500 Internal Server Error");
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
}
