<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'includes/header.php';

// Fetch all vouchers with user name
$sql = "SELECT v.*, u.name as used_by_name 
        FROM vouchers v 
        LEFT JOIN users u ON v.owner_id = u.id
        ORDER BY v.id DESC";
$res = $conn->query($sql);
?>

<div class="container mt-4">
    <h3>🎟 Manage Vouchers / PINs</h3>

    <div class="card shadow-sm p-3">
        <!-- ✅ Status Filter -->
        <div class="mb-3">
            <label class="form-label"><strong>Filter by Status:</strong></label>
            <select id="statusFilter" class="form-select w-auto d-inline-block">
                <option value="">All</option>
                <option value="✅ Used">Used</option>
                <option value="❌ Unused">Unused</option>
            </select>
        </div>

        <table id="voucherTable" class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Amount</th>
                    <th>Issued To (Owner ID)</th>
                    <th>Status</th>
                    <th>Used By (User)</th>
                    <th>Issued At</th>
                    <th>Used At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res && $res->num_rows > 0): ?>
                    <?php while ($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['code']) ?></strong></td>
                            <td>₹<?= number_format($row['amount'], 2) ?></td>
                            <td><?= $row['owner_id'] ?: "-" ?></td>
                            <td><?= ($row['status'] === 'used' ? "✅ Used" : "❌ Unused") ?></td>
                            <td><?= $row['used_by_name'] ?: "-" ?></td>
                            <td><?= date("d M Y H:i", strtotime($row['created_at'])) ?></td>
                            <td><?= $row['used_at'] ? date("d M Y H:i", strtotime($row['used_at'])) : "-" ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ DataTables + Export Buttons -->
<link rel="stylesheet" 
      href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" 
      href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#voucherTable').DataTable({
        "pageLength": 10,  // show 10 per page
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        "order": [[0, "desc"]], // order by ID DESC
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn btn-sm btn-secondary' },
            { extend: 'csv', className: 'btn btn-sm btn-info' },
            { extend: 'excel', className: 'btn btn-sm btn-success' },
            { extend: 'pdf', className: 'btn btn-sm btn-danger' },
            { extend: 'print', className: 'btn btn-sm btn-primary' }
        ]
    });

    // ✅ Filter by status
    $('#statusFilter').on('change', function() {
        table.column(4).search(this.value).draw();
    });
});
</script>

<?php include 'includes/footer.php'; ?>
