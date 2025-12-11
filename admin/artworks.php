<?php
include 'includes/auth_check.php';
include '../includes/db_connect.php';

// ✅ Allowed File Types Configuration
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Optional: Fetch image path first to unlink (delete) the file from the folder too
    $stmt = $conn->prepare("DELETE FROM artworks WHERE artwork_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-danger text-center">🗑️ Artwork deleted successfully.</div>';
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_artwork'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $cat_id = intval($_POST['category_id']);
    $file = $_FILES['image'];
    $upload_ok = true;
    $file_name = '';

    // Check if file is selected
    if ($file && $file['error'] === 0) {
        $file_mime = mime_content_type($file['tmp_name']);
        
        if (in_array($file_mime, $allowed_types)) {
            $target_dir = "../assets/uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_name = time() . '_' . basename($file['name']);
            if (!move_uploaded_file($file['tmp_name'], $target_dir . $file_name)) {
                echo '<div class="alert alert-danger text-center">❌ Failed to upload file. Check folder permissions.</div>';
                $upload_ok = false;
            }
        } else {
            echo '<div class="alert alert-danger text-center">❌ Invalid file type. Only JPG, PNG, GIF, and WEBP allowed.</div>';
            $upload_ok = false;
        }
    } else {
        echo '<div class="alert alert-warning text-center">⚠️ Please select an image.</div>';
        $upload_ok = false;
    }

    if ($upload_ok) {
        $stmt = $conn->prepare("INSERT INTO artworks (title, description, image_path, category_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $desc, $file_name, $cat_id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success text-center">✅ Artwork added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger text-center">❌ Database Error: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

// Handle Edit (Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_artwork'])) {
    $id = intval($_POST['artwork_id']);
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $cat_id = intval($_POST['category_id']);
    $file = $_FILES['image'];
    $upload_ok = true;

    // If a new image is uploaded
    if ($file && $file['error'] === 0) {
        $file_mime = mime_content_type($file['tmp_name']);
        
        if (in_array($file_mime, $allowed_types)) {
            $target_dir = "../assets/uploads/";
            $file_name = time() . '_' . basename($file['name']);
            
            if (move_uploaded_file($file['tmp_name'], $target_dir . $file_name)) {
                // Update with new image
                $stmt = $conn->prepare("UPDATE artworks SET title=?, description=?, category_id=?, image_path=? WHERE artwork_id=?");
                $stmt->bind_param("ssisi", $title, $desc, $cat_id, $file_name, $id);
            } else {
                echo '<div class="alert alert-danger text-center">❌ Failed to upload file.</div>';
                $upload_ok = false;
            }
        } else {
            echo '<div class="alert alert-danger text-center">❌ Invalid file type. Only JPG, PNG, GIF, and WEBP allowed.</div>';
            $upload_ok = false;
        }
    } else {
        // Update without changing image
        $stmt = $conn->prepare("UPDATE artworks SET title=?, description=?, category_id=? WHERE artwork_id=?");
        $stmt->bind_param("ssii", $title, $desc, $cat_id, $id);
    }

    if ($upload_ok) {
        if ($stmt->execute()) {
            echo '<div class="alert alert-success text-center">📝 Artwork updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger text-center">❌ Database Error: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

// Fetch categories
$cat_result = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

// Fetch all artworks
$art_result = $conn->query("
    SELECT a.*, c.category_name 
    FROM artworks a
    LEFT JOIN categories c ON a.category_id = c.category_id
    ORDER BY a.upload_date DESC
");

// If editing, fetch artwork details
$edit_artwork = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM artworks WHERE artwork_id = $edit_id");
    if ($res->num_rows > 0) $edit_artwork = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Artworks | Arty-U Admin</title>
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
    img.thumb { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; }
  </style>
</head>
<body>

<div class="sidebar">
  <h4 class="text-center py-3 border-bottom mb-3">🎨 Arty-U Admin</h4>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="artworks.php" class="active">🖼️ Artworks</a>
  <a href="categories.php">🗂️ Categories</a>
  <a href="comments.php">💬 Comments</a>
  <a href="profile.php">👤 My Account</a> <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
  <h3 class="fw-bold mb-3">🖼️ Artworks</h3>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">
        <?php echo $edit_artwork ? "✏️ Edit Artwork" : "➕ Add New Artwork"; ?>
      </h5>

      <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_artwork): ?>
          <input type="hidden" name="artwork_id" value="<?php echo $edit_artwork['artwork_id']; ?>">
        <?php endif; ?>

        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <input type="text" name="title" class="form-control" placeholder="Artwork Title"
              value="<?php echo $edit_artwork ? htmlspecialchars($edit_artwork['title']) : ''; ?>" required>
          </div>
          <div class="col-md-6">
            <select name="category_id" class="form-control" required>
              <option value="">Select Category</option>
              <?php
              $cat_result->data_seek(0); // reset pointer
              while ($cat = $cat_result->fetch_assoc()) {
                  $selected = ($edit_artwork && $edit_artwork['category_id'] == $cat['category_id']) ? 'selected' : '';
                  echo '<option value="' . $cat['category_id'] . '" ' . $selected . '>' . htmlspecialchars($cat['category_name']) . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="mb-2">
          <textarea name="description" class="form-control" rows="3" placeholder="Description..." required><?php echo $edit_artwork ? htmlspecialchars($edit_artwork['description']) : ''; ?></textarea>
        </div>

        <div class="mb-3">
          <input type="file" name="image" accept="image/*" class="form-control" <?php echo $edit_artwork ? '' : 'required'; ?>>
          <?php if ($edit_artwork && $edit_artwork['image_path']): ?>
            <div class="mt-2">
              <img src="../assets/uploads/<?php echo htmlspecialchars($edit_artwork['image_path']); ?>" class="thumb">
              <small class="text-muted ms-2">(Current Image)</small>
            </div>
          <?php endif; ?>
        </div>

        <button type="submit" name="<?php echo $edit_artwork ? 'update_artwork' : 'add_artwork'; ?>" class="btn btn-dark">
          <?php echo $edit_artwork ? '💾 Update Artwork' : '➕ Add Artwork'; ?>
        </button>

        <?php if ($edit_artwork): ?>
          <a href="artworks.php" class="btn btn-secondary ms-2">Cancel Edit</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">All Artworks</h5>
      <table class="table table-bordered align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Description</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($a = $art_result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $a['artwork_id']; ?></td>
            <td><img src="../assets/uploads/<?php echo htmlspecialchars($a['image_path']); ?>" class="thumb"></td>
            <td><?php echo htmlspecialchars($a['title']); ?></td>
            <td><?php echo htmlspecialchars($a['category_name']); ?></td>
            <td><?php echo htmlspecialchars($a['description']); ?></td>
            <td><?php echo date('F j, Y', strtotime($a['upload_date'])); ?></td>
            <td class="text-center">
              <a href="?edit=<?php echo $a['artwork_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="?delete=<?php echo $a['artwork_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this artwork?')">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

</body>
</html>