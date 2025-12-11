<?php
include 'includes/auth_check.php';
include '../includes/db_connect.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $admin_username = $_SESSION['admin']; // Get username from session

    if ($new_password !== $confirm_password) {
        $error = "❌ New password and confirmation password do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "❌ New password must be at least 6 characters long.";
    } else {
        // 1. Fetch current user data to verify old password
        $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($current_password, $user['password'])) {
            // 2. Hash the new password and update in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user['user_id']);
            
            if ($update_stmt->execute()) {
                $message = "✅ Password updated successfully!";
            } else {
                $error = "❌ Database update failed: " . $update_stmt->error;
            }
            $update_stmt->close();

        } else {
            $error = "❌ Incorrect current password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account | Arty-U Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; display: flex; min-height: 100vh; }
    .sidebar {
      width: 230px; background-color: #212529; color: white; flex-shrink: 0;
      display: flex; flex-direction: column;
    }
    .sidebar a { color: white; text-decoration: none; padding: 12px 18px; display: block; }
    .sidebar a:hover, .sidebar a.active { background-color: #343a40; border-left: 4px solid #ffc107; }
    .main-content { flex-grow: 1; padding: 20px; }
  </style>
</head>
<body>

<div class="sidebar">
  <h4 class="text-center py-3 border-bottom mb-3">🎨 Arty-U Admin</h4>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="artworks.php">🖼️ Artworks</a>
  <a href="categories.php">🗂️ Categories</a>
  <a href="comments.php">💬 Comments</a>
  <a href="profile.php" class="active">👤 My Account</a>
  <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
  <h3 class="fw-bold mb-4">👤 My Account: <?php echo htmlspecialchars($_SESSION['admin']); ?></h3>

  <div class="card shadow-sm mb-4" style="max-width: 500px;">
    <div class="card-body">
      <h5 class="card-title mb-3">🔒 Change Password</h5>
      
      <?php if ($message): ?>
        <div class="alert alert-success text-center"><?php echo $message; ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password</label>
          <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="new_password" class="form-label">New Password</label>
          <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm New Password</label>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="6">
        </div>
        <button type="submit" name="change_password" class="btn btn-dark w-100">Change Password</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>