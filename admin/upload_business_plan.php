<?php
include('includes/header.php');
include('../config/db.php');

// Handle file upload
if (isset($_POST['upload'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $target_dir = "../uploads/business_plans/";

    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $file_path = "";
    if (!empty($_FILES["file"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed types: pdf, jpg, png, jpeg
        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
            }
        } else {
            echo "<script>alert('Invalid file type! Only PDF, JPG, JPEG, PNG allowed.');</script>";
        }
    }

    if ($file_path != "") {
        $sql = "INSERT INTO business_plans (title, description, file_path) VALUES ('$name', '$description', '$file_path')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Business Plan uploaded successfully!');window.location='upload_business_plan.php';</script>";
            exit;
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $q = $conn->query("SELECT file_path FROM business_plans WHERE id = $id");
    $file = "";
    if ($q) {
        $row = $q->fetch_assoc();
        if ($row && isset($row['file_path'])) $file = $row['file_path'];
    }
    if ($file !== "" && file_exists($file)) unlink($file);
    $conn->query("DELETE FROM business_plans WHERE id=$id");
    echo "<script>alert('Business Plan deleted successfully!');window.location='upload_business_plan.php';</script>";
    exit;
}
?>

<h3>📘 Manage Business Plans</h3>
<div class="card mt-3 mb-4">
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Name of Document</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Upload File (PDF or Image)</label>
          <input type="file" name="file" class="form-control" accept=".pdf,image/*" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" name="upload" class="btn btn-primary">Upload</button>
    </form>
  </div>
</div>

<h4 class="mb-3">📋 Uploaded Business Plans</h4>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Preview</th>
      <th>Name</th>
      <th>Description</th>
      <th>Upload Date</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $res = $conn->query("SELECT * FROM business_plans ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $file = $row['file_path'];
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $preview = '';

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $preview = "<img src='$file' width='80' height='60' style='object-fit:cover;border-radius:5px;'>";
            } elseif ($ext == 'pdf') {
                $preview = "<a href='$file' target='_blank' class='btn btn-outline-primary btn-sm'><i class='bi bi-file-earmark-pdf'></i> View PDF</a>";
            } else {
                $preview = "N/A";
            }

            echo "<tr>
              <td>{$row['id']}</td>
              <td>$preview</td>
              <td>".htmlspecialchars($row['title'])."</td>
              <td>".nl2br(htmlspecialchars($row['description']))."</td>
              <td>{$row['upload_date']}</td>
              <td>
                <a href='upload_business_plan.php?delete={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this?\");' class='btn btn-danger btn-sm'>Delete</a>
              </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No business plans uploaded yet.</td></tr>";
    }
    ?>
  </tbody>
</table>

<?php include('includes/footer.php'); ?>
