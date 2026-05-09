<?php
include 'header.php';
require 'db.php';

$borrowers = mysqli_query($conn,"SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name");
$books     = mysqli_query($conn,"SELECT book_id, title FROM book WHERE available=1 ORDER BY title");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bi = $_POST['borrower_id'];
    $bk = $_POST['book_id'];
    $ld = $_POST['loan_date'];
    $rd = $_POST['return_date'] ?: 'NULL';
    $rdVal = $rd === 'NULL' ? 'NULL' : "'$rd'";
    mysqli_query($conn,"INSERT INTO loan(borrower_id,book_id,loan_date,return_date) VALUES('$bi','$bk','$ld',$rdVal)");
    header("Location: dashboard.php?table=loan");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Add Loan</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=loan" class="btn btn-secondary btn-sm">← Back</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Add New Loan</div>
        <div class="form-card-subtitle">Record a book loan transaction</div>

        <form method="post">
          <div class="form-group">
            <label>Borrower</label>
            <select name="borrower_id" required>
              <option value="">Select Borrower…</option>
              <?php while($r=mysqli_fetch_assoc($borrowers)): ?>
                <option value="<?= $r['borrower_id'] ?>"><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Book</label>
            <select name="book_id" required>
              <option value="">Select Book…</option>
              <?php while($r=mysqli_fetch_assoc($books)): ?>
                <option value="<?= $r['book_id'] ?>"><?= htmlspecialchars($r['title']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Loan Date</label>
              <input type="date" name="loan_date" required>
            </div>
            <div class="form-group">
              <label>Return Date <span style="color:var(--text-muted);font-weight:400;">(optional)</span></label>
              <input type="date" name="return_date">
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Loan</button>
            <a href="dashboard.php?table=loan" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
