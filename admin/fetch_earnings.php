<?php
require 'config/db.php';

if (!isset($_GET['user_id'])) {
  echo "<div class='alert alert-danger'>No user selected.</div>";
  exit;
}
$user_id = (int) $_GET['user_id'];

/**
 * 1) Earnings summary by type (with counts & totals)
 */
$sql = "SELECT e.type, COUNT(*) AS tx_count, SUM(e.amount) AS total_amount
        FROM earnings e
        WHERE e.user_id = {$user_id}
        GROUP BY e.type
        ORDER BY FIELD(e.type,'referral','level','watch') DESC, e.type ASC";
$res = mysqli_query($conn, $sql);

echo "<div class='table-responsive'>";
echo "<table class='table table-sm table-bordered align-middle'>";
echo "<thead class='table-light'>
        <tr>
          <th>Earning Type</th>
          <th>Transactions</th>
          <th>Total (₹)</th>
          <th>Action</th>
        </tr>
      </thead><tbody>";

if ($res && mysqli_num_rows($res) > 0) {
  while ($row = mysqli_fetch_assoc($res)) {
    $type = $row['type'] === '' ? 'other' : $row['type'];
    $label = ucfirst($type);
    $tx = (int)$row['tx_count'];
    $total = (float)$row['total_amount'];

    echo "<tr>
            <td>{$label}</td>
            <td>{$tx}</td>
            <td>₹" . number_format($total, 2) . "</td>
            <td>
              <button class='btn btn-sm btn-outline-primary view-type-details'
                      data-user='{$user_id}' data-type='{$type}'>
                View {$label} Details
              </button>
            </td>
          </tr>";
  }
} else {
  echo "<tr><td colspan='4' class='text-center'>No earnings found.</td></tr>";
}
echo "</tbody></table>";
echo "</div>";

/**
 * 2) Direct members list (who are referrals for this user)
 */
$sql2 = "SELECT id, name, email, created_at
         FROM users
         WHERE sponsor_id = {$user_id}
         ORDER BY created_at ASC";
$res2 = mysqli_query($conn, $sql2);

$total_directs = $res2 ? mysqli_num_rows($res2) : 0;

echo "<h5 class='mt-4'>👥 Direct Members <small class='text-muted'>(Total: {$total_directs})</small></h5>";

echo "<div class='table-responsive'>";
echo "<table class='table table-striped table-hover table-bordered'>";
echo "<thead class='table-dark'>
        <tr>
          <th>#</th>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Join Date</th>
        </tr>
      </thead><tbody>";

if ($res2 && $total_directs > 0) {
  $i = 1;
  while ($row = mysqli_fetch_assoc($res2)) {
    echo "<tr>
            <td>{$i}</td>
            <td>".(int)$row['id']."</td>
            <td>".htmlspecialchars($row['name'])."</td>
            <td>".htmlspecialchars($row['email'])."</td>
            <td>".date('d M Y H:i', strtotime($row['created_at']))."</td>
          </tr>";
    $i++;
  }
} else {
  echo "<tr><td colspan='5' class='text-center'>No direct members found.</td></tr>";
}
echo "</tbody></table>";
echo "</div>";
?>

<!-- 3) Container where per-type details will load -->
<div id="details-type-container" class="mt-3"></div>

<script>
$(function(){
  $(".view-type-details").on("click", function(){
    const userId = $(this).data("user");
    const type = $(this).data("type");
    $("#details-type-container").html(
      "<div class='alert alert-info'>Loading " + (type.charAt(0).toUpperCase()+type.slice(1)) + " details...</div>"
    );
    $.get("fetch_type_details.php", { user_id: userId, type: type }, function(html){
      $("#details-type-container").html(html);
    });
  });
});
</script>
