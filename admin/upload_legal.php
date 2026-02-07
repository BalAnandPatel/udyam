<?php
include('includes/header.php');
include('../config/db.php');

// Handle upload
if (isset($_POST['upload'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $target_dir = "../uploads/legal/";

    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $photo_path = "";
    if (!empty($_FILES["photo"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        }
    }

    $sql = "INSERT INTO legal_documents (name, photo, description) VALUES ('$name', '$photo_path', '$description')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Legal document added successfully!');window.location='upload_legal.php';</script>";
        exit;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $q = $conn->query("SELECT photo FROM legal_documents WHERE id = $id");
    $file = "";
    if ($q) {
        $row = $q->fetch_assoc();
        if ($row && isset($row['photo'])) $file = $row['photo'];
    }
    if ($file !== "" && file_exists($file)) unlink($file);
    $conn->query("DELETE FROM legal_documents WHERE id=$id");
    echo "<script>alert('Document deleted successfully!');window.location='upload_legal.php';</script>";
    exit;
}
?>

<h3>📄 Manage Legal Documents</h3>
<div class="card mt-3 mb-4">
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Name of Document</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Photo of Document</label>
          <input type="file" name="photo" class="form-control" accept="image/*" required>
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

<h4 class="mb-3">📋 Uploaded Legal Documents</h4>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Photo</th>
      <th>Name</th>
      <th>Description</th>
      <th>Upload Date</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $res = $conn->query("SELECT * FROM legal_documents ORDER BY id DESC");
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $photo = !empty($row['photo']) ? $row['photo'] : 'https://via.placeholder.com/80x60?text=No+Image';
            echo "<tr>
              <td>{$row['id']}</td>
              <td><img src='{$photo}' width='80' height='60' style='object-fit:cover;border-radius:5px;'></td>
              <td>".htmlspecialchars($row['name'])."</td>
              <td>".nl2br(htmlspecialchars($row['description']))."</td>
              <td>{$row['upload_date']}</td>
              <td>
                <a href='upload_legal.php?delete={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this?\");' class='btn btn-danger btn-sm'>Delete</a>
              </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No legal documents uploaded yet.</td></tr>";
    }
    ?>
  </tbody>
</table>

<?php include('includes/footer.php'); ?>
