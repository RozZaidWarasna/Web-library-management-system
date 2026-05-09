<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$username = $_SESSION['username'];
$role     = $_SESSION['role'];

$mode          = $_GET['mode']  ?? '';
$r             = $_GET['r']     ?? '';
$selectedTable = $_GET['table'] ?? $_SESSION['last_table'] ?? '';
if ($selectedTable) $_SESSION['last_table'] = $selectedTable;
$search = $_GET['search'] ?? '';

function fetchAll($conn, $sql) {
    $res  = mysqli_query($conn, $sql);
    if (!$res) return [];
    $rows = [];
    while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
    return $rows;
}
function esc($conn,$v){ return mysqli_real_escape_string($conn,(string)$v); }

// Page titles per table
$tableTitles = [
    'book'      => ['📚','Books'],
    'author'    => ['✍️','Authors'],
    'publisher' => ['🏢','Publishers'],
    'borrower'  => ['👤','Borrowers'],
    'loan'      => ['🔗','Loans'],
    'sale'      => ['💰','Sales'],
];

$pageTitle = 'Dashboard';
if ($mode==='reports') $pageTitle = 'Reports';
elseif ($mode==='about') $pageTitle = 'About';
elseif ($selectedTable && isset($tableTitles[$selectedTable]))
    $pageTitle = $tableTitles[$selectedTable][1];

// --- REPORT DEFINITIONS ---
$reports = [
  'total_value'      => ['💵','Total Book Value',      'Sum of all book prices'],
  'books_by_author'  => ['✍️','Books by Author',       'Filter books per author'],
  'borrower_books'   => ['📦','Borrower Activity',     'Loans & sales per borrower'],
  'current_loans'    => ['🔗','Current Loans',         'Unreturned loans'],
  'books_country'    => ['🌍','Books by Country',      'Filter by publisher country'],
  'never_borrowed'   => ['😴','Inactive Borrowers',    'Never borrowed or bought'],
  'multiple_authors' => ['👥','Multi-Author Books',    'Books with 2+ authors'],
  'sold_books'       => ['🛒','Sold Books',            'All sales records'],
  'available_books'  => ['✅','Available Books',       'Ready for borrowing'],
  'borrower_history' => ['📜','Loan History',          'Full history per borrower'],
];
?>
<?php include 'header.php'; ?>

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-title"><?= $pageTitle ?></div>
      <div class="topbar-actions">
        <?php if ($selectedTable && !$mode && $role==='admin'): ?>
          <a href="add_<?= $selectedTable ?>.php" class="btn btn-primary btn-sm">
            + Add <?= ucfirst($selectedTable) ?>
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">

<?php /* =========== ABOUT =========== */ ?>
<?php if ($mode==='about'): ?>
  <div class="card" style="max-width:700px;">
    <div class="card-header">
      <span class="card-title">About this System</span>
    </div>
    <div class="card-body">
      <p style="color:var(--text-secondary);margin-bottom:24px;">
        The Library Management System is a comprehensive platform for managing all aspects of
        a modern library — from cataloguing books and tracking authors to processing loans and
        generating insightful reports.
      </p>
      <div class="about-grid">
        <?php foreach([
          ['📚','Books & Catalogue','Manage your full book inventory with categories, types, and pricing.'],
          ['✍️','Authors & Publishers','Maintain detailed records of authors and publishing houses.'],
          ['👤','Borrower Management','Track members, their types, and contact information.'],
          ['🔗','Loan Tracking','Monitor active loans, due dates, and return status.'],
          ['💰','Sales Records','Log book purchases and sale transactions.'],
          ['📊','Analytics & Reports','10 built-in reports for data-driven decisions.'],
        ] as [$icon,$t,$d]): ?>
          <div class="about-feature">
            <div class="about-feature-icon"><?= $icon ?></div>
            <h4><?= $t ?></h4>
            <p><?= $d ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

