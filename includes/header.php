<?php
require_once __DIR__ . '/../includes/config.php';
$db = getDB();

// Load settings
$settingsRaw = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
$settings = [];
foreach ($settingsRaw as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Creative Elements helps Melbourne &amp; Sydney businesses dominate Google. Expert web design, SEO, and branding — global standards, transparent pricing.">
<title><?= isset($pageTitle) ? sanitize($pageTitle) . ' — ' : '' ?>Creative Elements | Digital Marketing Agency</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="page-<?= sanitize($currentPage) ?>">

<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>

<header class="site-header">
<!-- Navigation -->
<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <a href="/" class="logo">
      <?php if (!empty($settings['logo'])): ?>
        <img src="/uploads/branding/<?= sanitize($settings['logo']) ?>" alt="<?= sanitize(SITE_NAME) ?>" class="logo-img">
      <?php else: ?>
        <span class="logo-mark">CE</span>
        <span class="logo-text">Creative<br><em>Elements</em></span>
      <?php endif; ?>
    </a>
    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
    <ul class="nav-menu" id="navMenu">
      <li><a href="/" class="<?= $currentPage === 'index' ? 'active' : '' ?>">Home</a></li>
      <li><a href="/our-work.php" class="<?= $currentPage === 'our-work' ? 'active' : '' ?>">Our Work</a></li>
      <li><a href="/services.php" class="<?= $currentPage === 'services' ? 'active' : '' ?>">Services</a></li>
      <li><a href="/blog.php" class="<?= $currentPage === 'blog' ? 'active' : '' ?>">Blog</a></li>
      <li><a href="/about.php" class="<?= $currentPage === 'about' ? 'active' : '' ?>">About</a></li>
      <li><a href="/contact.php" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Contact</a></li>
    </ul>
  </div>
</nav>
</header>
