<?php
include 'header.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n   = $_POST['name'];
    $ci  = $_POST['city'];
    $cty = $_POST['country'];
    $ct  = $_POST['contact_info'];
    mysqli_query($conn,"INSERT INTO publisher(name,city,country,contact_info) VALUES('$n','$ci','$cty','$ct')");
    header("Location: dashboard.php?table=publisher");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Add Publisher</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=publisher" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Add New Publisher</div>
        <div class="form-card-subtitle">Register a new publishing house</div>

        <form method="post">
          <div class="form-group">
            <label>Publisher Name</label>
            <input name="name" placeholder="Publisher name" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>City</label>
              <input name="city" placeholder="City">
            </div>
            <div class="form-group">
              <label>Country</label>
              <input name="country" placeholder="Country">
            </div>
          </div>

          <div class="form-group">
            <label>Contact Info</label>
            <input name="contact_info" placeholder="Phone, email, or website">
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Publisher</button>
            <a href="dashboard.php?table=publisher" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
