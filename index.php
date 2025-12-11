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

<div class="text-center mb-5">
  <h1 class="fw-bold display-5">Welcome to <span class="text-dark">Arty-U</span></h1>
  <p class="text-muted">Discover and appreciate artworks from talented artists around the world.</p>
  <a href="gallery.php" class="btn btn-dark mt-3 px-4">Explore Gallery</a>
</div>

<div class="row">
  <?php
  $sql = "SELECT * FROM artworks ORDER BY upload_date DESC LIMIT 3";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $image_path = 'assets/uploads/' . htmlspecialchars($row['image_path']);
      if (!file_exists($image_path) || empty($row['image_path'])) {
        $image_path = 'assets/images/placeholder.png';
      }

      echo '
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
          <img src="' . $image_path . '" class="card-img-top" alt="' . htmlspecialchars($row['title']) . '">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
            <p class="card-text text-muted small flex-grow-1">' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>
            <a href="artwork.php?id=' . $row['artwork_id'] . '" class="btn btn-outline-dark btn-sm mt-auto">View Details</a>
          </div>
        </div>
      </div>';
    }
  } else {
    echo '<p class="text-center text-muted">No artworks uploaded yet.</p>';
  }
  ?>
</div>

<?php include 'includes/footer.php'; ?>
