<?php
include 'header.php';
require 'db.php';

$id = intval($_GET['id']);
$bk = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM book WHERE book_id=$id"));

$authors_result    = mysqli_query($conn,"SELECT author_id, first_name FROM author");
$publishers_result = mysqli_query($conn,"SELECT publisher_id, name FROM publisher");
$authors = []; while($r=mysqli_fetch_assoc($authors_result)) $authors[]=$r;
$publishers = []; while($r=mysqli_fetch_assoc($publishers_result)) $publishers[]=$r;

$bk_author = mysqli_fetch_assoc(mysqli_query($conn,"SELECT author_id FROM bookauthor WHERE book_id=$id"))['author_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t   = $_POST['title'];
    $c   = $_POST['category'];
    $ty  = $_POST['book_type'];
    $p   = $_POST['original_price'];
    $pub = $_POST['publisher_id'];
    $aut = $_POST['author'];
    $av  = $_POST['available'];

    mysqli_query($conn,"UPDATE book SET title='$t',category='$c',book_type='$ty',original_price='$p',publisher_id='$pub',available='$av' WHERE book_id=$id");
    mysqli_query($conn,"DELETE FROM bookauthor WHERE book_id=$id");
    mysqli_query($conn,"INSERT INTO bookauthor(book_id,author_id) VALUES($id,$aut)");
    header("Location: dashboard.php?table=book");
    exit;
}
?>
    <div class="topbar">
      <div class="topbar-title">Edit Book</div>
      <div class="topbar-actions">
        <a href="dashboard.php?table=book" class="btn btn-secondary btn-sm">← Back to Books</a>
      </div>
    </div>

    <div class="page-content">
      <div class="form-card">
        <div class="form-card-title">Edit Book</div>
        <div class="form-card-subtitle">Update the book details below</div>

        <form method="post">
          <div class="form-row">
            <div class="form-group">
              <label>Title</label>
              <input name="title" value="<?= htmlspecialchars($bk['title']) ?>" required>
            </div>
            <div class="form-group">
              <label>Category</label>
              <input name="category" value="<?= htmlspecialchars($bk['category']) ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Book Type</label>
              <input name="book_type" value="<?= htmlspecialchars($bk['book_type']) ?>" required>
            </div>
            <div class="form-group">
              <label>Price (USD)</label>
              <input name="original_price" type="number" step="0.01" value="<?= htmlspecialchars($bk['original_price']) ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Publisher</label>
              <select name="publisher_id" required>
                <option value="">Select Publisher…</option>
                <?php foreach($publishers as $p): ?>
                  <option value="<?= $p['publisher_id'] ?>" <?= $p['publisher_id']==$bk['publisher_id']?'selected':'' ?>><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Author</label>
              <select name="author" required>
                <option value="">Select Author…</option>
                <?php foreach($authors as $a): ?>
                  <option value="<?= $a['author_id'] ?>" <?= $a['author_id']==$bk_author?'selected':'' ?>><?= htmlspecialchars($a['first_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Availability</label>
            <select name="available" required>
              <option value="">Select…</option>
              <option value="1" <?= $bk['available']==1?'selected':'' ?>>Available</option>
              <option value="0" <?= $bk['available']==0?'selected':'' ?>>Not Available</option>
            </select>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php?table=book" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
