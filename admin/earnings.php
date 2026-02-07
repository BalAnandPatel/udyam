<?php
include 'includes/header.php';
require 'config/db.php';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total users
$count_sql = "SELECT COUNT(*) AS total FROM users";
$count_res = mysqli_query($conn, $count_sql);
$total_rows = mysqli_fetch_assoc($count_res)['total'];
$total_pages = ceil($total_rows / $limit);

// Total earnings per user
$sql = "SELECT u.id AS user_id, u.name AS user_name, COALESCE(SUM(e.amount),0) AS total_earning
        FROM users u
        LEFT JOIN earnings e ON e.user_id = u.id
        GROUP BY u.id
        ORDER BY total_earning DESC
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<div class="row">
  <div class="col-md-12">
    <h3 class="mb-4">All Earnings (Summary)</h3>

    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>User ID</th>
            <th>User Name</th>
            <th>Total Earnings (₹)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php if($result && mysqli_num_rows($result) > 0): ?>
          <?php $i = $offset + 1; while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= (int)$row['user_id'] ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td>₹<?= number_format($row['total_earning'], 2) ?></td>
            <td>
              <button class="btn btn-sm btn-info view-details"
                      data-id="<?= (int)$row['user_id'] ?>"
                      data-name="<?= htmlspecialchars($row['user_name']) ?>">
                📊 View Breakdown
              </button>
            </td>
          </tr>
          <?php $i++; endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No earnings found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <nav>
      <ul class="pagination">

        <!-- Previous -->
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
        </li>

        <!-- Next -->
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
        </li>

      </ul>
    </nav>

    <hr>
    <h4>User Earning Breakdown</h4>
    <div id="details-container" class="mt-3">
      <p class="text-muted">Click on a user to see detailed earnings (Referral & Level links included).</p>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  $(".view-details").on("click", function(){
    const userId = $(this).data("id");
    const userName = $(this).data("name");
    if (!userId) {
      $("#details-container").html("<div class='alert alert-danger'>User ID missing!</div>");
      return;
    }
    $("#details-container").html(
      "<div class='alert alert-info'>Loading earnings for <b>" + userName + " (ID: " + userId + ")</b>...</div>"
    );
    $.get("fetch_earnings.php", { user_id: userId }, function(html){
      $("#details-container").html("<h5 class='mb-3'>Earnings for " + userName + " (ID: " + userId + ")</h5>" + html);
    });
  });
});
</script>

<?php include 'includes/footer.php'; ?>
