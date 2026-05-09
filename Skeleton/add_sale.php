<?php
include 'header.php';
require 'db.php';

$borrowers = mysqli_query($conn,"SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name");
$books     = mysqli_query($conn,"SELECT book_id, title FROM book ORDER BY title");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bi = $_POST['book_id'];
    $bo = $_POST['borrower_id'];
    $sd = $_POST['sale_date'];
    $sp = $_POST['sale_price'];
    mysqli_query($conn,"INSERT INTO sale(book_id,borrower_id,sale_date,sale_price) VALUES('$bi','$bo','$sd','$sp')");
    header("Location: dashboard.php?table=sale");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Add Sale</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=sale" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Add New Sale</div>
        <div class="form-card-subtitle">Record a book sale transaction</div>

        <form method="post">
          <div class="form-group">
            <label>Book</label>
            <select name="book_id" required>
              <option value="">Select Book…</option>
              <?php while($r=mysqli_fetch_assoc($books)): ?>
                <option value="<?= $r['book_id'] ?>"><?= htmlspecialchars($r['title']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Buyer (Borrower)</label>
            <select name="borrower_id" required>
              <option value="">Select Buyer…</option>
              <?php while($r=mysqli_fetch_assoc($borrowers)): ?>
                <option value="<?= $r['borrower_id'] ?>"><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Sale Date</label>
              <input type="date" name="sale_date" required>
            </div>
            <div class="form-group">
              <label>Sale Price (USD)</label>
              <input type="number" step="0.01" name="sale_price" placeholder="0.00" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Sale</button>
            <a href="dashboard.php?table=sale" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
