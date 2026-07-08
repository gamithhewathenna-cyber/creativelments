<?php
require_once __DIR__ . '/../includes/config.php';
$db = getDB();

// ⚠️ ONE-TIME USE ONLY — delete this file from the server immediately after running it once.

$newUsername = 'admin';
$newPassword = 'Admin@1234'; // change this string if you want a different password

$hash = password_hash($newPassword, PASSWORD_BCRYPT);

$stmt = $db->prepare("SELECT id FROM admin_users WHERE username = ?");
$stmt->execute([$newUsername]);
$exists = $stmt->fetch();

if ($exists) {
    $db->prepare("UPDATE admin_users SET password_hash=? WHERE username=?")->execute([$hash, $newUsername]);
    echo "Existing admin user '$newUsername' password has been reset.<br>";
} else {
    $db->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)")
       ->execute([$newUsername, $hash, ADMIN_EMAIL]);
    echo "Admin user '$newUsername' created.<br>";
}

echo "You can now log in at <a href='/admin/login.php'>/admin/login.php</a> with:<br>";
echo "Username: <strong>" . htmlspecialchars($newUsername) . "</strong><br>";
echo "Password: <strong>" . htmlspecialchars($newPassword) . "</strong><br><br>";
echo "<strong style='color:red'>Delete this file (admin/reset-password.php) from the server right now.</strong>";
