<?php
include 'header.php';
include '../config/db.php';
?>

<div class="page-title text-center py-5 text-white" style="background: linear-gradient(45deg, #007bff, #6610f2);">
  <h1>🖼️ Gallery</h1>
  <p>Our Journey in Pictures – Explore Highlights and Memories</p>
</div>

<div class="container py-5">
  <div class="row g-4">
    <?php
    $res = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $image = $row['image_path'];
        $caption = $row['caption'];
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <div class="card border-0 shadow-sm h-100">
            <a href="<?= htmlspecialchars($image) ?>" target="_blank">
              <img src="<?= htmlspecialchars($image) ?>" class="card-img-top rounded" alt="<?= htmlspecialchars($caption) ?>" style="height:220px;object-fit:cover;">
            </a>
            <div class="card-body text-center">
              <p class="card-text text-muted small mb-0"><?= htmlspecialchars($caption) ?></p>
            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<div class='col-12 text-center'><p class='text-muted'>No images uploaded yet.</p></div>";
    }
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>
