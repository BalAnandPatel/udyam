<?php
include('includes/header.php');
include('../config/db.php');

// Handle upload
if (isset($_POST['upload'])) {
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $target_dir = "../uploads/gallery/";

    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $file_path = "";
    if (!empty($_FILES["image"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
                $sql = "INSERT INTO gallery (image_path, caption) VALUES ('$file_path', '$caption')";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Image uploaded successfully!');window.location='manage_gallery.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Failed to upload image!');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type! Only JPG, JPEG, PNG allowed.');</script>";
        }
    } else {
        echo "<script>alert('Please select an image file.');</script>";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $q = $conn->query("SELECT image_path FROM gallery WHERE id=$id");
    $file = "";
    if ($q && $row = $q->fetch_assoc()) {
        $file = $row['image_path'];
    }
    if ($file !== "" && file_exists($file)) unlink($file);
    $conn->query("DELETE FROM gallery WHERE id=$id");
    echo "<script>alert('Image deleted successfully!');window.location='manage_gallery.php';</script>";
    exit;
}
?>

<h3>🖼️ Manage Gallery</h3>
<div class="card mt-3 mb-4">
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Caption / Description</label>
          <input type="text" name="caption" class="form-control" placeholder="Enter image caption..." required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Select Image</label>
          <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
      </div>
      <button type="submit" name="upload" class="btn btn-primary">Upload</button>
    </form>
  </div>
</div>

<h4 class="mb-3">📸 Uploaded Gallery Images</h4>
<div class="row">
  <?php
  $res = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
  if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
          $img = $row['image_path'];
          $caption = htmlspecialchars($row['caption']);
          echo "
          <div class='col-md-3 mb-4'>
            <div class='card border-0 shadow-sm'>
              <img src='$img' class='card-img-top' style='height:180px;object-fit:cover;border-radius:8px 8px 0 0;'>
              <div class='card-body text-center'>
                <p class='card-text small mb-2'>$caption</p>
                <a href='manage_gallery.php?delete={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this image?\");' class='btn btn-danger btn-sm'>Delete</a>
              </div>
            </div>
          </div>
          ";
      }
  } else {
      echo "<div class='col-12 text-center'><p class='text-muted'>No images uploaded yet.</p></div>";
  }
  ?>
</div>

<?php include('includes/footer.php'); ?>
