<?php
include 'header.php';
require 'db.php';

$authors    = mysqli_query($conn,"SELECT author_id, first_name FROM author");
$publishers = mysqli_query($conn,"SELECT publisher_id, name FROM publisher");

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t   = $_POST['title'];
    $c   = $_POST['category'];
    $ty  = $_POST['book_type'];
    $p   = $_POST['original_price'];
    $pub = $_POST['publisher_id'];
    $aut = $_POST['author'];
    $av  = $_POST['available'];

    $sql = "INSERT INTO book(title, category, book_type, original_price, publisher_id, available)
            VALUES('$t','$c','$ty','$p','$pub','$av')";
    mysqli_query($conn,$sql);
    $book_id = mysqli_insert_id($conn);
    mysqli_query($conn,"INSERT INTO bookauthor(book_id, author_id) VALUES($book_id,$aut)");
    header("Location: dashboard.php?table=book");
    exit;
}
?>
    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-title">Add Book</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=book" class="btn btn-secondary btn-sm">← Back to Books</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Add New Book</div>
        <div class="form-card-subtitle">Fill in the details to add a new book to the catalogue</div>

        <form method="post">
          <div class="form-row">
            <div class="form-group">
              <label>Title</label>
              <input name="title" placeholder="Book title" required>
            </div>
            <div class="form-group">
              <label>Category</label>
              <input name="category" placeholder="e.g. Fiction, Science" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Book Type</label>
              <input name="book_type" placeholder="e.g. Hardcover, Digital" required>
            </div>
            <div class="form-group">
              <label>Price (USD)</label>
              <input name="original_price" type="number" step="0.01" placeholder="0.00" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Publisher</label>
              <select name="publisher_id" required>
                <option value="">Select Publisher…</option>
                <?php while($row = mysqli_fetch_assoc($publishers)): ?>
                  <option value="<?= $row['publisher_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Author</label>
              <select name="author" required>
                <option value="">Select Author…</option>
                <?php while($row = mysqli_fetch_assoc($authors)): ?>
                  <option value="<?= $row['author_id'] ?>"><?= htmlspecialchars($row['first_name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Availability</label>
            <select name="available" required>
              <option value="">Select…</option>
              <option value="1">Available</option>
              <option value="0">Not Available</option>
            </select>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Book</button>
            <a href="dashboard.php?table=book" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
