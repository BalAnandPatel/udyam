<?php
include 'header.php';
include '../config/db.php';
?>

<div class="page-title text-center py-5 text-white" style="background: linear-gradient(45deg, #007bff, #6610f2);">
  <h1>📘 Business Plans</h1>
  <p>Explore entrepreneurship plans, investment ideas, and startup opportunities</p>
</div>

<div class="container py-5">
  <div class="row">
    <?php
    $res = $conn->query("SELECT * FROM business_plans ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $file = $row['file_path'];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
        ?>
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="card shadow-sm border-0 h-100">
            <?php if ($isImage) { ?>
              <a href="<?= htmlspecialchars($file) ?>" target="_blank">
                <img src="<?= htmlspecialchars($file) ?>" class="card-img-top" style="height:220px;object-fit:cover;border-radius:8px 8px 0 0;" alt="<?= htmlspecialchars($row['title']) ?>">
              </a>
            <?php } else { ?>
              <div class="d-flex align-items-center justify-content-center bg-light" style="height:220px;border-radius:8px 8px 0 0;">
                <a href="<?= htmlspecialchars($file) ?>" target="_blank" class="btn btn-outline-primary">
                  <i class="bi bi-file-earmark-pdf fs-1"></i><br>View PDF
                </a>
              </div>
            <?php } ?>

            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
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
      echo "<div class='col-12 text-center'><p class='text-muted'>No business plans available yet.</p></div>";
    }
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>
