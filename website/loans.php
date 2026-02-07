<?php include 'header.php'; ?>
<div class="container my-4">
  <h2 class="fw-bold">Loan Guides</h2>
  <p class="text-muted">Understand eligibility, documents, timelines & benefits.</p>

  <?php
    $guides = [
      ['MUDRA Loan','Up to ₹10 lakh for micro business: Shishu, Kishor, Tarun.','mudra'],
      ['PMEGP','Credit-linked subsidy for new enterprises under KVIC/KVIB/DIC.','pmegp'],
      ['CGTMSE','Collateral-free guarantee cover on MSME loans.','cgtmse'],
      ['Stand-Up India','Finance for women/SC/ST entrepreneurs to start greenfield enterprise.','standup']
    ];
  ?>

  <div class="row g-4 mt-2">
    <?php foreach($guides as $g): ?>
      <div class="col-md-6">
        <div class="p-4 border rounded-4 h-100">
          <h5 class="mb-1"><?= htmlspecialchars($g[0]) ?></h5>
          <div class="text-muted mb-2"><?= htmlspecialchars($g[1]) ?></div>
          <a class="btn btn-outline-primary btn-sm" href="apply.php?scheme=<?= urlencode($g[2]) ?>">Check Eligibility</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
