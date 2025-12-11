<?php
include 'includes/auth_check.php';
include '../includes/db_connect.php';

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if ($name !== "") {
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        echo '<div class="alert alert-success text-center">✅ Category added successfully!</div>';
    } else {
        echo '<div class="alert alert-warning text-center">⚠️ Please enter a category name.</div>';
    }
}

// Handle Edit Category (update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $id = intval($_POST['category_id']);
    $name = trim($_POST['category_name']);
    if ($name !== "") {
        $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        echo '<div class="alert alert-success text-center">📝 Category updated successfully!</div>';
    } else {
        echo '<div class="alert alert-warning text-center">⚠️ Please enter a category name.</div>';
    }
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-danger text-center">🗑️ Category deleted successfully.</div>';
}

// Fetch single category if editing
$edit_category = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM categories WHERE category_id = $edit_id");
    if ($res->num_rows > 0) $edit_category = $res->fetch_assoc();
}

// Fetch all categories
$result = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Categories | Arty-U Admin</title>
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
  <a href="categories.php" class="active">🗂️ Categories</a>
  <a href="comments.php">💬 Comments</a>
  <a href="profile.php">👤 My Account</a> <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
  <h3 class="fw-bold mb-3">🗂️ Manage Categories</h3>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">
        <?php echo $edit_category ? "✏️ Edit Category" : "➕ Add New Category"; ?>
      </h5>

      <form method="POST" class="row g-3">
        <?php if ($edit_category): ?>
          <input type="hidden" name="category_id" value="<?php echo $edit_category['category_id']; ?>">
        <?php endif; ?>

        <div class="col-md-8">
          <input type="text" name="category_name" class="form-control"
                 placeholder="Enter category name..."
                 value="<?php echo $edit_category ? htmlspecialchars($edit_category['category_name']) : ''; ?>" required>
        </div>

        <div class="col-md-4 d-grid">
          <button type="submit"
                  name="<?php echo $edit_category ? 'update_category' : 'add_category'; ?>"
                  class="btn btn-dark">
            <?php echo $edit_category ? '💾 Update Category' : '+ Add Category'; ?>
          </button>
        </div>

        <?php if ($edit_category): ?>
          <div class="col-12">
            <a href="categories.php" class="btn btn-secondary btn-sm mt-2">Cancel Edit</a>
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="mb-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0">All Categories</h5>
    <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 Search category...">
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <table id="categoryTable" class="table table-bordered align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['category_id']; ?></td>
            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
            <td class="text-center">
              <a href="?edit=<?php echo $row['category_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="?delete=<?php echo $row['category_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
// 🔍 Live search filter
document.getElementById('searchInput').addEventListener('keyup', function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#categoryTable tbody tr');

  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
</script>

</body>
</html>