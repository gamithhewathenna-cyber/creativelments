<?php
$adminTitle = 'SEO';
require_once 'admin-auth.php';

// $currentPage values these pages resolve to via includes/header.php
$pages = [
    'index'     => 'Home',
    'about'     => 'About Us',
    'services'  => 'Services (listing page)',
    'our-work'  => 'Our Work',
    'blog'      => 'Blog (listing page)',
    'contact'   => 'Contact',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($pages) as $key) {
        foreach (['keyphrase', 'title', 'meta'] as $field) {
            $settingKey = "seo_{$field}_{$key}";
            $val = trim($_POST[$settingKey] ?? '');
            $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$settingKey, $val, $val]);
            $settings[$settingKey] = $val;
        }
    }
    header('Location: /admin/seo.php?msg=saved');
    exit;
}

require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success">SEO settings saved.</div>
<?php endif; ?>

<p style="color:#313131;font-size:.88rem;margin-bottom:1.5rem">
  Set the Google search result title and description for each page. Leave a field blank to use the site default.
  Individual services and blog posts have their own SEO fields on their own edit forms (Admin → Services / Blog Posts).
</p>

<form method="POST">
<?php foreach ($pages as $key => $label): ?>
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= sanitize($label) ?></h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Focus Keyphrase</label>
      <input name="seo_keyphrase_<?= $key ?>" value="<?= sanitize($settings["seo_keyphrase_{$key}"] ?? '') ?>" placeholder="e.g. web design melbourne">
      <small style="color:#8892A4;display:block;margin-top:.4rem">For your own reference when writing page content — not shown on the page.</small>
    </div>
    <div class="form-group">
      <label>SEO Title</label>
      <input name="seo_title_<?= $key ?>" value="<?= sanitize($settings["seo_title_{$key}"] ?? '') ?>" placeholder="Shown as the browser tab title and Google result title">
    </div>
    <div class="form-group" style="margin-bottom:0">
      <label>Meta Description</label>
      <textarea name="seo_meta_<?= $key ?>" style="min-height:80px" placeholder="Shown under the title in Google search results"><?= sanitize($settings["seo_meta_{$key}"] ?? '') ?></textarea>
    </div>
  </div>
</div>
<?php endforeach; ?>
<button type="submit" class="btn btn-primary">Save SEO Settings</button>
</form>

<?php require_once 'admin-footer.php'; ?>
