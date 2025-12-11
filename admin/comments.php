<?php
include 'includes/auth_check.php';
include '../includes/db_connect.php';

// Handle delete comment
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-danger text-center">🗑️ Comment deleted successfully.</div>';
}

// Optional: handle approve comment (you can skip this if you don’t need approval)
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE comments SET approved = 1 WHERE comment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-success text-center">✅ Comment approved.</div>';
}

// Fetch all comments
$result = $conn->query("
    SELECT c.*, a.title AS artwork_title
    FROM comments c
    LEFT JOIN artworks a ON c.artwork_id = a.artwork_id
    ORDER BY c.date_posted DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Comments | Arty-U Admin</title>
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
    td p { margin-bottom: 4px; }
  </style>
</head>
<body>

<div class="sidebar">
  <h4 class="text-center py-3 border-bottom mb-3">🎨 Arty-U Admin</h4>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="artworks.php">🖼️ Artworks</a>
  <a href="categories.php">🗂️ Categories</a>
  <a href="comments.php" class="active">💬 Comments</a>
  <a href="profile.php">👤 My Account</a> <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
  <h3 class="fw-bold mb-3">💬 Manage Comments</h3>

  <div class="mb-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0">All Comments</h5>
    <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 Search comments...">
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <table id="commentsTable" class="table table-bordered align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Artwork</th>
            <th>Name</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?php echo $row['comment_id']; ?></td>
                <td><?php echo htmlspecialchars($row['artwork_title'] ?? '—'); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p></td>
                <td><?php echo date('F j, Y, g:i a', strtotime($row['date_posted'])); ?></td>
                <td class="text-center">
                  <?php if (isset($row['approved']) && !$row['approved']) { ?>
                    <a href="?approve=<?php echo $row['comment_id']; ?>" class="btn btn-sm btn-outline-success mb-1">Approve</a>
                  <?php } ?>
                  <a href="?delete=<?php echo $row['comment_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this comment?')">Delete</a>
                </td>
              </tr>
          <?php }
          } else {
              echo '<tr><td colspan="6" class="text-center text-muted">No comments yet.</td></tr>';
          } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// 🔍 Live search filter
document.getElementById('searchInput').addEventListener('keyup', function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#commentsTable tbody tr');

  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
</script>

</body>
</html>