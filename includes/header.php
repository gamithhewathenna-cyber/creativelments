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

// Per-page SEO overrides. Pages with a single item (service.php, blog-post.php) set
// $seoTitle/$seoDescription themselves before including this file; static pages fall
// back to whatever's saved in Admin → SEO for their page key.
if (empty($seoTitle))       $seoTitle       = $settings["seo_title_{$currentPage}"] ?? '';
if (empty($seoDescription)) $seoDescription = $settings["seo_meta_{$currentPage}"] ?? '';

$defaultTitle = 'Creative Elements | Digital Marketing Agency';
$defaultDesc  = 'Creative Elements helps Melbourne &amp; Sydney businesses dominate Google. Expert web design, SEO, and branding — global standards, transparent pricing.';
$titleTag = $seoTitle !== '' ? sanitize($seoTitle) : (isset($pageTitle) ? sanitize($pageTitle) . ' — ' . $defaultTitle : $defaultTitle);
$descTag  = $seoDescription !== '' ? sanitize($seoDescription) : $defaultDesc;

// Canonical URL — preserves ?slug= (the real content identifier for service.php/blog-post.php)
// but strips any other query params (utm_source, etc.) so link equity isn't split.
$reqPath = strtok($_SERVER['REQUEST_URI'], '?');
if (basename($reqPath) === 'index.php') $reqPath = rtrim(dirname($reqPath), '/') . '/';
$canonicalUrl = rtrim(SITE_URL, '/') . $reqPath;
if (!empty($_GET['slug'])) $canonicalUrl .= '?slug=' . urlencode($_GET['slug']);

// Social share image — service.php/blog-post.php set $ogImage themselves before including
// this file (their own detail/featured image); everything else falls back to the site logo.
if (empty($ogImage)) {
    $ogImage = !empty($settings['logo']) ? rtrim(SITE_URL, '/') . '/uploads/branding/' . $settings['logo'] : '';
}

// Organization/LocalBusiness structured data — shown on every page.
$orgSchema = [
    '@context' => 'https://schema.org',
    '@type'    => 'LocalBusiness',
    'name'     => SITE_NAME,
    'url'      => SITE_URL,
];
if (!empty($settings['logo']))   $orgSchema['logo']       = rtrim(SITE_URL, '/') . '/uploads/branding/' . $settings['logo'];
if (!empty($settings['phone']))  $orgSchema['telephone']  = $settings['phone'];
if (!empty($settings['email']))  $orgSchema['email']      = $settings['email'];
if (!empty($settings['address'])) {
    $orgSchema['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $settings['address']];
}
$sameAs = array_values(array_filter([$settings['facebook'] ?? '', $settings['instagram'] ?? '']));
if ($sameAs) $orgSchema['sameAs'] = $sameAs;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="google-site-verification" content="TuumGSLz5nSDiOexD4lTLhz6tcXKetlXn8zIXviHrQI" />
<?php if (!empty($settings['favicon'])): ?>
<link rel="icon" href="<?= SITE_URL ?>/uploads/branding/<?= sanitize($settings['favicon']) ?>">
<?php endif; ?>
<meta name="description" content="<?= $descTag ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
<title><?= $titleTag ?></title>
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= sanitize(SITE_NAME) ?>">
<meta property="og:title" content="<?= $titleTag ?>">
<meta property="og:description" content="<?= $descTag ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
<?php if ($ogImage): ?><meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $titleTag ?>">
<meta name="twitter:description" content="<?= $descTag ?>">
<?php if ($ogImage): ?><meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
<script type="application/ld+json"><?= json_encode($orgSchema, JSON_UNESCAPED_SLASHES) ?></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= @filemtime(__DIR__ . '/../assets/css/style.css') ?: time() ?>">
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
        <img src="<?= SITE_URL ?>/uploads/branding/<?= sanitize($settings['logo']) ?>" alt="<?= sanitize(SITE_NAME) ?>" class="logo-img">
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
