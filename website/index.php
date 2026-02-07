<?php include 'header.php'; ?>

<section class="hero py-5">
  <div class="container py-4">
    <div class="row align-items-start">
      <!-- LEFT SECTION -->
      <div class="col-lg-7">
        <h1 class="display-5 fw-bold">Your Complete Entrepreneurship Growth Partner</h1>
        <p class="lead mt-3">
          Start your business confidently with expert guidance on machinery, marketing,
          documentation, compliance, and MSME loan support — designed for first-time founders.
        </p>
        <div class="mt-3 d-flex gap-2">
          <a href="apply.php" class="btn btn-light btn-lg">Get Guidance</a>
          <a href="services.php" class="btn btn-outline-light btn-lg">Explore Solutions</a>
        </div>
        <div class="mt-3 small opacity-75">
          MUDRA • PMEGP • CGTMSE • MSME Setup • Branding • Digital Ads • Machinery
        </div>
      </div>

      <!-- RIGHT SIDEBAR -->
      <div class="col-lg-5 mt-4 mt-lg-0">
        <!-- Quick Eligibility Check -->
        <div class="bg-white rounded-4 p-4 shadow-soft text-dark mb-4">
          <h5 class="mb-3">Quick Eligibility Check</h5>
          <form action="apply.php" method="get" class="row g-2">
            <div class="col-12">
              <input class="form-control" name="business" placeholder="Business idea / sector" required>
            </div>
            <div class="col-md-6">
              <input class="form-control" name="turnover" placeholder="Expected investment">
            </div>
            <div class="col-md-6">
              <input class="form-control" name="amount" placeholder="Loan needed (₹)">
            </div>
            <div class="col-12 d-grid">
              <button class="btn btn-primary" type="submit">Check Eligibility</button>
            </div>
            <div class="small text-muted mt-1">Takes ~60 seconds. No hard inquiry.</div>
          </form>
        </div>

        <!-- 📰 Latest News Sidebar -->
        <?php
        include "../config/db.php";
        $news_result = $conn->query("SELECT title, created_at FROM news ORDER BY created_at DESC LIMIT 10");
        if ($news_result && $news_result->num_rows > 0):
        ?>
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-megaphone-fill"></i> Latest News
          </div>
          <div class="card-body p-0" style="height:250px; overflow:hidden; position:relative;">
            <div class="news-ticker" onmouseover="pauseTicker()" onmouseout="resumeTicker()">
              <ul class="mb-0">
                <?php while($n = $news_result->fetch_assoc()): ?>
                  <li>
                    <i class="bi bi-dot text-primary"></i>
                    <?= htmlspecialchars($n['title']) ?><br>
                    <small class="text-muted"><?= date('d M Y', strtotime($n['created_at'])) ?></small>
                  </li>
                <?php endwhile; ?>
              </ul>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section class="py-5">
  <div class="container">
    <div class="section-title text-center">
      <h2 class="fw-bold">Our Entrepreneurship Solutions</h2>
      <p class="text-muted">End-to-end support for launching and growing your business.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 border rounded-4 feature h-100">
          <i class="bi bi-journal-text"></i>
          <h5 class="mt-3">Business Setup & Compliance</h5>
          <p class="text-muted mb-0">
            Registration, licenses, GST, UDYAM, bank account assistance — everything required to legally start your unit.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 border rounded-4 feature h-100">
          <i class="bi bi-gear-wide-connected"></i>
          <h5 class="mt-3">Machinery & Equipment Guidance</h5>
          <p class="text-muted mb-0">
            Match machines to your business model, vendor sourcing, quotations, training & subsidy eligibility.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 border rounded-4 feature h-100">
          <i class="bi bi-film"></i>
          <h5 class="mt-3">Marketing & Advertisement</h5>
          <p class="text-muted mb-0">
            Brand identity, digital ads, social media setup, promotional creatives, and growth strategy.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 border rounded-4 feature h-100">
          <i class="bi bi-bank"></i>
          <h5 class="mt-3">Loan Process Support</h5>
          <p class="text-muted mb-0">
            MUDRA, PMEGP, Stand-Up India, CGTMSE — file preparation, CMA data, projections, bank follow-up.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 border rounded-4 feature h-100">
          <i class="bi bi-people"></i>
          <h5 class="mt-3">Startup Mentorship</h5>
          <p class="text-muted mb-0">
            1:1 expert calls, weekly sessions, step-by-step implementation roadmaps, and risk planning.
          </p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 border rounded-4 feature h-100">
          <i class="bi bi-rocket"></i>
          <h5 class="mt-3">Business Growth Strategy</h5>
          <p class="text-muted mb-0">
            Scaling plans, customer acquisition methods, cash-flow optimization, and working capital management.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- POPULAR LOAN SCHEMES -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="section-title text-center">
      <h2 class="fw-bold">Popular Loan Schemes</h2>
      <p class="text-muted">Government & bank support for manufacturing and service units</p>
    </div>

    <div class="row g-4">
      <?php
      $schemes = [
        ['MUDRA (Shishu/Kishor/Tarun)','Up to ₹10 lakh for micro-enterprises.','bi-cash-coin'],
        ['PMEGP','Subsidy up to 35% for new manufacturing/service units.','bi-building'],
        ['CGTMSE','Collateral-free credit guarantee for MSME loans.','bi-shield-lock'],
        ['Stand-Up India','Loans for women/SC/ST entrepreneurs.','bi-gender-ambiguous'],
      ];
      foreach($schemes as $s){
        echo '<div class="col-md-3"><div class="p-4 bg-white border rounded-4 h-100 shadow-soft">';
        echo '<i class="bi '.$s[2].' fs-3" style="color:var(--brand)"></i>';
        echo '<h6 class="mt-3">'.$s[0].'</h6><p class="text-muted mb-0">'.$s[1].'</p></div></div>';
      }
      ?>
    </div>

    <div class="text-center mt-4">
      <a href="loans.php" class="btn btn-outline-primary">Explore All Loan Guides</a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
