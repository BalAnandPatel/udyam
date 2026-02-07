<?php
include 'includes/header.php';

/* ===================== Helper Functions ===================== */

function getDirectsCount($conn, $userId) {
    $userId = (int)$userId;
    $res = $conn->query("SELECT COUNT(*) AS c FROM users WHERE sponsor_id = $userId");
    $row = $res ? $res->fetch_assoc() : null;
    return isset($row['c']) ? (int)$row['c'] : 0;
}

function getTotalEarning($conn, $userId) {
    $userId = (int)$userId;
    $res = $conn->query("SELECT IFNULL(SUM(amount),0) AS tot FROM earnings WHERE user_id = $userId");
    $row = $res ? $res->fetch_assoc() : null;
    return isset($row['tot']) ? (float)$row['tot'] : 0.0;
}

function getCompletedLevel($conn, $rootUserId, $maxCheck = 12) {
    $current = array((int)$rootUserId);
    $completed = 0;

    for ($level = 1; $level <= (int)$maxCheck; $level++) {
        if (empty($current)) break;
        $next = array();
        foreach ($current as $uid) {
            $uid = (int)$uid;
            $q = $conn->query("SELECT id FROM users WHERE parent_id = $uid");
            if (!$q) return $completed;

            $children = array();
            while ($row = $q->fetch_assoc()) {
                $children[] = (int)$row['id'];
            }

            if (count($children) !== 2) {
                return $completed;
            }

            foreach ($children as $cid) {
                $next[] = $cid;
            }
        }

        $completed = $level;
        $current = $next;
    }

    return $completed;
}

function maskHash($hash) {
    if ($hash === '') return '';
    $len = strlen($hash);
    $repeat = max(8, min(12, $len));
    return str_repeat('•', $repeat);
}

/* ===================== FILTER LOGIC ===================== */
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'today') {
    $todayDate = date("Y-m-d");
    $whereFilter = "WHERE DATE(u.created_at) = '$todayDate'";
    $pageTitle = "Today's Joined Users";
} else {
    $whereFilter = "";
    $pageTitle = "All Registered Users";
}

/* ===================== Pagination ===================== */
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Total count for pagination
$countQry = $conn->query("SELECT COUNT(*) AS total FROM users u $whereFilter");
$totalUsers = $countQry ? (int)$countQry->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalUsers / $limit);

/* ===================== Fetch Users ===================== */
$sql = "
    SELECT u.*, 
           r.name AS referrer_name, 
           p.name AS product_name, 
           p.price AS product_price
    FROM users u
    LEFT JOIN users r ON u.referrer_id = r.id
    LEFT JOIN products p ON u.product_id = p.id
    $whereFilter
    ORDER BY u.created_at DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $sql);
?>

<div class="row">
    <div class="col-md-12">

        <!-- FILTER BUTTONS -->
        <div class="mb-3">
            <a href="users.php" class="btn btn-primary btn-sm">All Users</a>
            <a href="users.php?filter=today" class="btn btn-success btn-sm">Today's Users</a>
        </div>

        <h3 class="mb-4"><?= $pageTitle ?></h3>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name / Email / Mobile</th>
                        <th>Password</th>
                        <th>Wallet (₹)</th>
                        <th>Referrer</th>
                        <th>Directs</th>
                        <th>Completed Level</th>
                        <th>Total Earning (₹)</th>
                        <th>Product</th>
                        <th>Delivery Type</th>
                        <th>PIN</th>
                        <th>Active</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if($result && mysqli_num_rows($result) > 0): ?>
                    <?php $i = $offset + 1; while($user = mysqli_fetch_assoc($result)): ?>
                        
                        <?php
                        $uid            = (int)$user['id'];
                        $directs        = getDirectsCount($conn, $uid);
                        $completedLevel = getCompletedLevel($conn, $uid, 12);
                        $totalEarning   = getTotalEarning($conn, $uid);
                        $mobile         = isset($user['mobile']) ? $user['mobile'] : '-';
                        ?>

                        <tr>
                            <td><?= $i; ?></td>

                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($user['name']); ?> (ID: <?= $uid ?>)</div>
                                <div class="text-muted small"><?= htmlspecialchars($user['email']); ?></div>
                                <div class="text-muted small">📞 <?= htmlspecialchars($mobile); ?></div>
                            </td>

                            <td><?= htmlspecialchars($user['plain_password']); ?></td>

                            <td><?= number_format((float)$user['wallet_balance'], 2); ?></td>

                            <td><?= $user['referrer_name'] ? htmlspecialchars($user['referrer_name']) : '-'; ?></td>

                            <td><?= $directs ?></td>

                            <td><?= $completedLevel ?></td>

                            <td><?= number_format($totalEarning, 2) ?></td>

                            <td>
                                <?php if (!empty($user['product_name'])): ?>
                                    <?= htmlspecialchars($user['product_name']); ?>
                                    (₹<?= number_format($user['product_price'], 2); ?>)
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td><?= !empty($user['delivery_type']) ? htmlspecialchars($user['delivery_type']) : '-' ?></td>

                            <td><?= htmlspecialchars($user['pin']) ?></td>

                            <td><?= !empty($user['is_active']) ? 'Yes' : 'No' ?></td>

                            <td><?= date("d M Y H:i", strtotime($user['created_at'])) ?></td>

                            <td>
                                <a href="admin_edit_user.php?user_id=<?= $uid ?>"
                                   class="btn btn-sm btn-warning mb-1">✏️ Edit</a>

                                <form method="post" action="admin_impersonate.php" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= $uid ?>">
                                    <button class="btn btn-sm btn-primary mb-1">Login as</button>
                                </form>

                                <a href="print_bill.php?user_id=<?= $uid ?>" 
                                   class="btn btn-sm btn-info mb-1" 
                                   target="_blank">🧾 Print Bill</a>
                            </td>
                        </tr>

                    <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="14" class="text-center text-muted">No users found.</td></tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center mt-4">

            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?filter=<?= $filter ?>&page=<?= $page - 1 ?>">&laquo; Prev</a>
            </li>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
              <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                <a class="page-link" href="?filter=<?= $filter ?>&page=<?= $p ?>"><?= $p ?></a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
              <a class="page-link" href="?filter=<?= $filter ?>&page=<?= $page + 1 ?>">Next &raquo;</a>
            </li>

          </ul>
        </nav>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
