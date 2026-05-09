<?php
include 'header.php';
require 'db.php';

$id     = intval($_GET['id']);
$res    = mysqli_query($conn,"SELECT * FROM author WHERE author_id=$id");
$author = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fn  = $_POST['first_name'];
    $ln  = $_POST['last_name'];
    $cty = $_POST['country'];
    $bio = $_POST['bio'];
    mysqli_query($conn,"UPDATE author SET first_name='$fn',last_name='$ln',country='$cty',bio='$bio' WHERE author_id=$id");
    header("Location: dashboard.php?table=author");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Edit Author</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=author" class="btn btn-secondary btn-sm">← Back to Authors</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Edit Author</div>
        <div class="form-card-subtitle">Update author information</div>

        <form method="post">
          <div class="form-row">
            <div class="form-group">
              <label>First Name</label>
              <input name="first_name" value="<?= htmlspecialchars($author['first_name']) ?>" required>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input name="last_name" value="<?= htmlspecialchars($author['last_name']) ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Country</label>
            <input name="country" value="<?= htmlspecialchars($author['country']) ?>" required>
          </div>

          <div class="form-group">
            <label>Biography</label>
            <textarea name="bio" rows="5" required><?= htmlspecialchars($author['bio']) ?></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php?table=author" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
