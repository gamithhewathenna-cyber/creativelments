<?php
require_once '../includes/config.php';
requireLogin();

// Admin pages must always be fetched fresh — never served from browser/back-forward cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$db = getDB();

// Unread enquiries count
$newEnquiries = $db->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn();
$currentPage  = basename($_SERVER['PHP_SELF'], '.php');
$siteLogo     = $db->query("SELECT setting_value FROM settings WHERE setting_key='logo'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow, noarchive">
<title><?= isset($adminTitle) ? htmlspecialchars($adminTitle) . ' — ' : '' ?>Admin — Creative Elements</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Poppins',sans-serif;background:#F1F5F9;color:#313131;display:flex;min-height:100vh}
/* Sidebar */
.sidebar{width:240px;background:#0A0F1E;display:flex;flex-direction:column;flex-shrink:0;min-height:100vh;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-logo{padding:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:.75rem}
.lm{width:38px;height:38px;background:#804899;border-radius:8px;display:grid;place-items:center;font-family:'Poppins',sans-serif;font-weight:800;color:white;font-size:.9rem;flex-shrink:0}
.lt{font-family:'Poppins',sans-serif;font-size:.8rem;font-weight:700;color:white;line-height:1.2}
.lt em{font-style:normal;color:#5bc1c1}
.sidebar-nav{padding:1rem 0;flex:1}
.nav-section{padding:.5rem 1rem .25rem;font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#4B5563}
.sidebar a{display:flex;align-items:center;gap:.7rem;padding:.65rem 1.25rem;color:rgba(255,255,255,.6);font-size:.85rem;font-weight:500;text-decoration:none;transition:all .2s;border-left:3px solid transparent}
.sidebar a:hover,.sidebar a.active{color:white;background:rgba(128,72,153,.12);border-left-color:#804899}
.badge{background:#EF4444;color:white;font-size:.65rem;font-weight:700;padding:.15rem .45rem;border-radius:100px;margin-left:auto}
.sidebar-footer{padding:1.25rem;border-top:1px solid rgba(255,255,255,.07)}
.sidebar-footer a{color:#4B5563;font-size:.8rem;text-decoration:none}
.sidebar-footer a:hover{color:white}

/* Main */
.main{flex:1;display:flex;flex-direction:column;min-width:0}
.topbar-admin{background:white;border-bottom:1px solid #E2E8F0;padding:.85rem 2rem;display:flex;align-items:center;justify-content:space-between;gap:1rem}
.topbar-admin h1{font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:#804899}
.admin-user{display:flex;align-items:center;gap:.75rem;font-size:.85rem;color:#313131}
.user-avatar{width:32px;height:32px;background:#804899;border-radius:50%;display:grid;place-items:center;color:white;font-weight:700;font-size:.8rem}

.content{padding:2rem;flex:1}

/* Cards */
.card{background:white;border-radius:12px;border:1px solid #E2E8F0;overflow:hidden;margin-bottom:1.5rem}
.card-header{padding:1.25rem 1.5rem;border-bottom:1px solid #E2E8F0;display:flex;align-items:center;justify-content:space-between;gap:1rem}
.card-header h2{font-size:1rem;font-weight:700;color:#804899}
.card-body{padding:1.5rem}

/* Forms */
.form-group{margin-bottom:1.25rem}
.form-group label{display:block;font-size:.82rem;font-weight:600;color:#374151;margin-bottom:.4rem}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:.7rem 1rem;border:1.5px solid #E2E8F0;border-radius:8px;font-family:'Poppins',sans-serif;font-size:.9rem;color:#313131;background:white;outline:none;transition:border-color .2s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#804899;box-shadow:0 0 0 3px rgba(128,72,153,.1)}
.form-group textarea{min-height:140px;resize:vertical}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}

/* Buttons */
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1.35rem;border-radius:8px;font-weight:600;font-size:.85rem;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
.btn-primary{background:#804899;color:white}
.btn-primary:hover{background:#ff9243}
.btn-danger{background:#EF4444;color:white}
.btn-danger:hover{background:#DC2626}
.btn-sm{padding:.45rem 1rem;font-size:.8rem}
.btn-outline{background:transparent;color:#374151;border:1.5px solid #E2E8F0}
.btn-outline:hover{border-color:#ff9243;color:#ff9243}

/* Table */
table{width:100%;border-collapse:collapse}
th{padding:.75rem 1rem;text-align:left;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#313131;background:#F8FAFC;border-bottom:1px solid #E2E8F0}
td{padding:.85rem 1rem;font-size:.88rem;border-bottom:1px solid #F1F5F9;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#F8FAFC}

/* Alert */
.alert{padding:.85rem 1.25rem;border-radius:8px;font-size:.88rem;font-weight:500;margin-bottom:1.25rem}
.alert-success{background:#D1FAE5;color:#065F46}
.alert-error{background:#FEE2E2;color:#991B1B}

/* Status pills */
.pill{display:inline-flex;align-items:center;padding:.2rem .7rem;border-radius:100px;font-size:.75rem;font-weight:600}
.pill-green{background:#D1FAE5;color:#065F46}
.pill-yellow{background:#FEF3C7;color:#92400E}
.pill-red{background:#FEE2E2;color:#991B1B}
.pill-blue{background:#DBEAFE;color:#1E40AF}

/* Stat cards */
.stat-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:1.5rem}
.stat-card{background:white;border:1px solid #E2E8F0;border-radius:12px;padding:1.5rem}
.stat-card .num{font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:#ff9243}
.stat-card .label{font-size:.82rem;color:#313131;margin-top:.25rem}

@media(max-width:900px){.sidebar{width:200px}.stat-cards{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.sidebar{display:none}.stat-cards{grid-template-columns:1fr}}
</style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <?php if (!empty($siteLogo)): ?>
      <img src="/uploads/branding/<?= htmlspecialchars($siteLogo) ?>" alt="Logo" style="width:170px;height:35px;object-fit:contain">
    <?php else: ?>
      <div class="lm">CE</div>
      <div class="lt">Creative<br><em>Elements</em></div>
    <?php endif; ?>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Main</div>
    <a href="/admin/dashboard.php" class="<?= $currentPage==='dashboard'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="/admin/enquiries.php" class="<?= $currentPage==='enquiries'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      Enquiries
      <?php if ($newEnquiries > 0): ?><span class="badge"><?= $newEnquiries ?></span><?php endif; ?>
    </a>

    <div class="nav-section">Content</div>
    <a href="/admin/sliders.php" class="<?= $currentPage==='sliders'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
      Hero Slider
    </a>
    <a href="/admin/projects.php" class="<?= $currentPage==='projects'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
      Projects
    </a>
    <a href="/admin/services.php" class="<?= $currentPage==='services'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      Services
    </a>
    <a href="/admin/testimonials.php" class="<?= $currentPage==='testimonials'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Testimonials
    </a>
    <a href="/admin/client-logos.php" class="<?= $currentPage==='client-logos'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Client Logos
    </a>
    <a href="/admin/posts.php" class="<?= $currentPage==='posts'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Blog Posts
    </a>
    <a href="/admin/settings.php" class="<?= $currentPage==='settings'?'active':'' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Settings
    </a>
  </nav>
  <div class="sidebar-footer">
    <a href="/admin/logout.php">← Logout</a>&nbsp;&nbsp;
    <a href="/" target="_blank">View Site</a>
  </div>
</aside>

<!-- Main content -->
<div class="main">
  <div class="topbar-admin">
    <h1><?= $adminTitle ?? 'Dashboard' ?></h1>
    <div class="admin-user">
      <div class="user-avatar"><?= strtoupper(substr($_SESSION['admin_user'] ?? 'A', 0, 1)) ?></div>
      <span><?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?></span>
    </div>
  </div>
  <div class="content">
