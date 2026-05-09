<?php
include 'header.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fn = $_POST['first_name'];
    $ln = $_POST['last_name'];
    $ti = $_POST['type_id'];
    $ci = $_POST['contact_info'];
    mysqli_query($conn,"INSERT INTO borrower(first_name,last_name,type_id,contact_info) VALUES('$fn','$ln','$ti','$ci')");
    header("Location: dashboard.php?table=borrower");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Add Borrower</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=borrower" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Add New Borrower</div>
        <div class="form-card-subtitle">Register a new library member</div>

        <form method="post">
          <div class="form-row">
            <div class="form-group">
              <label>First Name</label>
              <input name="first_name" placeholder="First name" required>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input name="last_name" placeholder="Last name" required>
            </div>
          </div>

          <div class="form-group">
            <label>Member Type ID</label>
            <input name="type_id" placeholder="e.g. 1, 2, 3" required>
          </div>

          <div class="form-group">
            <label>Contact Info</label>
            <input name="contact_info" placeholder="Phone or email">
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Borrower</button>
            <a href="dashboard.php?table=borrower" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
