<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error = '';
$db = getDB();
$siteLogo = $db->query("SELECT setting_value FROM settings WHERE setting_key='logo'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $user['username'];
            $_SESSION['admin_role']      = $user['role'] ?? 'admin';
            // update last login
            $db->prepare("UPDATE admin_users SET last_login=NOW() WHERE id=?")->execute([$user['id']]);
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow, noarchive">
<title>Admin Login — Creative Elements</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Poppins',sans-serif;background:#0A0F1E;min-height:100vh;display:grid;place-items:center}
.login-box{background:#111827;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:2.5rem;width:100%;max-width:420px;margin:1.5rem}
.logo{display:flex;align-items:center;gap:.75rem;margin-bottom:2rem;justify-content:center}
.logo-mark{width:48px;height:48px;background:#804899;color:white;border-radius:10px;display:grid;place-items:center;font-family:'Poppins',sans-serif;font-weight:800;font-size:1rem}
.logo-text{font-family:'Poppins',sans-serif;font-size:.9rem;font-weight:700;color:white;line-height:1.2}
.logo-text em{font-style:normal;color:#5bc1c1}
h2{font-family:'Poppins',sans-serif;color:white;font-size:1.4rem;margin-bottom:.4rem}
p.sub{color:#8892A4;font-size:.88rem;margin-bottom:2rem}
.form-group{margin-bottom:1.25rem}
label{display:block;font-size:.82rem;font-weight:600;color:#8892A4;margin-bottom:.4rem}
input{width:100%;padding:.8rem 1rem;background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.1);border-radius:8px;color:white;font-family:'Poppins',sans-serif;font-size:.9rem;outline:none;transition:border-color .2s}
input:focus{border-color:#804899;box-shadow:0 0 0 3px rgba(128,72,153,.15)}
input::placeholder{color:#4B5563}
.btn{width:100%;padding:1rem;background:#804899;color:white;border:none;border-radius:8px;font-family:'Poppins',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;transition:background .2s}
.btn:hover{background:#ff9243}
.alert{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#FCA5A5;padding:.85rem 1rem;border-radius:8px;font-size:.88rem;margin-bottom:1.25rem}
a{color:#804899;text-decoration:none;font-size:.82rem}
.back{display:block;text-align:center;margin-top:1.25rem}
</style>
</head>
<body>
<div class="login-box">
  <div class="logo">
    <?php if (!empty($siteLogo)): ?>
      <img src="/uploads/branding/<?= htmlspecialchars($siteLogo) ?>" alt="Logo" style="width:280px;height:57px;object-fit:contain">
    <?php else: ?>
      <div class="logo-mark">CE</div>
      <div class="logo-text">Creative<br><em>Elements</em></div>
    <?php endif; ?>
  </div>
  <h2>Admin Login</h2>
  <p class="sub">Sign in to manage your website</p>
  <?php if ($error): ?>
  <div class="alert"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn">Sign In</button>
  </form>
  <a href="/" class="back">← Back to Website</a>
</div>
</body>
</html>
