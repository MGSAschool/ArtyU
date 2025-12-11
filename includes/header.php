<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arty-U</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    /* ===== 🌙 DARK MODE STYLES ===== */
    :root {
      --bg-light: #f8f9fa;
      --text-light: #212529;
      --card-light: #ffffff;

      --bg-dark: #121212;
      --text-dark: #e9ecef;
      --card-dark: #1e1e1e;
    }

    body.light-mode {
      background-color: var(--bg-light);
      color: var(--text-light);
    }

    body.dark-mode {
      background-color: var(--bg-dark);
      color: var(--text-dark);
    }

    /* Navbar + Footer Styling for Theme */
    nav.navbar.light-mode {
      background-color: #f8f9fa !important;
    }
    nav.navbar.dark-mode {
      background-color: #1a1a1a !important;
    }

    footer.light-mode {
      background-color: #f8f9fa !important;
      color: #212529 !important;
      border-top: 1px solid #dee2e6;
    }
    footer.dark-mode {
      background-color: #1e1e1e !important;
      color: #e9ecef !important;
      border-top: 1px solid #333;
    }

    .card {
      transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
      background-color: var(--card-light);
    }

    body.dark-mode .card {
      background-color: var(--card-dark);
      border-color: #333;
    }

    .btn-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 999;
      background-color: #000;
      color: #fff;
      border-radius: 50px;
      padding: 8px 16px;
      border: none;
      font-size: 14px;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-toggle:hover {
      background-color: #333;
      transform: scale(1.05);
    }

    /* ===== 🎨 Artwork Glow Effect ===== */
.card-img-top {
  transition: transform 0.4s ease, box-shadow 0.4s ease, filter 0.4s ease;
  border-radius: 6px;
}

/* Light mode hover: subtle zoom */
body.light-mode .card-img-top:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

/* Dark mode hover: soft white glow like a spotlight */
body.dark-mode .card-img-top:hover {
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.25), 0 0 40px rgba(255, 255, 255, 0.15);
  filter: brightness(1.1);
}

/* Optional: give images slight dim base in dark mode */
body.dark-mode .card-img-top {
  filter: brightness(0.9);
}

/* Smooth fade-in for cards when switching themes */
.card {
  transition: background-color 0.4s, color 0.4s, transform 0.3s, box-shadow 0.3s, filter 0.3s;
}

/* ===== 🏷️ Category & Muted Text Fix ===== */

/* Light mode (default gray tone) */
body.light-mode .text-muted,
body.light-mode small,
body.light-mode .card .text-muted {
  color: #6c757d !important;
}

/* Dark mode (brighter gray tone for readability) */
body.dark-mode .text-muted,
body.dark-mode small,
body.dark-mode .card .text-muted {
  color: #bdbdbd !important;
}

/* Make category tags slightly more distinct */
.card .text-muted small {
  font-weight: 500;
  letter-spacing: 0.3px;
}

/* Optional: Add subtle underline hover on category name */
body.dark-mode .card .text-muted small:hover,
body.light-mode .card .text-muted small:hover {
  text-decoration: underline;
  cursor: default;
}
  </style>
</head>

<body class="light-mode">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🎨 Arty-U</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
