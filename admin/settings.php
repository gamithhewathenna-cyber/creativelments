<?php
$adminTitle = 'Settings';
require_once 'admin-header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = ['phone','email','address','facebook','instagram','whatsapp','hero_title','hero_subtitle','about_text'];
    foreach ($keys as $key) {
        $val = trim($_POST[$key] ?? '');
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$key,$val,$val]);
    }
    // Change password
    if (!empty($_POST['new_password']) && !empty($_POST['current_password'])) {
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username=?");
        $stmt->execute([$_SESSION['admin_user']]);
        $user = $stmt->fetch();
        if ($user && password_verify($_POST['current_password'], $user['password_hash'])) {
            $hash = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $db->prepare("UPDATE admin_users SET password_hash=? WHERE id=?")->execute([$hash, $user['id']]);
            $msg = 'Settings and password saved.';
        } else {
            $msg = 'Settings saved. (Current password incorrect — password not changed)';
        }
    } else {
        $msg = 'Settings saved.';
    }
    // Reload settings
    $rows = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
    foreach ($rows as $r) { $settings[$r['setting_key']] = $r['setting_value']; }
}

if (isset($msg)): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<form method="POST">
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Contact & Social</h2></div>
  <div class="card-body">
    <div class="form-row">
      <div class="form-group"><label>Phone</label><input name="phone" value="<?= sanitize($settings['phone'] ?? '') ?>"></div>
      <div class="form-group"><label>Email</label><input name="email" type="email" value="<?= sanitize($settings['email'] ?? '') ?>"></div>
    </div>
    <div class="form-group"><label>Address</label><input name="address" value="<?= sanitize($settings['address'] ?? '') ?>"></div>
    <div class="form-row">
      <div class="form-group"><label>WhatsApp Number (digits only)</label><input name="whatsapp" value="<?= sanitize($settings['whatsapp'] ?? '') ?>"></div>
      <div class="form-group"><label>Facebook URL</label><input name="facebook" value="<?= sanitize($settings['facebook'] ?? '') ?>"></div>
    </div>
    <div class="form-group"><label>Instagram URL</label><input name="instagram" value="<?= sanitize($settings['instagram'] ?? '') ?>"></div>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Homepage Content</h2></div>
  <div class="card-body">
    <div class="form-group"><label>Hero Title</label><input name="hero_title" value="<?= sanitize($settings['hero_title'] ?? '') ?>"></div>
    <div class="form-group"><label>Hero Subtitle</label><input name="hero_subtitle" value="<?= sanitize($settings['hero_subtitle'] ?? '') ?>"></div>
    <div class="form-group"><label>About Text</label><textarea name="about_text"><?= sanitize($settings['about_text'] ?? '') ?></textarea></div>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Change Admin Password</h2></div>
  <div class="card-body">
    <div class="form-row">
      <div class="form-group"><label>Current Password</label><input type="password" name="current_password" autocomplete="current-password"></div>
      <div class="form-group"><label>New Password</label><input type="password" name="new_password" autocomplete="new-password"></div>
    </div>
  </div>
</div>

<button type="submit" class="btn btn-primary">Save All Settings</button>
</form>

<?php require_once 'admin-footer.php'; ?>
