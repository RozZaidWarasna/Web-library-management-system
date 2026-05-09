<?php
include 'header.php';
require 'db.php';

$id = intval($_GET['id']);
$ln = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan WHERE loan_id=$id"));

$borrowers = mysqli_query($conn,"SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name");
$books     = mysqli_query($conn,"SELECT book_id, title FROM book ORDER BY title");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $b  = $_POST['borrower_id'];
    $bk = $_POST['book_id'];
    $ld = $_POST['loan_date'];
    $rd = $_POST['return_date'];
    $rdVal = $rd ? "'$rd'" : 'NULL';
    mysqli_query($conn,"UPDATE loan SET borrower_id='$b',book_id='$bk',loan_date='$ld',return_date=$rdVal WHERE loan_id=$id");
    header("Location: dashboard.php?table=loan");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Edit Loan</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=loan" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Edit Loan</div>
        <div class="form-card-subtitle">Update loan details</div>

        <form method="post">
          <div class="form-group">
            <label>Borrower</label>
            <select name="borrower_id" required>
              <option value="">Select Borrower…</option>
              <?php while($r=mysqli_fetch_assoc($borrowers)): ?>
                <option value="<?= $r['borrower_id'] ?>" <?= $r['borrower_id']==$ln['borrower_id']?'selected':'' ?>>
                  <?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Book</label>
            <select name="book_id" required>
              <option value="">Select Book…</option>
              <?php while($r=mysqli_fetch_assoc($books)): ?>
                <option value="<?= $r['book_id'] ?>" <?= $r['book_id']==$ln['book_id']?'selected':'' ?>>
                  <?= htmlspecialchars($r['title']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Loan Date</label>
              <input type="date" name="loan_date" value="<?= htmlspecialchars($ln['loan_date']) ?>" required>
            </div>
            <div class="form-group">
              <label>Return Date</label>
              <input type="date" name="return_date" value="<?= htmlspecialchars($ln['return_date']) ?>">
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php?table=loan" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
