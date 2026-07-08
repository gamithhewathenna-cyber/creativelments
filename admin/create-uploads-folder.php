<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

// ⚠️ ONE-TIME USE ONLY — delete this file from the server immediately after running it once.

$dir = __DIR__ . '/../uploads/projects';

if (!is_dir($dir)) {
    if (mkdir($dir, 0755, true)) {
        echo "Created uploads/projects folder.<br>";
    } else {
        echo "<strong style='color:red'>Failed to create the folder.</strong> The parent 'uploads' folder itself may not be writable — check its permissions in cPanel File Manager.<br>";
    }
} else {
    echo "Folder already existed.<br>";
}

if (is_dir($dir)) {
    chmod($dir, 0755);
    echo "Permissions set to 755.<br>";
    echo is_writable($dir) ? "Confirmed: the folder is now writable.<br>" : "<strong style='color:red'>Warning: the folder still isn't writable — your host may require a different permission or ownership setup. Contact your hosting support if uploads keep failing.</strong><br>";
}

echo "<br><strong style='color:red'>Delete this file (admin/create-uploads-folder.php) from the server right now.</strong>";
