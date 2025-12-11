<?php 
include 'includes/header.php';
include 'includes/db_connect.php';
?>

<style>
.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
  transform: scale(1.03);
  box-shadow: 0 8px 18px rgba(0,0,0,0.2);
}
</style>

<h2 class="fw-bold text-center mb-4">Art Gallery</h2>

<!-- Category Filter -->
<div class="text-center mb-4">
  <form method="GET" action="gallery.php" class="d-inline-block">
    <select name="category" class="form-select d-inline-block w-auto">
      <option value="">All Categories</option>
      <?php
      $cat_sql = "SELECT * FROM categories ORDER BY category_name ASC";
      $cat_result = $conn->query($cat_sql);

      if ($cat_result && $cat_result->num_rows > 0) {
        while ($cat = $cat_result->fetch_assoc()) {
          $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category_id']) ? 'selected' : '';
          echo '<option value="' . htmlspecialchars($cat['category_id']) . '" ' . $selected . '>' 
              . htmlspecialchars($cat['category_name']) . '</option>';
        }
      }
      ?>
    </select>
    <button type="submit" class="btn btn-dark btn-sm ms-2">Filter</button>
  </form>
</div>

<!-- Artworks Grid -->
<div class="row">
  <?php
  $sql = "SELECT a.*, c.category_name 
          FROM artworks a 
          LEFT JOIN categories c ON a.category_id = c.category_id ";

  if (!empty($_GET['category'])) {
    $sql .= "WHERE a.category_id = ? ";
    $stmt = $conn->prepare($sql . "ORDER BY a.upload_date DESC");
    $stmt->bind_param("i", $_GET['category']);
  } else {
    $stmt = $conn->prepare($sql . "ORDER BY a.upload_date DESC");
  }

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $image_path = 'assets/uploads/' . htmlspecialchars($row['image_path']);
      if (!file_exists($image_path) || empty($row['image_path'])) {
        $image_path = 'assets/images/placeholder.png';
      }

      echo '
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="' . $image_path . '" class="card-img-top" alt="' . htmlspecialchars($row['title']) . '">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-1">' . htmlspecialchars($row['title']) . '</h5>
            <p class="text-muted mb-2"><small>' . htmlspecialchars($row['category_name']) . '</small></p>
            <p class="card-text small flex-grow-1">' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>
            <a href="artwork.php?id=' . $row['artwork_id'] . '" class="btn btn-outline-dark btn-sm mt-auto">View Details</a>
          </div>
        </div>
      </div>';
    }
  } else {
    echo '<p class="text-center text-muted">No artworks found.</p>';
  }

  $stmt->close();
  ?>
</div>

<?php include 'includes/footer.php'; ?>
