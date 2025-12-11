<?php
// === PASSWORD HASHING TOOL ===
// 1. Change the text inside the quotes below to your desired, secure password.
$password_to_hash = "YourSecurePasswordHere"; 
    to your actual, secure Super Admin password (e.g., `"MYSUPERSECUREPASS123"`)

// 2. The password_hash function creates a strong, one-way hash.
$hashed_password = password_hash($password_to_hash, PASSWORD_DEFAULT);

// 3. Display the resulting hash.
echo "<h2>Generated Password Hash:</h2>";
echo "<p style='font-family: monospace; word-break: break-all; color: #333; background-color: #f4f4f4; padding: 10px; border: 1px solid #ccc; border-radius: 4px;'>";
echo htmlspecialchars($hashed_password);
echo "</p>";
echo "<h3>Instructions:</h3>";
echo "<ul>";
echo "<li>Copy the long string starting with '$2y$' or '$2a$'.</li>";
echo "<li>Go to your phpMyAdmin <strong>users</strong> table.</li>";
echo "<li>Paste the copied hash into the <strong>password</strong> column for your Super Admin user.</li>";
echo "</ul>";

?>