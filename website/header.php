<?php
include "../config/db.php";
// Set timezone globally for the project
date_default_timezone_set('Asia/Kolkata');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Udyami Bazar | Entrepreneurship & Loan Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root { --brand: #0d6efd; }
    body { font-family: 'Segoe UI', Arial, sans-serif; }

    .navbar-brand img {
      width: 40px;
      height: 40px;
      object-fit: contain;
      margin-right: 8px;
    }

    .slider-img {
      height: 360px;
      object-fit: cover;
      width: 100%;
    }

    @media (max-width: 768px) {
      .slider-img { height: 200px; }
      .carousel-caption h5 { font-size: 16px; }
      .carousel-caption p { font-size: 13px; }
    }

    @media (max-width: 480px) {
      .slider-img { height: 160px; }
    }

    .carousel-caption {
      background: rgba(0,0,0,0.45);
      border-radius: 6px;
      padding: 6px 12px;
    }
.news-ticker {
  height: 250px;
  overflow: hidden;
  position: relative;
  padding: 10px 15px;
}

.news-ticker ul {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  list-style: none;
  padding: 0;
  margin: 0;
  animation: scrollNews 20s linear infinite;
}

.news-ticker li {
  padding: 5px 0;
  font-size: 14px;
  font-weight: 500;
  color: #212529;
  border-bottom: 1px dashed #ddd;
}

.news-ticker li:last-child { border-bottom: none; }

@keyframes scrollNews {
  0% { top: 0; }
  100% { top: -360px; }
}
.team-card {
  transition: all 0.3s ease;
  border-radius: 16px;
}
.team-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.team-img {
  height: 300px;
  object-fit: cover;
  border-top-left-radius: 16px;
  border-top-right-radius: 16px;
}
.slider-img {
    width: 100%;
    height: 360px; /* fixed height */
    object-fit: cover; /* auto crop and fit correctly */
    object-position: center; /* center the image */
    border-radius: 0;
}

    
  </style>
</head>

<body>

<!-- 🌐 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">

    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
      <img src="../images/logo.jpg" alt="Logo">
      <span>Udyami Bazar</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navMenu" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">

        <?php
          function active($page) {
            echo basename($_SERVER['PHP_SELF']) === $page ? ' active' : '';
          }
        ?>

        <li class="nav-item"><a class="nav-link<?php active('index.php'); ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link<?php active('about.php'); ?>" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link<?php active('about.php'); ?>" href="team.php">Team</a></li>
        <li class="nav-item"><a class="nav-link<?php active('services.php'); ?>" href="services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link<?php active('faq.php'); ?>" href="faq.php">FAQ</a></li>
        <li class="nav-item"><a class="nav-link<?php active('contact.php'); ?>" href="contact.php">Contact</a></li>

        <!-- ✅ Extracted Menu (No Dropdown) -->

        <li class="nav-item"><a class="nav-link<?php active('business_plans.php'); ?>" href="business_plans.php">Business Plans</a></li>
        <li class="nav-item"><a class="nav-link<?php active('legal.php'); ?>" href="legal.php">Legal Documents</a></li>
        <li class="nav-item"><a class="nav-link<?php active('gallery.php'); ?>" href="gallery.php">Gallery</a></li>

        <li class="nav-item"><a class="nav-link" href="https://udyamibazar.com/login.php">Career Club</a></li>
      </ul>
    </div>

  </div>
</nav>

<!-- 🖼️ GLOBAL RESPONSIVE SLIDER -->
<div id="mainSlider" class="carousel slide shadow-sm" data-bs-ride="carousel">
  <div class="carousel-inner">

  <?php
    $slider = $conn->query("SELECT * FROM homepage_slider WHERE status = 1 ORDER BY id DESC LIMIT 10");
    $active = "active";

    while($row = $slider->fetch_assoc()):
  ?>
    <div class="carousel-item <?= $active ?>">
      <img src="../uploads/slider/<?= $row['image'] ?>" class="slider-img">
      <div class="carousel-caption d-none d-md-block">
        <h5><?= htmlspecialchars($row['title']) ?></h5>
        <p><?= htmlspecialchars($row['subtitle']) ?></p>
      </div>
    </div>
  <?php
      $active = ""; 
    endwhile;
  ?>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const myCarousel = document.querySelector('#mainSlider');
  const carousel = new bootstrap.Carousel(myCarousel, {
    interval: 3000,
    ride: 'carousel'
  });
</script>