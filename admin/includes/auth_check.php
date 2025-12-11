<?php
// Start the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- AUTO LOGOUT AFTER 30 MINUTES OF INACTIVITY ---
$timeout_duration = 30 * 60; // 30 minutes in seconds

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session expired, destroy it
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=1");
    exit;
}

// Update last activity timestamp
$_SESSION['LAST_ACTIVITY'] = time();

// --- CHECK IF ADMIN IS LOGGED IN ---
// 1. Check if the admin username is set
// 2. Check if the 'role' is strictly set to 'admin' (matches index.php change)
if (!isset($_SESSION['admin']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?error=unauthorized");
    exit;
}
?>