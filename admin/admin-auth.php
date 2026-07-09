<?php
// Auth + DB setup only — produces NO HTML output, so pages that require this
// first are free to run header('Location: ...') redirects (delete/toggle/save)
// before admin-header.php prints anything to the browser.
require_once '../includes/config.php';
requireLogin();
if (!empty($requireAdminRole)) requireAdmin();

// Admin pages must always be fetched fresh — never served from browser/back-forward cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$db = getDB();

// Load all settings so every admin page can read $settings['key'] — this must happen
// on every request (not just after a POST save), otherwise fields show blank/default
// the next time you visit the page even though the database has the saved value.
$settingsRaw = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
$settings = [];
foreach ($settingsRaw as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$adminAuthLoaded = true;
