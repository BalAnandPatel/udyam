<?php
require 'config/db.php';

if (!isset($_GET['user_id']) || !isset($_GET['type'])) {
    echo "<div class='alert alert-danger'>Missing parameters.</div>";
    exit;
}

$user_id = (int) $_GET['user_id'];
$type_raw = $_GET['type'];

// allowed earning types
$allowed = ['referral', 'level', 'watch', 'admin_credit', 'other', ''];
$type = in_array($type_raw, $allowed, true) ? $type_raw : 'other';

if ($type === 'referral') {
    // ✅ Referral income details
    $sql = "SELECT e.id, e.amount, e.created_at, e.meta,
                   src.id AS src_id, src.name AS src_name, src.email AS src_email
            FROM earnings e
            LEFT JOIN users src
              ON e.meta LIKE CONCAT('%user #', src.id, '%')
            WHERE e.user_id = {$user_id} AND e.type = 'referral'
            ORDER BY e.created_at DESC";
    $res = mysqli_query($conn, $sql);

    echo "<h6 class='mb-2'>Referral Income Details</h6>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped table-hover table-bordered align-middle'>";
    echo "<thead class='table-dark'>
            <tr>
              <th>#</th>
              <th>Amount (₹)</th>
              <th>From User</th>
              <th>Email</th>
              <th>Meta</th>
              <th>Date</th>
            </tr>
          </thead><tbody>";

    if ($res && mysqli_num_rows($res) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $fromUser = '-';
            $fromEmail = '-';
            if (!empty($row['src_id'])) {
                $fromUser = "User #".$row['src_id']." — ".htmlspecialchars($row['src_name']);
                $fromEmail = htmlspecialchars($row['src_email']);
            } elseif (!empty($row['meta']) && preg_match('/user\s+#(\d+)/i', $row['meta'], $m)) {
                $fromUser = "User #".$m[1];
            }

            echo "<tr>
                    <td>{$i}</td>
                    <td>₹".number_format((float)$row['amount'], 2)."</td>
                    <td>{$fromUser}</td>
                    <td>{$fromEmail}</td>
                    <td>".htmlspecialchars($row['meta'])."</td>
                    <td>".date('d M Y H:i', strtotime($row['created_at']))."</td>
                  </tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No referral records found.</td></tr>";
    }
    echo "</tbody></table></div>";

} elseif ($type === 'level') {
    // ✅ Team by levels (downline members)
    $max_levels = 12; // adjust for your MLM plan
    $levels = [];
    $currentParentIds = [$user_id];

    for ($lvl = 1; $lvl <= $max_levels; $lvl++) {
        if (empty($currentParentIds)) break;

        $in = implode(',', array_map('intval', $currentParentIds));
        $q = "SELECT id, name, email, created_at, parent_id, position
              FROM users
              WHERE parent_id IN ($in)
              ORDER BY created_at ASC";
        $rs = mysqli_query($conn, $q);

        $rows = [];
        $next = [];
        if ($rs && mysqli_num_rows($rs) > 0) {
            while ($r = mysqli_fetch_assoc($rs)) {
                $rows[] = $r;
                $next[] = (int)$r['id'];
            }
        }

        $levels[$lvl] = $rows;
        $currentParentIds = $next;
        if (empty($currentParentIds)) break;
    }

    echo "<h6 class='mb-2'>Team by Levels</h6>";
    $filteredLevels = array_filter($levels); // ✅ safe fix
    if (empty($filteredLevels)) {
        echo "<div class='alert alert-warning'>No downline found.</div>";
        exit;
    }

    $accId = 'lvAcc'.uniqid();
    echo "<div class='accordion' id='{$accId}'>";

    foreach ($levels as $lvl => $members) {
        $cid = $accId."_c".$lvl;
        $hid = $accId."_h".$lvl;
        $count = is_array($members) ? count($members) : 0;

        echo "<div class='accordion-item'>
                <h2 class='accordion-header' id='{$hid}'>
                  <button class='accordion-button".($lvl>1?' collapsed':'')."' type='button'
                          data-bs-toggle='collapse' data-bs-target='#{$cid}'>
                    Level {$lvl} <span class='ms-2 badge bg-secondary'>{$count}</span>
                  </button>
                </h2>
                <div id='{$cid}' class='accordion-collapse collapse".($lvl===1?' show':'')."' data-bs-parent='#{$accId}'>
                  <div class='accordion-body'>";

        if ($count === 0) {
            echo "<div class='text-muted'>No members at this level.</div>";
        } else {
            echo "<div class='table-responsive'>
                    <table class='table table-striped table-hover table-bordered align-middle'>
                      <thead class='table-dark'>
                        <tr>
                          <th>#</th>
                          <th>User ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Parent ID</th>
                          <th>Position</th>
                          <th>Join Date</th>
                        </tr>
                      </thead>
                      <tbody>";
            $i = 1;
            foreach ($members as $m) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>".(int)$m['id']."</td>
                        <td>".htmlspecialchars($m['name'])."</td>
                        <td>".htmlspecialchars($m['email'])."</td>
                        <td>".(int)$m['parent_id']."</td>
                        <td>".htmlspecialchars($m['position'])."</td>
                        <td>".date('d M Y H:i', strtotime($m['created_at']))."</td>
                      </tr>";
                $i++;
            }
            echo "  </tbody></table>
                  </div>";
        }

        echo "  </div>
                </div>
              </div>";
    }
    echo "</div>";

} else {
    // ✅ Other types (watch, admin_credit, etc.)
    $safeType = htmlspecialchars($type === '' ? '(empty)' : $type);
    $sql = "SELECT id, amount, created_at, meta
            FROM earnings
            WHERE user_id = {$user_id} AND ".($type==='other' ? " (type='' OR type IS NULL) " : " type = '{$type}' ")."
            ORDER BY created_at DESC";
    $res = mysqli_query($conn, $sql);

    echo "<h6 class='mb-2'>".$safeType." Details</h6>";
    echo "<div class='table-responsive'>
            <table class='table table-striped table-hover table-bordered align-middle'>
              <thead class='table-dark'>
                <tr>
                  <th>#</th>
                  <th>Amount (₹)</th>
                  <th>Meta</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>";
    if ($res && mysqli_num_rows($res) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr>
                    <td>{$i}</td>
                    <td>₹".number_format((float)$row['amount'], 2)."</td>
                    <td>".htmlspecialchars($row['meta'])."</td>
                    <td>".date('d M Y H:i', strtotime($row['created_at']))."</td>
                  </tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>No records found.</td></tr>";
    }
    echo "  </tbody></table>
          </div>";
}
