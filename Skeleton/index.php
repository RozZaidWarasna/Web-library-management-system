<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    if (empty($u) || empty($p)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $u);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($p, $user['password'])) {
                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                header("Location: dashboard.php");
                exit;
            }
        }
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — Library System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

  <!-- LEFT PANEL -->
  <div class="auth-panel-left">
    <div class="auth-brand">
      <div class="auth-brand-icon">📚</div>
      <h1>Library System</h1>
      <p>Manage books, authors, borrowers, loans, and sales in one elegant place.</p>

      <div style="margin-top:48px; display:flex; flex-direction:column; gap:16px;">
        <?php foreach([
          ['📖','Books & Authors','Track your entire collection with ease'],
          ['👤','Borrower Management','Monitor loans and member activity'],
          ['📊','Insightful Reports','Data-driven decisions at a glance'],
        ] as [$icon,$title,$desc]): ?>
        <div style="display:flex;align-items:center;gap:14px;text-align:left;">
          <div style="font-size:22px;width:40px;text-align:center;"><?= $icon ?></div>
          <div>
            <div style="color:#fff;font-weight:600;font-size:14px;"><?= $title ?></div>
            <div style="color:var(--sidebar-text);font-size:12px;"><?= $desc ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="auth-panel-right">
    <div class="auth-form-box">
      <h2>Welcome back</h2>
      <p class="auth-subtitle">Sign in to your account to continue</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input id="username" name="username" placeholder="Enter your username" autocomplete="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">
          Sign In →
        </button>
      </form>

      <div class="auth-link">
        Don't have an account? <a href="signup.php">Create one</a>
      </div>
    </div>
  </div>

</body>
</html>
