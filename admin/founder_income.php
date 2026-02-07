<?php
include "includes/header.php";
include "../config/db.php";

// GET SELECTED MONTH OR DEFAULT CURRENT MONTH
$selected_month = isset($_GET['month']) ? $_GET['month'] : date("Y-m");

// 1️⃣ Total members who paid for selected month
$q1 = mysqli_query($conn, "
    SELECT COUNT(DISTINCT user_id) AS total_payers
    FROM bills 
    WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month'
");
$totalPayers = mysqli_fetch_assoc($q1)['total_payers'];

// 2️⃣ Total Qualified Founder Members (64 directs)
$q2 = mysqli_query($conn, "
    SELECT COUNT(*) AS total_founders FROM (
        SELECT sponsor_id
        FROM users
        GROUP BY sponsor_id
        HAVING COUNT(*) >= 64
    ) AS founder_members
");
$totalQualified = mysqli_fetch_assoc($q2)['total_founders'];

// 3️⃣ Total income (25% of base_amount for selected month)
$q3 = mysqli_query($conn, "
    SELECT SUM(base_amount) AS total_base 
    FROM bills
    WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month'
");
$totalBase = mysqli_fetch_assoc($q3)['total_base'];
$totalBase = $totalBase ? $totalBase : 0;

$poolIncome = $totalBase * 0.25;

// 4️⃣ Per-member income
$perMember = ($totalQualified > 0) ? ($poolIncome / $totalQualified) : 0;


// ----------------------------------------------------------
// PAGINATION FOR PAYER LIST
// ----------------------------------------------------------

$limit = 10;  
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// Count total payer rows
$qCount = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM bills 
    WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month'
");
$totalRows  = mysqli_fetch_assoc($qCount)['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch payer list for selected month
$qList = mysqli_query($conn, "
    SELECT b.*, u.name 
    FROM bills b
    LEFT JOIN users u ON b.user_id = u.id
    WHERE DATE_FORMAT(b.date, '%Y-%m') = '$selected_month'
    ORDER BY b.id DESC
    LIMIT $limit OFFSET $offset
");
?>

<div class="container mt-4">

    <h3 class="mb-4">🏆 Founder Club Monthly Income Summary</h3>

    <!-- MONTH FILTER -->
    <form method="GET" class="mb-4">
        <label>Select Month:</label>
        <input type="month" name="month" value="<?= $selected_month ?>" class="form-control" style="max-width:200px; display:inline-block;">
        <button class="btn btn-primary btn-sm">Filter</button>
    </form>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Total Members Who Paid (<?= date("M Y", strtotime($selected_month . "-01")) ?>)</h5>
                    <h3 class="text-primary"><?= $totalPayers ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Total Qualified Founder Members</h5>
                    <h3 class="text-success"><?= $totalQualified ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Total Pool Income (25%)</h5>
                    <h3 class="text-warning">₹<?= number_format($poolIncome, 2) ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Per Member Income</h5>
                    <h3 class="text-info">₹<?= number_format($perMember, 2) ?></h3>
                    <small class="text-muted">(Auto calculated)</small>
                </div>
            </div>
        </div>

    </div>


    <!-- ======================= PAYER LIST ======================= -->

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5>Payers List (<?= date("M Y", strtotime($selected_month . "-01")) ?>)</h5>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Base Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $sn = $offset + 1;
                    while ($row = mysqli_fetch_assoc($qList)): 
                    ?>
                    <tr>
                        <td><?= $sn++; ?></td>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>₹<?= number_format($row['base_amount'], 2) ?></td>
                        <td><?= date("d M Y", strtotime($row['date'])) ?></td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if ($totalRows == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No records found for this month.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>


            <!-- PAGINATION (works with month filter) -->
            <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">

                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?month=<?= $selected_month ?>&page=<?= $page-1 ?>">Previous</a>
                    </li>

                    <li class="page-item disabled">
                        <a class="page-link">Page <?= $page ?> of <?= $totalPages ?></a>
                    </li>

                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?month=<?= $selected_month ?>&page=<?= $page+1 ?>">Next</a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php include "includes/footer.php"; ?>
