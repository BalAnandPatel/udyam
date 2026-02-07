<?php
include('includes/header.php');
include('../config/db.php');

/* ─── ADD SLIDE ─────────────────────────────────────────────────────────── */
if (isset($_POST['add'])) {

    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];

    // Ensure folder exists
    $target_dir = "../uploads/slider/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Upload Image
    $image = '';
    if (!empty($_FILES['image']['name'])) {

        $image = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "<script>alert('Image upload failed!');</script>";
        }
    }

    // Limit to 10 slides
    $count = $conn->query("SELECT COUNT(*) AS total FROM homepage_slider")->fetch_assoc()['total'];

    if ($count >= 10) {
        echo "<script>alert('Maximum 10 slides allowed.');</script>";
    } else {
        $conn->query("INSERT INTO homepage_slider (title, subtitle, image)
                      VALUES ('$title', '$subtitle', '$image')");

        echo "<script>window.location='slider.php?added=1';</script>";
        exit;
    }
}

/* ─── DELETE SLIDE ───────────────────────────────────────────────────────── */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Optional: delete file also
    $file = $conn->query("SELECT image FROM homepage_slider WHERE id=$id")->fetch_assoc()['image'];
    if ($file && file_exists("../uploads/slider/" . $file)) {
        unlink("../uploads/slider/" . $file);
    }

    $conn->query("DELETE FROM homepage_slider WHERE id=$id");

    echo "<script>window.location='slider.php?deleted=1';</script>";
    exit;
}

/* ─── TOGGLE ACTIVE/INACTIVE ─────────────────────────────────────────────── */
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];

    $conn->query("UPDATE homepage_slider SET status = NOT status WHERE id=$id");

    echo "<script>window.location='slider.php?updated=1';</script>";
    exit;
}

/* ─── FETCH SLIDES ───────────────────────────────────────────────────────── */
$slides = $conn->query("SELECT * FROM homepage_slider ORDER BY id DESC");
?>

<style>
body { background: #f5f7fa; }

.page-title { font-weight: 700; color: #333; }

.card-custom {
    border-radius: 14px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.slider-thumb {
    width: 140px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ddd;
}

@media (max-width: 576px) {
    .slider-thumb {
        width: 100%;
        height: 140px;
    }
}
</style>

<div class="container mt-4">

    <div class="text-center mb-4">
        <h2 class="page-title">📸 Manage Homepage Slider</h2>
        <p class="text-muted">Add, activate, deactivate and delete slider images (Max 10).</p>
    </div>

    <div class="alert alert-info shadow-sm">
        <strong>Recommended Slider Image Size:</strong> <b>1200 × 450 px</b><br>
        Any size works — best quality comes from 1200×450.
    </div>

<?php 
if (isset($_GET['added'])) echo "<div class='alert alert-success'>Slide added successfully!</div>";
if (isset($_GET['deleted'])) echo "<div class='alert alert-danger'>Slide deleted successfully!</div>";
if (isset($_GET['updated'])) echo "<div class='alert alert-info'>Slide status updated.</div>";
?>

    <!-- Add New Slide -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-plus-circle"></i> Add New Slide</h5>

            <form method="POST" enctype="multipart/form-data" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Slide Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary px-4" name="add">
                        <i class="bi bi-upload"></i> Add Slide
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Slide List -->
    <div class="card card-custom mb-5">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-images"></i> All Slides</h5>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th style="width:150px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                      <?php 
                      $sn = 1; 
                     while($s = $slides->fetch_assoc()): 
                    ?>
<tr>
    <td><?= $sn++; ?></td>

                            <td><img src="../uploads/slider/<?= $s['image'] ?>" class="slider-thumb"></td>

                            <td><?= htmlspecialchars($s['title']) ?></td>

                            <td>
                                <?= $s['status'] 
                                    ? '<span class="badge bg-success">Active</span>' 
                                    : '<span class="badge bg-secondary">Inactive</span>' ?>
                            </td>

                            <td>
                                <a href="?toggle=<?= $s['id'] ?>" class="btn btn-sm btn-warning mb-1">
                                    <?= $s['status'] ? "Deactivate" : "Activate" ?>
                                </a>

                                <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Delete this slide?');"
                                   class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>
