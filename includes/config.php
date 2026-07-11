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
