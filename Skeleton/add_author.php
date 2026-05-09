<?php
include 'header.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fn  = $_POST['first_name'];
    $ln  = $_POST['last_name'];
    $cty = $_POST['country'];
    $bio = $_POST['bio'];
    mysqli_query($conn,"INSERT INTO author(first_name,last_name,country,bio) VALUES('$fn','$ln','$cty','$bio')");
    header("Location: dashboard.php?table=author");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Add Author</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=author" class="btn btn-secondary btn-sm">← Back to Authors</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Add New Author</div>
        <div class="form-card-subtitle">Register a new author in the system</div>

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
            <label>Country</label>
            <input name="country" placeholder="Country of origin" required>
          </div>

          <div class="form-group">
            <label>Biography</label>
            <textarea name="bio" rows="5" placeholder="Brief biography…" required></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Author</button>
            <a href="dashboard.php?table=author" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
