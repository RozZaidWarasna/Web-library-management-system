<?php
include 'header.php';
require 'db.php';

$id  = intval($_GET['id']);
$pub = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM publisher WHERE publisher_id=$id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n   = $_POST['name'];
    $ci  = $_POST['city'];
    $cty = $_POST['country'];
    $ct  = $_POST['contact_info'];
    mysqli_query($conn,"UPDATE publisher SET name='$n',city='$ci',country='$cty',contact_info='$ct' WHERE publisher_id=$id");
    header("Location: dashboard.php?table=publisher");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Edit Publisher</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=publisher" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Edit Publisher</div>
        <div class="form-card-subtitle">Update publisher information</div>

        <form method="post">
          <div class="form-group">
            <label>Publisher Name</label>
            <input name="name" value="<?= htmlspecialchars($pub['name']) ?>" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>City</label>
              <input name="city" value="<?= htmlspecialchars($pub['city']) ?>">
            </div>
            <div class="form-group">
              <label>Country</label>
              <input name="country" value="<?= htmlspecialchars($pub['country']) ?>">
            </div>
          </div>

          <div class="form-group">
            <label>Contact Info</label>
            <input name="contact_info" value="<?= htmlspecialchars($pub['contact_info']) ?>">
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php?table=publisher" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