<?php /* =========== REPORTS =========== */ ?>
<?php elseif ($mode==='reports'): ?>
  <!-- Report picker -->
  <div class="report-grid">
    <?php foreach($reports as $key=>[$icon,$label,$desc]): ?>
      <a href="dashboard.php?mode=reports&r=<?= $key ?>"
         class="report-card <?= $r===$key?'active':'' ?>">
        <div class="report-card-icon"><?= $icon ?></div>
        <div class="report-card-label"><?= $label ?></div>
        <div class="report-card-desc"><?= $desc ?></div>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($r): ?>
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <?= $reports[$r][0] ?? '' ?> <?= $reports[$r][1] ?? htmlspecialchars($r) ?>
      </span>
    </div>
    <div class="card-body">
    <?php
    switch($r) {

      case 'total_value':
        $rows = fetchAll($conn,"SELECT SUM(original_price) AS total FROM book");
        $total = number_format($rows[0]['total']??0, 2);
        echo "<div class='stat-card' style='display:inline-flex;'>
                <div class='stat-icon amber'>💵</div>
                <div class='stat-info'>
                  <div class='stat-value'>\${$total}</div>
                  <div class='stat-label'>Total collection value</div>
                </div>
              </div>";
        break;

      case 'books_by_author':
        $author_id = $_GET['author_id'] ?? '';
        if(!$author_id){
          $authors = fetchAll($conn,"SELECT author_id, first_name FROM author ORDER BY first_name");
          echo '<form method="get" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                  <input type="hidden" name="mode" value="reports">
                  <input type="hidden" name="r" value="books_by_author">
                  <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                    <label>Select Author</label>
                    <select name="author_id">';
          foreach($authors as $a) echo '<option value="'.$a['author_id'].'">'.htmlspecialchars($a['first_name']).'</option>';
          echo '    </select></div>
                  <button type="submit" class="btn btn-primary">View Books</button>
                </form>';
        } else {
          $author_id = intval($author_id);
          $books = fetchAll($conn,"SELECT b.title, b.category, b.original_price
                                   FROM book b
                                   JOIN bookauthor ba ON b.book_id = ba.book_id
                                   WHERE ba.author_id = $author_id");
          if($books){
            echo '<div class="table-wrapper"><table><thead><tr><th>Title</th><th>Category</th><th>Price</th></tr></thead><tbody>';
            foreach($books as $b) echo "<tr><td>".htmlspecialchars($b['title'])."</td><td>".htmlspecialchars($b['category'])."</td><td>$".htmlspecialchars($b['original_price'])."</td></tr>";
            echo '</tbody></table></div>';
          } else echo "<div class='empty-state'><div class='empty-state-icon'>📭</div><h3>No books found</h3><p>This author has no books in the system.</p></div>";
        }
        break;

      case 'borrower_books':
        $borrower_id = $_GET['borrower_id'] ?? '';
        if(!$borrower_id){
          $borrowers = fetchAll($conn,"SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name");
          echo '<form method="get" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                  <input type="hidden" name="mode" value="reports">
                  <input type="hidden" name="r" value="borrower_books">
                  <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                    <label>Select Borrower</label>
                    <select name="borrower_id">';
          foreach($borrowers as $b){ $label=htmlspecialchars($b['first_name'].' '.$b['last_name']); echo '<option value="'.$b['borrower_id'].'">'.$label.'</option>'; }
          echo '    </select></div>
                  <button type="submit" class="btn btn-primary">View Activity</button>
                </form>';
        } else {
          $bid = intval($borrower_id);
          $books = fetchAll($conn,"SELECT b.title, l.loan_date, l.return_date, s.sale_date, s.sale_price
                                   FROM book b
                                   LEFT JOIN loan l ON b.book_id = l.book_id AND l.borrower_id = $bid
                                   LEFT JOIN sale s ON b.book_id = s.book_id AND s.borrower_id = $bid
                                   WHERE l.borrower_id=$bid OR s.borrower_id=$bid");
          if($books){
            echo '<div class="table-wrapper"><table><thead><tr><th>Book</th><th>Loan Date</th><th>Return Date</th><th>Sale Date</th><th>Sale Price</th></tr></thead><tbody>';
            foreach($books as $b){
              $ret = $b['return_date'] ? htmlspecialchars($b['return_date']) : '<span class="badge badge-amber">Pending</span>';
              echo "<tr><td>".htmlspecialchars($b['title'])."</td><td>".htmlspecialchars($b['loan_date'])."</td><td>$ret</td><td>".htmlspecialchars($b['sale_date'])."</td><td>".htmlspecialchars($b['sale_price'])."</td></tr>";
            }
            echo '</tbody></table></div>';
          } else echo "<div class='empty-state'><div class='empty-state-icon'>📭</div><h3>No activity</h3><p>This borrower has no loans or purchases.</p></div>";
        }
        break;

      case 'current_loans':
        $rows = fetchAll($conn,"SELECT b.title, l.loan_date, l.return_date, br.first_name AS borrower
                                 FROM loan l
                                 JOIN book b ON b.book_id = l.book_id
                                 LEFT JOIN borrower br ON br.borrower_id = l.borrower_id
                                 WHERE l.return_date IS NULL");
        if($rows){
          echo '<div class="table-wrapper"><table><thead><tr><th>Book</th><th>Borrower</th><th>Loan Date</th><th>Status</th></tr></thead><tbody>';
          foreach($rows as $r2) echo "<tr><td>".htmlspecialchars($r2['title'])."</td><td>".htmlspecialchars($r2['borrower'])."</td><td>".htmlspecialchars($r2['loan_date'])."</td><td><span class='badge badge-amber'>Active</span></td></tr>";
          echo '</tbody></table></div>';
        } else echo "<div class='empty-state'><div class='empty-state-icon'>✅</div><h3>No active loans</h3><p>All books have been returned.</p></div>";
        break;

      case 'books_country':
        $country = $_GET['country'] ?? '';
        if(!$country){
          $countries = fetchAll($conn,"SELECT DISTINCT country FROM publisher ORDER BY country");
          echo '<form method="get" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                  <input type="hidden" name="mode" value="reports">
                  <input type="hidden" name="r" value="books_country">
                  <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                    <label>Select Country</label>
                    <select name="country">';
          foreach($countries as $c) echo '<option value="'.htmlspecialchars($c['country']).'">'.htmlspecialchars($c['country']).'</option>';
          echo '    </select></div>
                  <button type="submit" class="btn btn-primary">View Books</button>
                </form>';
        } else {
          $country = esc($conn,$country);
          $rows = fetchAll($conn,"SELECT b.title, p.name AS publisher FROM book b JOIN publisher p ON b.publisher_id = p.publisher_id WHERE p.country='$country'");
          if($rows){
            echo '<div class="table-wrapper"><table><thead><tr><th>Book</th><th>Publisher</th></tr></thead><tbody>';
            foreach($rows as $b) echo "<tr><td>".htmlspecialchars($b['title'])."</td><td>".htmlspecialchars($b['publisher'])."</td></tr>";
            echo '</tbody></table></div>';
          } else echo "<div class='empty-state'><div class='empty-state-icon'>🌍</div><h3>No books found</h3><p>No books from publishers in this country.</p></div>";
        }
        break;

      case 'never_borrowed':
        $rows = fetchAll($conn,"SELECT br.* FROM borrower br LEFT JOIN loan l ON br.borrower_id=l.borrower_id LEFT JOIN sale s ON br.borrower_id=s.borrower_id WHERE l.loan_id IS NULL AND s.sale_id IS NULL");
        if($rows){
          echo '<div class="table-wrapper"><table><thead><tr>';
          foreach(array_keys($rows[0]) as $col) echo "<th>".htmlspecialchars($col)."</th>";
          echo '</tr></thead><tbody>';
          foreach($rows as $b){ echo '<tr>'; foreach($b as $v) echo "<td>".htmlspecialchars($v)."</td>"; echo '</tr>'; }
          echo '</tbody></table></div>';
        } else echo "<div class='empty-state'><div class='empty-state-icon'>🎉</div><h3>All borrowers are active</h3><p>Every borrower has at least one loan or purchase.</p></div>";
        break;

      case 'multiple_authors':
        $rows = fetchAll($conn,"SELECT b.title, COUNT(ba.author_id) AS authors_count FROM book b JOIN bookauthor ba ON b.book_id = ba.book_id GROUP BY b.book_id HAVING COUNT(ba.author_id) > 1");
        if($rows){
          echo '<div class="table-wrapper"><table><thead><tr><th>Book</th><th>Author Count</th></tr></thead><tbody>';
          foreach($rows as $r2) echo "<tr><td>".htmlspecialchars($r2['title'])."</td><td><span class='badge badge-blue'>".$r2['authors_count']." authors</span></td></tr>";
          echo '</tbody></table></div>';
        } else echo "<div class='empty-state'><div class='empty-state-icon'>👤</div><h3>No multi-author books</h3><p>All books have a single author.</p></div>";
        break;

      case 'sold_books':
        $rows = fetchAll($conn,"SELECT b.title, s.sale_price, s.sale_date FROM sale s JOIN book b ON b.book_id = s.book_id");
        if($rows){
          echo '<div class="table-wrapper"><table><thead><tr><th>Book</th><th>Sale Date</th><th>Price</th></tr></thead><tbody>';
          foreach($rows as $r2) echo "<tr><td>".htmlspecialchars($r2['title'])."</td><td>".htmlspecialchars($r2['sale_date'])."</td><td>$".htmlspecialchars($r2['sale_price'])."</td></tr>";
          echo '</tbody></table></div>';
        } else echo "<div class='empty-state'><div class='empty-state-icon'>🛒</div><h3>No sales yet</h3><p>No books have been sold.</p></div>";
        break;

      case 'available_books':
        $rows = fetchAll($conn,"SELECT title, category FROM book WHERE available=1");
        if($rows){
          echo '<div class="table-wrapper"><table><thead><tr><th>Title</th><th>Category</th></tr></thead><tbody>';
          foreach($rows as $r2) echo "<tr><td>".htmlspecialchars($r2['title'])."</td><td>".htmlspecialchars($r2['category'])."</td></tr>";
          echo '</tbody></table></div>';
        } else echo "<div class='empty-state'><div class='empty-state-icon'>📦</div><h3>No books available</h3><p>All books are currently on loan.</p></div>";
        break;

      case 'borrower_history':
        $borrower_id = $_GET['borrower_id'] ?? '';
        if(!$borrower_id){
          $borrowers = fetchAll($conn,"SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name");
          echo '<form method="get" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                  <input type="hidden" name="mode" value="reports">
                  <input type="hidden" name="r" value="borrower_history">
                  <div class="form-group" style="margin:0;flex:1;min-width:200px;">
                    <label>Select Borrower</label>
                    <select name="borrower_id">';
          foreach($borrowers as $b){ $label=htmlspecialchars($b['first_name'].' '.$b['last_name']); echo '<option value="'.$b['borrower_id'].'">'.$label.'</option>'; }
          echo '    </select></div>
                  <button type="submit" class="btn btn-primary">View History</button>
                </form>';
        } else {
          $bid = intval($borrower_id);
          $rows = fetchAll($conn,"SELECT b.title, l.loan_date, l.return_date FROM loan l JOIN book b ON b.book_id=l.book_id WHERE l.borrower_id=$bid");
          if($rows){
            echo '<div class="table-wrapper"><table><thead><tr><th>Book</th><th>Loan Date</th><th>Return Date</th><th>Status</th></tr></thead><tbody>';
            foreach($rows as $r2){
              $status = $r2['return_date'] ? '<span class="badge badge-green">Returned</span>' : '<span class="badge badge-amber">Active</span>';
              echo "<tr><td>".htmlspecialchars($r2['title'])."</td><td>".htmlspecialchars($r2['loan_date'])."</td><td>".htmlspecialchars($r2['return_date'])."</td><td>$status</td></tr>";
            }
            echo '</tbody></table></div>';
          } else echo "<div class='empty-state'><div class='empty-state-icon'>📜</div><h3>No history</h3><p>This borrower has no loan history.</p></div>";
        }
        break;

      default:
        echo "<p class='text-muted'>Select a report from the cards above.</p>";
    }
    ?>
    </div>
  </div>
  <?php endif; ?>

<?php /* =========== TABLE VIEW =========== */ ?>
<?php elseif ($selectedTable): ?>
  <!-- Search bar -->
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <form method="get" class="search-bar">
      <input type="hidden" name="table" value="<?= htmlspecialchars($selectedTable) ?>">
      <div class="search-input-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" name="search" placeholder="Search <?= htmlspecialchars($selectedTable) ?>…"
               value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn btn-secondary btn-sm">Search</button>
      <?php if($search): ?>
        <a href="dashboard.php?table=<?= $selectedTable ?>" class="btn btn-ghost btn-sm">✕ Clear</a>
      <?php endif; ?>
    </form>
    <?php if($role==='admin'): ?>
      <a href="add_<?= $selectedTable ?>.php" class="btn btn-primary btn-sm">+ Add <?= ucfirst($selectedTable) ?></a>
    <?php endif; ?>
  </div>

  <?php
    // Build SQL
    $sql = "SELECT * FROM `$selectedTable`";
    if ($search) {
      $cols = [];
      $colResult = mysqli_query($conn,"SHOW COLUMNS FROM `$selectedTable`");
      while($col = mysqli_fetch_assoc($colResult)){
        $field = $col['Field']; $type = $col['Type'];
        if(preg_match('/int|decimal|float|double/',$type))
          $cols[] = "CAST(`$field` AS CHAR) LIKE '%".esc($conn,$search)."%'";
        else
          $cols[] = "`$field` LIKE '%".esc($conn,$search)."%'";
      }
      $sql .= " WHERE ".implode(" OR ",$cols);
    }

    $result = mysqli_query($conn,$sql);
    if ($result && mysqli_num_rows($result) > 0):
  ?>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <?php
          $fields = [];
          mysqli_data_seek($result,0);
          while($field = mysqli_fetch_field($result)){
            echo '<th>'.htmlspecialchars($field->name).'</th>';
            $fields[] = $field->name;
          }
          if($role==='admin') echo '<th>Actions</th>';
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        mysqli_data_seek($result,0);
        // Re-fetch rows for display
        $rows2 = [];
        while($row = mysqli_fetch_assoc($result)) $rows2[] = $row;

        foreach($rows2 as $row):
          echo '<tr>';
          foreach($row as $k=>$v){
            // Special display: available field
            if($k==='available'){
              $badge = $v ? '<span class="badge badge-green">Available</span>' : '<span class="badge badge-red">Unavailable</span>';
              echo "<td>$badge</td>";
            } else {
              echo '<td>'.htmlspecialchars((string)$v).'</td>';
            }
          }
          if($role==='admin'){
            $pk  = array_keys($row)[0];
            $id  = $row[$pk];
            echo '<td><div class="table-actions">
                    <a href="edit_'.$selectedTable.'.php?id='.$id.'" class="btn btn-secondary btn-sm">✏️ Edit</a>
                    <a href="delete_'.$selectedTable.'.php?id='.$id.'" class="btn btn-danger btn-sm"
                       onclick="return confirm(\'Delete this record?\')">🗑️</a>
                  </div></td>';
          }
          echo '</tr>';
        endforeach;
        ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <div class="empty-state">
      <div class="empty-state-icon"><?= $tableTitles[$selectedTable][0] ?? '📭' ?></div>
      <h3>No records found</h3>
      <p><?= $search ? 'No results match your search.' : 'No '.htmlspecialchars($selectedTable).' records exist yet.' ?></p>
      <?php if($role==='admin' && !$search): ?>
        <a href="add_<?= $selectedTable ?>.php" class="btn btn-primary" style="margin-top:16px;">+ Add First <?= ucfirst($selectedTable) ?></a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

<?php /* =========== DEFAULT LANDING =========== */ ?>
<?php else: ?>
  <p style="color:var(--text-muted);margin-bottom:24px;">
    Welcome back, <strong><?= htmlspecialchars($username) ?></strong>. Select a collection from the sidebar to get started.
  </p>

  <div class="about-grid">
    <?php foreach([
      ['book','📚','Books','Browse and manage the book catalogue'],
      ['author','✍️','Authors','View all registered authors'],
      ['publisher','🏢','Publishers','Publisher directory'],
      ['borrower','👤','Borrowers','Member management'],
      ['loan','🔗','Loans','Track borrowed books'],
      ['sale','💰','Sales','Sales records'],
    ] as [$table,$icon,$title,$desc]): ?>
      <a href="dashboard.php?table=<?= $table ?>" class="about-feature" style="cursor:pointer;transition:all 0.2s;text-decoration:none;"
         onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
        <div class="about-feature-icon"><?= $icon ?></div>
        <h4><?= $title ?></h4>
        <p><?= $desc ?></p>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

    </div><!-- page-content -->
  </div><!-- main-area -->
</div><!-- app-layout -->
</body>
</html>
