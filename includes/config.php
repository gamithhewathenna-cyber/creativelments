<?php
// ============================================================
// DATABASE CONFIGURATION — Edit these before uploading
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'creaeina_creativelementsadmin');   // CPanel DB username
define('DB_PASS', 'n!GtNk)$#tG#Pl{+');                // CPanel DB password
define('DB_NAME', 'creaeina_creativelements');        // CPanel DB name

// ============================================================
// SITE CONFIGURATION
// ============================================================
define('SITE_URL', 'http://creative.creativelements-dev.info');   // Your domain
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

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

session_start();
