<?php include 'header.php'; ?>
<div class="container my-4">
  <h2 class="fw-bold">Apply for Consultation</h2>
  <p class="text-muted">Fill this form and our team will call you within 24 hours.</p>

  <form class="row g-3 shadow-soft p-4 rounded-4 bg-white">
    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Mobile</label>
      <input class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">City</label>
      <input class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">Business Type</label>
      <input class="form-control" placeholder="e.g., retail, services, manufacturing">
    </div>
    <div class="col-md-6">
      <label class="form-label">Amount Needed (₹)</label>
      <input class="form-control">
    </div>
    <div class="col-12">
      <label class="form-label">Notes</label>
      <textarea class="form-control" rows="4" placeholder="Tell us about your plan"></textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Submit Application</button>
    </div>
    <div class="small text-muted">By submitting, you agree to our terms & privacy.</div>
  </form>
</div>
<?php include 'footer.php'; ?>
