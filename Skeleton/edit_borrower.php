<?php
include 'header.php';
require 'db.php';

$id       = intval($_GET['id']);
$borrower = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM borrower WHERE borrower_id=$id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fn = $_POST['first_name'];
    $ln = $_POST['last_name'];
    $ti = $_POST['type_id'];
    $ci = $_POST['contact_info'];
    mysqli_query($conn,"UPDATE borrower SET first_name='$fn',last_name='$ln',type_id='$ti',contact_info='$ci' WHERE borrower_id=$id");
    header("Location: dashboard.php?table=borrower");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Edit Borrower</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=borrower" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Edit Borrower</div>
        <div class="form-card-subtitle">Update member details</div>

        <form method="post">
          <div class="form-row">
            <div class="form-group">
              <label>First Name</label>
              <input name="first_name" value="<?= htmlspecialchars($borrower['first_name']) ?>" required>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input name="last_name" value="<?= htmlspecialchars($borrower['last_name']) ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Member Type ID</label>
            <input name="type_id" value="<?= htmlspecialchars($borrower['type_id']) ?>" required>
          </div>

          <div class="form-group">
            <label>Contact Info</label>
            <input name="contact_info" value="<?= htmlspecialchars($borrower['contact_info']) ?>">
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php?table=borrower" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
