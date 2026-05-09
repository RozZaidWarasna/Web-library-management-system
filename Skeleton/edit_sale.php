<?php
include 'header.php';
require 'db.php';

$id   = intval($_GET['id']);
$sale = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM sale WHERE sale_id=$id"));

$borrowers = mysqli_query($conn,"SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name");
$books     = mysqli_query($conn,"SELECT book_id, title FROM book ORDER BY title");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bi = $_POST['book_id'];
    $bo = $_POST['borrower_id'];
    $sd = $_POST['sale_date'];
    $sp = $_POST['sale_price'];
    mysqli_query($conn,"UPDATE sale SET book_id='$bi',borrower_id='$bo',sale_date='$sd',sale_price='$sp' WHERE sale_id=$id");
    header("Location: dashboard.php?table=sale");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Edit Sale</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=sale" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Edit Sale</div>
        <div class="form-card-subtitle">Update sale transaction details</div>

        <form method="post">
          <div class="form-group">
            <label>Book</label>
            <select name="book_id" required>
              <option value="">Select Book…</option>
              <?php while($r=mysqli_fetch_assoc($books)): ?>
                <option value="<?= $r['book_id'] ?>" <?= $r['book_id']==$sale['book_id']?'selected':'' ?>><?= htmlspecialchars($r['title']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Buyer (Borrower)</label>
            <select name="borrower_id" required>
              <option value="">Select Buyer…</option>
              <?php while($r=mysqli_fetch_assoc($borrowers)): ?>
                <option value="<?= $r['borrower_id'] ?>" <?= $r['borrower_id']==$sale['borrower_id']?'selected':'' ?>><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Sale Date</label>
              <input type="date" name="sale_date" value="<?= htmlspecialchars($sale['sale_date']) ?>" required>
            </div>
            <div class="form-group">
              <label>Sale Price (USD)</label>
              <input type="number" step="0.01" name="sale_price" value="<?= htmlspecialchars($sale['sale_price']) ?>" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php?table=sale" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
