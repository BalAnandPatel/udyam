<?php include 'header.php'; ?>
<div class="container my-4">
  <h2 class="fw-bold">Frequently Asked Questions</h2>
  <div class="accordion mt-3" id="faq">
    <?php
      $faqs = [
        ['Who is eligible for MSME loans?', 'Individuals, proprietors, partnerships, LLPs & companies with viable business plans and KYC.'],
        ['Do I need collateral?', 'Many schemes like MUDRA or CGTMSE are collateral-free (subject to lender policy).'],
        ['How long does approval take?', 'Typically 7–30 working days depending on scheme and documents.'],
        ['What documents are required?', 'KYC, UDYAM, bank statements, ITR/GST, business plan & projections (CMA).']
      ];
      $i=0; foreach($faqs as $f): $i++; ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="h<?=$i?>">
          <button class="accordion-button <?= $i>1?'collapsed':'' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#c<?=$i?>">
            <?= htmlspecialchars($f[0]) ?>
          </button>
        </h2>
        <div id="c<?=$i?>" class="accordion-collapse collapse <?= $i==1?'show':'' ?>" data-bs-parent="#faq">
          <div class="accordion-body"><?= htmlspecialchars($f[1]) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
