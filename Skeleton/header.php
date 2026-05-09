<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$username = $_SESSION['username'];
$role     = $_SESSION['role'];
$currentTable = $_GET['table'] ?? $_SESSION['last_table'] ?? '';
$currentMode  = $_GET['mode']  ?? '';

$nav = [
  ['icon'=>'📚','label'=>'Books',      'table'=>'book'],
  ['icon'=>'✍️', 'label'=>'Authors',    'table'=>'author'],
  ['icon'=>'🏢','label'=>'Publishers', 'table'=>'publisher'],
  ['icon'=>'👤','label'=>'Borrowers',  'table'=>'borrower'],
  ['icon'=>'🔗','label'=>'Loans',      'table'=>'loan'],
  ['icon'=>'💰','label'=>'Sales',      'table'=>'sale'],
];

function navActive($item, $currentTable, $currentMode) {
  return (!$currentMode && $currentTable === $item['table']) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
      <div class="sidebar-brand-inner">
        <div class="sidebar-logo">📚</div>
        <div class="sidebar-brand-text">
          <h2>LibraryMS</h2>
          <span>Management System</span>
        </div>
      </div>
    </div>

    <!-- User -->
    <div class="sidebar-user">
      <div class="sidebar-avatar"><?= strtoupper(substr($username,0,1)) ?></div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name"><?= htmlspecialchars($username) ?></div>
        <div class="sidebar-user-role"><?= htmlspecialchars($role) ?></div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav">
      <div class="sidebar-section-label">Collections</div>
      <?php foreach($nav as $item): ?>
        <a href="dashboard.php?table=<?= $item['table'] ?>"
           class="sidebar-nav-item <?= navActive($item, $currentTable, $currentMode) ?>">
          <span class="nav-icon"><?= $item['icon'] ?></span>
          <?= $item['label'] ?>
        </a>
      <?php endforeach; ?>

      <div class="sidebar-section-label" style="margin-top:8px;">Analytics</div>
      <a href="dashboard.php?mode=reports"
         class="sidebar-nav-item <?= $currentMode==='reports'?'active':'' ?>">
        <span class="nav-icon">📊</span> Reports
      </a>

      <div class="sidebar-section-label" style="margin-top:8px;">System</div>
      <a href="dashboard.php?mode=about"
         class="sidebar-nav-item <?= $currentMode==='about'?'active':'' ?>">
        <span class="nav-icon">ℹ️</span> About
      </a>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
      <a class="sidebar-logout" href="logout.php">
        <span>🚪</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- MAIN AREA -->
  <div class="main-area">
