<?php include 'header.php'; ?>
<?php include '../config/db.php'; ?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Our Core Team</h2>
      <p class="text-muted">Meet the visionary minds leading Udyami Bazar towards empowering entrepreneurs across India.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php
      $team = $conn->query("SELECT * FROM team ORDER BY id ASC");
      if ($team && $team->num_rows > 0):
        while ($member = $team->fetch_assoc()):
      ?>
        <div class="col-md-4 col-sm-6">
          <div class="card border-0 shadow-sm team-card h-100">
            <img src="../admin/uploads/team/<?= htmlspecialchars($member['photo']) ?>" class="card-img-top team-img" alt="<?= htmlspecialchars($member['name']) ?>">
            <div class="card-body text-center">
              <h5 class="fw-bold mb-0"><?= htmlspecialchars($member['name']) ?></h5>
              <small class="text-primary d-block mb-2"><?= htmlspecialchars($member['designation']) ?></small>
              <p class="text-muted small mb-0"><?= htmlspecialchars($member['bio']) ?></p>
            </div>
          </div>
        </div>
      <?php
        endwhile;
      else:
        echo "<p class='text-center text-muted'>No team members found.</p>";
      endif;
      ?>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
