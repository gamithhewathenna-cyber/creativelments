<?php
$adminTitle = 'Settings';
require_once 'admin-header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = ['phone','email','address','facebook','instagram','whatsapp','hero_title','hero_subtitle','unique_section_text'];
    foreach ($keys as $key) {
        $val = trim($_POST[$key] ?? '');
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$key,$val,$val]);
    }

    // Logo upload
    if (!empty($_FILES['logo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp','svg'])) {
            $newName = 'logo_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/branding/' . $newName;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('logo',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            }
        }
    } elseif (!empty($_POST['remove_logo'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('logo','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
    }

    // Footer logo (white version) upload
    if (!empty($_FILES['footer_logo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['footer_logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp','svg'])) {
            $newName = 'footerlogo_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/branding/' . $newName;
            if (move_uploaded_file($_FILES['footer_logo']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('footer_logo',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            }
        }
    } elseif (!empty($_POST['remove_footer_logo'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('footer_logo','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
    }

    // Growth CTA banner image upload
    if (!empty($_FILES['cta_banner']['name'])) {
        $ext = strtolower(pathinfo($_FILES['cta_banner']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $newName = 'cta_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/banners/' . $newName;
            if (move_uploaded_file($_FILES['cta_banner']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('cta_banner_image',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            }
        }
    } elseif (!empty($_POST['remove_cta_banner'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('cta_banner_image','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
    }

    // "What Makes Us Unique" section image upload
    if (!empty($_FILES['unique_image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['unique_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $newName = 'unique_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/sections/' . $newName;
            if (move_uploaded_file($_FILES['unique_image']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('unique_section_image',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            }
        }
    } elseif (!empty($_POST['remove_unique_image'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('unique_section_image','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
    }

    // "Global Standards, Local Understanding" section image upload
    if (!empty($_FILES['why_us_image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['why_us_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $newName = 'whyus_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/sections/' . $newName;
            if (move_uploaded_file($_FILES['why_us_image']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('why_us_image',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            }
        }
    } elseif (!empty($_POST['remove_why_us_image'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('why_us_image','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
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

<form method="POST" enctype="multipart/form-data">
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Branding</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Current Logo</label>
      <?php if (!empty($settings['logo'])): ?>
        <div style="margin:.5rem 0 1rem"><img src="/uploads/branding/<?= sanitize($settings['logo']) ?>" alt="Current logo" style="max-height:60px;max-width:220px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No logo uploaded yet — the site is currently using the default "CE" text logo.</p>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label>Upload New Logo</label>
      <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml">
      <small style="color:#8892A4;display:block;margin-top:.4rem">PNG, JPG, WEBP or SVG. Transparent background recommended, ~60px tall.</small>
    </div>
    <?php if (!empty($settings['logo'])): ?>
    <div class="form-group">
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500">
        <input type="checkbox" name="remove_logo" value="1" style="width:auto"> Remove logo and use the default text logo instead
      </label>
    </div>
    <?php endif; ?>

    <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">

    <div class="form-group">
      <label>Current Footer Logo (White Version)</label>
      <?php if (!empty($settings['footer_logo'])): ?>
        <div style="background:#0A0F1E;padding:1rem;border-radius:8px;margin:.5rem 0 1rem;display:inline-block"><img src="/uploads/branding/<?= sanitize($settings['footer_logo']) ?>" alt="Current footer logo" style="max-height:60px;max-width:220px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No footer logo uploaded yet — the footer is currently using the default white "CE" text logo.</p>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label>Upload New Footer Logo</label>
      <input type="file" name="footer_logo" accept="image/png,image/jpeg,image/webp,image/svg+xml">
      <small style="color:#8892A4;display:block;margin-top:.4rem">Use a white or light-colored version of your logo — the footer background is dark navy. Transparent PNG or SVG recommended.</small>
    </div>
    <?php if (!empty($settings['footer_logo'])): ?>
    <div class="form-group" style="margin-bottom:0">
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500">
        <input type="checkbox" name="remove_footer_logo" value="1" style="width:auto"> Remove footer logo and use the default text logo instead
      </label>
    </div>
    <?php endif; ?>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Growth CTA Banner</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Current Banner Image</label>
      <?php if (!empty($settings['cta_banner_image'])): ?>
        <div style="margin:.5rem 0 1rem"><img src="/uploads/banners/<?= sanitize($settings['cta_banner_image']) ?>" alt="Current banner" style="max-height:100px;max-width:100%;border-radius:8px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No banner uploaded yet — the "Ready to grow?" section will show a plain dark background until you add one.</p>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label>Upload New Banner Image</label>
      <input type="file" name="cta_banner" accept="image/png,image/jpeg,image/webp">
      <small style="color:#8892A4;display:block;margin-top:.4rem">A wide landscape photo works best — it displays full-width behind the "Ready to grow?" text.</small>
    </div>
    <?php if (!empty($settings['cta_banner_image'])): ?>
    <div class="form-group" style="margin-bottom:0">
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500">
        <input type="checkbox" name="remove_cta_banner" value="1" style="width:auto"> Remove banner image
      </label>
    </div>
    <?php endif; ?>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>"What Makes Us Unique" Section</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Paragraph Text</label>
      <textarea name="unique_section_text" style="min-height:140px"><?= sanitize($settings['unique_section_text'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label>Current Image</label>
      <?php if (!empty($settings['unique_section_image'])): ?>
        <div style="margin:.5rem 0 1rem"><img src="/uploads/sections/<?= sanitize($settings['unique_section_image']) ?>" alt="Current image" style="max-height:160px;border-radius:8px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No image uploaded yet — the homepage section will show a placeholder box until you add one.</p>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label>Upload New Image</label>
      <input type="file" name="unique_image" accept="image/png,image/jpeg,image/webp">
      <small style="color:#8892A4;display:block;margin-top:.4rem">A cutout-style photo (transparent PNG) works best for the "What Makes Us Unique?" homepage section.</small>
    </div>
    <?php if (!empty($settings['unique_section_image'])): ?>
    <div class="form-group" style="margin-bottom:0">
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500">
        <input type="checkbox" name="remove_unique_image" value="1" style="width:auto"> Remove image
      </label>
    </div>
    <?php endif; ?>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>"Global Standards, Local Understanding" Image</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Current Image</label>
      <?php if (!empty($settings['why_us_image'])): ?>
        <div style="margin:.5rem 0 1rem"><img src="/uploads/sections/<?= sanitize($settings['why_us_image']) ?>" alt="Current image" style="max-height:160px;border-radius:8px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No image uploaded yet — the homepage section will show a placeholder box until you add one.</p>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label>Upload New Image</label>
      <input type="file" name="why_us_image" accept="image/png,image/jpeg,image/webp">
      <small style="color:#8892A4;display:block;margin-top:.4rem">Replaces the dark box next to "Global Standards, Local Understanding" on the homepage.</small>
    </div>
    <?php if (!empty($settings['why_us_image'])): ?>
    <div class="form-group" style="margin-bottom:0">
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500">
        <input type="checkbox" name="remove_why_us_image" value="1" style="width:auto"> Remove image
      </label>
    </div>
    <?php endif; ?>
  </div>
</div>

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
