<?php
// Start session if not already started (needed for checking admin login)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/header.php';
include 'includes/db_connect.php';

// ✅ Validate artwork ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-warning text-center">Invalid artwork ID. <a href="gallery.php">Back to gallery</a></div>';
    include 'includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

// ✅ Fetch artwork details
$stmt = $conn->prepare("
    SELECT a.*, c.category_name 
    FROM artworks a 
    LEFT JOIN categories c ON a.category_id = c.category_id 
    WHERE a.artwork_id = ? LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-danger text-center">Artwork not found. <a href="gallery.php">Back to gallery</a></div>';
    include 'includes/footer.php';
    exit;
}

$artwork = $result->fetch_assoc();
$stmt->close();

$img_path = file_exists('assets/uploads/' . $artwork['image_path'])
    ? 'assets/uploads/' . htmlspecialchars($artwork['image_path'])
    : 'assets/images/placeholder.png';

$upload_date = date('F j, Y', strtotime($artwork['upload_date']));

// ✅ Like button handler
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['like_submit'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $check = $conn->prepare("SELECT * FROM likes WHERE artwork_id = ? AND ip_address = ?");
    $check->bind_param("is", $id, $ip);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows === 0) {
        $insert_like = $conn->prepare("INSERT INTO likes (artwork_id, ip_address) VALUES (?, ?)");
        $insert_like->bind_param("is", $id, $ip);
        $insert_like->execute();
        $insert_like->close();
        echo '<div class="alert alert-success text-center">❤️ Thanks for liking this artwork!</div>';
    } else {
        echo '<div class="alert alert-info text-center">You already liked this artwork!</div>';
    }
    $check->close();
}

// ✅ Delete comment (SECURE: Checks for Admin Role)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_comment'])) {
    // CHECK: Change 'role' to whatever session variable you use for admin login
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        $comment_id = intval($_POST['delete_comment']);
        $delete = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
        $delete->bind_param("i", $comment_id);
        $delete->execute();
        $delete->close();
        echo '<div class="alert alert-danger text-center">🗑️ Comment deleted successfully.</div>';
    } else {
        // If a hacker tries to force a delete without being logged in
        echo '<div class="alert alert-warning text-center">⛔ You do not have permission to delete comments.</div>';
    }
}

// ✅ Submit new comment
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comment_submit'])) {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    if ($name !== "" && $message !== "") {
        $bad_words = include 'includes/badwords.php';
        $clean_text = strtolower(preg_replace('/[^a-z0-9\s]/', '', $message));
        $found_bad_word = false;

        foreach ($bad_words as $word) {
            $pattern = '/' . str_ireplace(['*', '@', '$', '1'], '[a-z0-9]*', preg_quote($word, '/')) . '/i';
            if (preg_match($pattern, $clean_text)) {
                $found_bad_word = true;
                break;
            }
        }

        if ($found_bad_word) {
            echo '<div class="alert alert-danger text-center">🚫 Please avoid using inappropriate language.</div>';
        } else {
            $insert = $conn->prepare("INSERT INTO comments (artwork_id, name, message) VALUES (?, ?, ?)");
            $insert->bind_param("iss", $id, $name, $message);
            $insert->execute();
            $insert->close();
            echo '<div class="alert alert-success text-center">💬 Comment posted successfully!</div>';
        }
    } else {
        echo '<div class="alert alert-warning text-center">⚠️ Please fill in all fields.</div>';
    }
}

// ✅ Fetch comments
$comments = $conn->prepare("SELECT * FROM comments WHERE artwork_id = ? ORDER BY date_posted DESC");
$comments->bind_param("i", $id);
$comments->execute();
$comments_result = $comments->get_result();
$comments->close();
?>

<style>
.alert { transition: opacity 0.6s ease; }
.related-art { transition: transform 0.3s; }
.related-art:hover { transform: scale(1.03); }
</style>

<div class="row mb-4">
  <div class="col-md-7">
    <div class="card shadow-sm">
      <img src="<?php echo $img_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($artwork['title']); ?>">
    </div>
  </div>

  <div class="col-md-5">
    <h2 class="fw-bold"><?php echo htmlspecialchars($artwork['title']); ?></h2>
    <p class="text-muted mb-1"><small><?php echo htmlspecialchars($artwork['category_name']); ?></small></p>
    <p><?php echo nl2br(htmlspecialchars($artwork['description'])); ?></p>
    <p class="text-secondary"><small>Uploaded on <?php echo htmlspecialchars($upload_date); ?></small></p>

    <a href="gallery.php" class="btn btn-outline-dark">← Back to Gallery</a>

    <form method="POST" class="d-inline ms-2">
      <button type="submit" name="like_submit" class="btn btn-danger btn-sm">
        ❤️ Like (<?php
          $count = $conn->query("SELECT COUNT(*) AS total FROM likes WHERE artwork_id = $id")->fetch_assoc();
          echo $count['total'];
        ?>)
      </button>
    </form>
  </div>
</div>

<hr>

<div class="comments-section mt-4">
  <h4 class="fw-bold mb-3">Comments</h4>

  <form method="POST" class="mb-4">
    <div class="mb-2">
      <input type="text" name="name" class="form-control" placeholder="Your name" required>
    </div>
    <div class="mb-2">
      <textarea name="message" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
    </div>
    <button type="submit" name="comment_submit" class="btn btn-dark btn-sm">Post Comment</button>
  </form>

  <?php if ($comments_result && $comments_result->num_rows > 0): ?>
    <?php while ($c = $comments_result->fetch_assoc()): ?>
      <div class="border rounded p-3 mb-3">
        <strong><?php echo htmlspecialchars($c['name']); ?></strong>
        <p class="mb-1"><?php echo nl2br(htmlspecialchars($c['message'])); ?></p>
        <small class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($c['date_posted'])); ?></small>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <form method="POST" class="mt-2">
            <input type="hidden" name="delete_comment" value="<?php echo $c['comment_id']; ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</button>
          </form>
        <?php endif; ?>
        
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-muted">No comments yet. Be the first to comment!</p>
  <?php endif; ?>
</div>

<hr>

<?php
$related = $conn->prepare("
  SELECT artwork_id, title, image_path 
  FROM artworks 
  WHERE category_id = ? AND artwork_id != ? 
  ORDER BY RAND() LIMIT 3
");
$related->bind_param("ii", $artwork['category_id'], $id);
$related->execute();
$related_result = $related->get_result();
?>

<?php if ($related_result && $related_result->num_rows > 0): ?>
  <div class="mt-5">
    <h4 class="fw-bold mb-3">Related Artworks</h4>
    <div class="row">
      <?php while ($r = $related_result->fetch_assoc()): ?>
        <div class="col-md-4 mb-3">
          <div class="card related-art shadow-sm h-100">
            <img src="assets/uploads/<?php echo htmlspecialchars($r['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($r['title']); ?>">
            <div class="card-body text-center">
              <h6 class="card-title"><?php echo htmlspecialchars($r['title']); ?></h6>
              <a href="artwork.php?id=<?php echo $r['artwork_id']; ?>" class="btn btn-outline-dark btn-sm">View</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 600);
    }, 3000);
  });
});
</script>

<?php include 'includes/footer.php'; ?>