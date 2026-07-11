<?php
// TEMPORARY DEBUG — remove once the blank-page issue is found and fixed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ============================================================
// DATABASE CONFIGURATION — Edit these before uploading
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'matsaqyg_creativelementsadmin26');   // CPanel DB username
define('DB_PASS', 'Pldo^?rJlY-;,&N%');                  // CPanel DB password
define('DB_NAME', 'matsaqyg_creativelements2026');      // CPanel DB name

// ============================================================
// SITE CONFIGURATION
// ============================================================
define('SITE_URL', 'https://creativelements.co');   // Your domain
define('SITE_NAME', 'Creative Elements');
define('ADMIN_EMAIL', 'reach@creativelements.co');
define('WHATSAPP_NUMBER', '94777130597');

// ============================================================
// DATABASE CONNECTION
// ============================================================
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

// ============================================================
// HELPERS
// ============================================================
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Same as sanitize(), but allows a literal <br> to pass through as a real line break.
function sanitizeBr($input) {
    $escaped = htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    return preg_replace('/&lt;br\s*\/?&gt;/i', '<br>', $escaped);
}

// Resizes+crops an uploaded image to exact target dimensions (cover-fit, no distortion).
function resizeCoverCrop($srcPath, $destPath, $targetW, $targetH, $ext) {
    if (!extension_loaded('gd')) return false;

    $size = @getimagesize($srcPath);
    if (!$size) return false;
    [$srcW, $srcH] = $size;

    switch ($ext) {
        case 'jpg': case 'jpeg': $srcImg = @imagecreatefromjpeg($srcPath); break;
        case 'png':              $srcImg = @imagecreatefrompng($srcPath); break;
        case 'webp':              $srcImg = @imagecreatefromwebp($srcPath); break;
        case 'gif':               $srcImg = @imagecreatefromgif($srcPath); break;
        default: return false;
    }
    if (!$srcImg) return false;

    $srcRatio    = $srcW / $srcH;
    $targetRatio = $targetW / $targetH;
    if ($srcRatio > $targetRatio) {
        $cropH = $srcH;
        $cropW = (int) round($srcH * $targetRatio);
        $cropX = (int) round(($srcW - $cropW) / 2);
        $cropY = 0;
    } else {
        $cropW = $srcW;
        $cropH = (int) round($srcW / $targetRatio);
        $cropX = 0;
        $cropY = (int) round(($srcH - $cropH) / 2);
    }

    $dstImg = imagecreatetruecolor($targetW, $targetH);
    if (in_array($ext, ['png', 'webp', 'gif'])) {
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 0, 0, 0, 127);
        imagefilledrectangle($dstImg, 0, 0, $targetW, $targetH, $transparent);
    }

    imagecopyresampled($dstImg, $srcImg, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);

    $ok = false;
    switch ($ext) {
        case 'jpg': case 'jpeg': $ok = imagejpeg($dstImg, $destPath, 90); break;
        case 'png':               $ok = imagepng($dstImg, $destPath); break;
        case 'webp':              $ok = imagewebp($dstImg, $destPath, 90); break;
        case 'gif':               $ok = imagegif($dstImg, $destPath); break;
    }

    imagedestroy($srcImg);
    imagedestroy($dstImg);
    return $ok;
}

// Builds the sitemap XML from current DB content (static pages + active services + published posts).
function buildSitemapXml($db) {
    $urlFn = function ($loc, $lastmod = null, $changefreq = 'weekly', $priority = '0.7') {
        $xml  = "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
        if ($lastmod) {
            $xml .= '    <lastmod>' . date('Y-m-d', strtotime($lastmod)) . "</lastmod>\n";
        }
        $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
        $xml .= "    <priority>{$priority}</priority>\n";
        $xml .= "  </url>\n";
        return $xml;
    };

    $services = $db->query("SELECT slug, created_at FROM services WHERE active=1 AND slug IS NOT NULL AND slug != ''")->fetchAll();
    $posts    = $db->query("SELECT slug, created_at FROM posts WHERE status='published'")->fetchAll();

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    $xml .= $urlFn(SITE_URL . '/', null, 'weekly', '1.0');
    $xml .= $urlFn(SITE_URL . '/about.php', null, 'monthly', '0.8');
    $xml .= $urlFn(SITE_URL . '/services.php', null, 'monthly', '0.9');
    $xml .= $urlFn(SITE_URL . '/our-work.php', null, 'weekly', '0.8');
    $xml .= $urlFn(SITE_URL . '/blog.php', null, 'weekly', '0.7');
    $xml .= $urlFn(SITE_URL . '/contact.php', null, 'monthly', '0.6');
    foreach ($services as $s) {
        $xml .= $urlFn(SITE_URL . '/service.php?slug=' . urlencode($s['slug']), $s['created_at'] ?? null, 'monthly', '0.7');
    }
    foreach ($posts as $p) {
        $xml .= $urlFn(SITE_URL . '/blog-post.php?slug=' . urlencode($p['slug']), $p['created_at'] ?? null, 'monthly', '0.6');
    }
    $xml .= "</urlset>\n";
    return $xml;
}

// Regenerates the real, physical sitemap.xml file on disk — called after any content
// change that affects it (services, blog posts), so no server rewrite rule is needed.
function regenerateSitemap($db) {
    file_put_contents(__DIR__ . '/../sitemap.xml', buildSitemapXml($db));
}

// Renders a breadcrumb trail (visible nav + BreadcrumbList schema for SEO).
// $items: [['label' => 'Home', 'url' => '/'], ..., ['label' => 'Current Page', 'url' => null]]
function renderBreadcrumbs($items) {
    $html = '<div class="breadcrumbs"><div class="container"><ol>';
    $last = count($items) - 1;
    foreach ($items as $i => $item) {
        if ($i > 0) $html .= '<li class="breadcrumb-sep">/</li>';
        if (!empty($item['url']) && $i !== $last) {
            $html .= '<li><a href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</a></li>';
        } else {
            $html .= '<li aria-current="page">' . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</li>';
        }
    }
    $html .= '</ol></div></div>';

    $ld = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => []];
    foreach ($items as $i => $item) {
        $ld['itemListElement'][] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $item['label'],
            'item'     => rtrim(SITE_URL, '/') . ($item['url'] ?? $_SERVER['REQUEST_URI']),
        ];
    }
    $html .= '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES) . '</script>';
    return $html;
}

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

// 'admin' has full access; 'editor' is a content-only role (no Settings/Users pages).
function isAdmin() {
    return ($_SESSION['admin_role'] ?? 'admin') === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /admin/dashboard.php?error=forbidden');
        exit;
    }
}

session_start();

// Show a readable error instead of a silent blank page when something fails
// (production PHP normally has display_errors off, which hides fatal errors entirely).
set_exception_handler(function ($e) {
    http_response_code(500);
    echo '<div style="font-family:sans-serif;max-width:800px;margin:2rem auto;padding:1.5rem;background:#FEE2E2;color:#991B1B;border-radius:8px;border:1px solid #FCA5A5">'
       . '<strong>Something went wrong:</strong><br>' . htmlspecialchars($e->getMessage())
       . '<br><small>' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</small></div>';
});
