<?php
include '../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
  exit;
}

$msg = "";

// ✅ Handle Add Team Member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_team'])) {
  $name = $_POST['name'];
  $designation = $_POST['designation'];
  $bio = $_POST['bio'];

  $photo = "";
  if (!empty($_FILES['photo']['name'])) {
    $photo = time() . "_" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/team/" . $photo);
  }

  $stmt = $conn->prepare("INSERT INTO team (name, designation, bio, photo) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $name, $designation, $bio, $photo);
  if ($stmt->execute()) {
    $msg = "<div class='alert alert-success'>✅ Team member added successfully!</div>";
  } else {
    $msg = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
  }
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM team WHERE id = $id");
  $msg = "<div class='alert alert-success'>🗑️ Team member deleted successfully!</div>";
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">👥 Manage Core Team</h4>
  </div>

  <?= $msg ?>

  <!-- Add Form -->
  <form method="post" enctype="multipart/form-data" class="card shadow-sm p-4 mb-4 border-0" style="max-width:800px;">
    <h5 class="mb-3">Add Team Member</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input type="text" name="designation" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label">Short Bio</label>
        <textarea name="bio" class="form-control" rows="3" required></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Profile Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*" required>
      </div>
    </div>
    <div class="text-end mt-3">
      <button type="submit" name="add_team" class="btn btn-primary">Add Member</button>
    </div>
  </form>

  <!-- Team List -->
  <h5>Current Team Members</h5>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Photo</th>
          <th>Name</th>
          <th>Designation</th>
          <th>Bio</th>
          <th width="120">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $result = $conn->query("SELECT * FROM team ORDER BY id DESC");
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$row['id']}</td>";
          echo "<td><img src='uploads/team/{$row['photo']}' width='60' class='rounded'></td>";
          echo "<td>".htmlspecialchars($row['name'])."</td>";
          echo "<td>".htmlspecialchars($row['designation'])."</td>";
          echo "<td>".substr(htmlspecialchars($row['bio']),0,80)."...</td>";
          echo "<td>
                  <a href='edit_team.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                  <a href='manage_team.php?delete={$row['id']}' onclick='return confirm(\"Delete this member?\")' class='btn btn-sm btn-danger'>Delete</a>
                </td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='6' class='text-center text-muted'>No team members found.</td></tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
