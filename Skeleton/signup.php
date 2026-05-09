<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = trim($_POST['username']);
    $e = trim($_POST['email']);
    $p = $_POST['password'];
    $r = $_POST['role'];

    if (empty($u) || empty($e) || empty($p) || empty($r)) {
        $error = "All fields are required.";
    } else {
        $hashed = password_hash($p, PASSWORD_DEFAULT);
        $sql  = "INSERT INTO users(username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $u, $e, $hashed, $r);
        mysqli_stmt_execute($stmt);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account — Library System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

  <!-- LEFT PANEL -->
  <div class="auth-panel-left">
    <div class="auth-brand">
      <div class="auth-brand-icon">📚</div>
      <h1>Library System</h1>
      <p>Join our library platform and get access to a world of books and resources.</p>

      <div style="margin-top:40px;">
        <div style="color:var(--sidebar-text);font-size:13px;line-height:1.9;">
          ✓ &nbsp;Full access to book catalogue<br>
          ✓ &nbsp;Track loans and returns<br>
          ✓ &nbsp;Manage your borrowing history<br>
          ✓ &nbsp;Access detailed reports
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="auth-panel-right">
    <div class="auth-form-box">
      <h2>Create account</h2>
      <p class="auth-subtitle">Fill in your details to get started</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input id="username" name="username" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
          <label for="email">Email address</label>
          <input id="email" type="email" name="email" placeholder="your@email.com" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="Create a strong password" required>
        </div>
        <div class="form-group">
          <label for="role">Account role</label>
          <select id="role" name="role" required>
            <option value="">Select role…</option>
            <option value="student">Student</option>
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-full">
          Create Account →
        </button>
      </form>

      <div class="auth-link">
        Already have an account? <a href="index.php">Sign in</a>
      </div>
    </div>
  </div>

</body>
</html>
