<?php
session_start();
include '../includes/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['last_login'] = $user['last_login'];

            $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $update->bind_param("i", $user['user_id']);
            $update->execute();
            $update->close();

            // ✅ SET SESSION VARIABLES
            $_SESSION['admin'] = $user['username'];
            $_SESSION['role'] = 'admin'; 
            $_SESSION['login_success'] = true; 

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ Username not found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Arty-U</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow p-4" style="width: 350px;">
    <h4 class="text-center fw-bold mb-4">🎨 Admin Login</h4>

    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
        <div id="logoutAlert" class="alert alert-success text-center mb-3">
            ✅ You have logged out successfully.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['timeout']) && $_GET['timeout'] == 1): ?>
        <div id="timeoutAlert" class="alert alert-warning text-center mb-3">
        ⏰ Your session has expired due to 30 minutes of inactivity. Please log in again.
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" required placeholder="Username">
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" required placeholder="Password">
      </div>
      <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>
  </div>

<script>
  // Fade out logout alert after 2 seconds
  const logoutAlert = document.getElementById('logoutAlert');
  if (logoutAlert) {
    setTimeout(() => {
      logoutAlert.style.transition = "opacity 0.5s ease";
      logoutAlert.style.opacity = "0";
      setTimeout(() => logoutAlert.remove(), 500); 
    }, 2000);
  }

  // Fade out timeout alert after 2 seconds
  const timeoutAlert = document.getElementById('timeoutAlert');
  if (timeoutAlert) {
    setTimeout(() => {
      timeoutAlert.style.transition = "opacity 0.5s ease";
      timeoutAlert.style.opacity = "0";
      setTimeout(() => timeoutAlert.remove(), 500); 
    }, 2000);
  }
</script>

</body>
</html>