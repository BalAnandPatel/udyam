<?php
include 'header.php';
include '../config/db.php';
?>

<div class="page-title text-center py-5 text-white" style="background: linear-gradient(45deg, #007bff, #6610f2);">
  <h1>📜 Legal Documents</h1>
  <p>All official company documents and important information</p>
</div>

<div class="container py-5">
  <div class="row">
    <?php
    $res = $conn->query("SELECT * FROM legal_documents ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $photo = !empty($row['photo']) ? $row['photo'] : 'https://via.placeholder.com/300x200?text=No+Image';
        ?>
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="card shadow-sm border-0 h-100">
            <a href="<?= htmlspecialchars($photo) ?>" target="_blank">
              <img src="<?= htmlspecialchars($photo) ?>" class="card-img-top" style="height:220px;object-fit:cover;border-radius:8px 8px 0 0;" alt="<?= htmlspecialchars($row['name']) ?>">
            </a>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
              <p class="card-text" style="font-size:14px; color:#555;">
                <?= nl2br(htmlspecialchars($row['description'])) ?>
              </p>
              <small class="text-muted">Uploaded on <?= date("d M, Y", strtotime($row['upload_date'])) ?></small>
            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<div class='col-12 text-center'><p class='text-muted'>No legal documents available yet.</p></div>";
    }
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>
