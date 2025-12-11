<?php
include 'includes/auth_check.php';
include '../includes/db_connect.php';

// Fetch quick stats
$artworks = $conn->query("SELECT COUNT(*) AS total FROM artworks")->fetch_assoc()['total'];
$categories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$comments = $conn->query("SELECT COUNT(*) AS total FROM comments")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Arty-U</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 230px;
      background-color: #212529;
      color: white;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 12px 18px;
      display: block;
      border-left: 4px solid transparent;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #343a40;
      border-left-color: #ffc107;
    }
    .main-content {
      flex-grow: 1;
      padding: 20px;
    }
    .card {
      border: none;
      border-radius: 12px;
    }
    .topbar {
      background-color: white;
      border-bottom: 1px solid #ddd;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h4 class="text-center py-3 border-bottom mb-3">🎨 Arty-U Admin</h4>
  <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">📊 Dashboard</a>
  <a href="artworks.php">🖼️ Artworks</a>
  <a href="categories.php">🗂️ Categories</a>
  <a href="comments.php">💬 Comments</a>
  <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">👤 My Account</a> <a href="logout.php" 
   onclick="return confirm('Are you sure you want to log out?')" 
   data-bs-toggle="tooltip" 
   data-bs-placement="right" 
   title="Logout from your admin account">
   <i class="bi bi-box-arrow-right"></i> Logout
</a>
</div>

<div class="main-content">
  <div class="topbar">
    <h5 class="m-0">Welcome, <?php echo $_SESSION['admin']; ?>!</h5>
    <?php if (!empty($_SESSION['last_login'])): ?>
      <small class="text-muted">
        Last login: <?php echo date('F j, Y g:i a', strtotime($_SESSION['last_login'])); ?>
      </small>
    <?php else: ?>
      <small class="text-muted">This is your first login.</small>
    <?php endif; ?>
  </div>

  <div class="container-fluid mt-4">
    <?php if (isset($_SESSION['login_success'])): ?>
  <div id="loginAlert" class="alert alert-success text-center">
    ✅ Login successful! Welcome back, <?php echo $_SESSION['admin']; ?>.
  </div>
  <?php unset($_SESSION['login_success']); // remove the flag so it only shows once ?>
<?php endif; ?>
    <h3 class="fw-bold mb-4">Dashboard Overview</h3>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-center p-4 shadow-sm bg-white">
          <h2 class="fw-bold"><?php echo $artworks; ?></h2>
          <p class="text-muted">Total Artworks</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center p-4 shadow-sm bg-white">
          <h2 class="fw-bold"><?php echo $categories; ?></h2>
          <p class="text-muted">Total Categories</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center p-4 shadow-sm bg-white">
          <h2 class="fw-bold"><?php echo $comments; ?></h2>
          <p class="text-muted">Total Comments</p>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Enable Bootstrap tooltips
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
</script>

<script>
  // Make success alert fade out after 2 seconds
  const alertBox = document.getElementById('loginAlert');
  if (alertBox) {
    setTimeout(() => {
      alertBox.style.transition = "opacity 0.5s ease";
      alertBox.style.opacity = "0";
      setTimeout(() => alertBox.remove(), 500); // remove after fade
    }, 2000);
  }
</script>

</body>
</html>