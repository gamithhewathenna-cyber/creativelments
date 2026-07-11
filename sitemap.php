<?php
// Manual endpoint: regenerates the real sitemap.xml file on disk and displays it.
// The file is normally kept up to date automatically whenever a service or blog post
// is saved (see admin/services.php and admin/posts.php) — visit this only if you ever
// need to force a refresh.
require_once 'includes/config.php';
$db = getDB();

regenerateSitemap($db);

header('Location: /sitemap.xml');
exit;
