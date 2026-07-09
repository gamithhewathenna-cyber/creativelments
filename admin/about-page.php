<?php
$adminTitle = 'About Page';
require_once 'admin-auth.php';

$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = [
        'about_hero_heading',
        'about_story_heading', 'about_text',
        'about_founder_text',
        'about_reasons_heading',
        'reason1_title', 'reason1_desc',
        'reason2_title', 'reason2_desc',
        'reason3_title', 'reason3_desc',
        'reason4_title', 'reason4_desc',
        'about_cta_heading', 'about_cta_text',
    ];
    foreach ($keys as $key) {
        $val = trim($_POST[$key] ?? '');
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$key,$val,$val]);
    }

    // Agency Story image upload
    if (!empty($_FILES['about_story_image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['about_story_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $formError = 'Agency Story image must be a JPG, PNG or WEBP file.';
        } else {
            $newName = 'aboutstory_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/sections/' . $newName;
            if (move_uploaded_file($_FILES['about_story_image']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('about_story_image',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            } else {
                $formError = 'Agency Story image upload failed. Please try again.';
            }
        }
    } elseif (!empty($_POST['remove_about_story_image'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('about_story_image','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
    }

    // Founder Story image upload
    if (!empty($_FILES['about_founder_image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['about_founder_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $formError = 'Founder Story image must be a JPG, PNG or WEBP file.';
        } else {
            $newName = 'aboutfounder_' . uniqid() . '.' . $ext;
            $dest    = '../uploads/sections/' . $newName;
            if (move_uploaded_file($_FILES['about_founder_image']['tmp_name'], $dest)) {
                $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('about_founder_image',?) ON DUPLICATE KEY UPDATE setting_value=?")->execute([$newName,$newName]);
            } else {
                $formError = 'Founder Story image upload failed. Please try again.';
            }
        }
    } elseif (!empty($_POST['remove_about_founder_image'])) {
        $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES ('about_founder_image','') ON DUPLICATE KEY UPDATE setting_value=''")->execute();
    }

    if (!$formError) {
        header('Location: /admin/about-page.php?msg=saved');
        exit;
    }

    // Reload settings so the form redisplays what was just typed/saved despite the image error
    $rows = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
    foreach ($rows as $r) { $settings[$r['setting_key']] = $r['setting_value']; }
}

require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success">About page saved successfully.</div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Page Hero</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Heading</label>
      <input name="about_hero_heading" value="<?= sanitize($settings['about_hero_heading'] ?? 'The Digital Agency Melbourne & Sydney Businesses Trust to Grow Online') ?>">
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Our Agency Story</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Section Heading</label>
      <input name="about_story_heading" value="<?= sanitize($settings['about_story_heading'] ?? 'From Colombo to Melbourne — How We Became the Agency Australian Businesses Rely On') ?>">
    </div>
    <div class="form-group">
      <label>Body Text</label>
      <textarea name="about_text" style="min-height:180px"><?= sanitize($settings['about_text'] ?? '') ?></textarea>
      <small style="color:#8892A4;display:block;margin-top:.4rem">Press Enter for a new paragraph.</small>
    </div>
    <div class="form-group">
      <label>Section Image</label>
      <?php if (!empty($settings['about_story_image'])): ?>
        <div style="margin:.5rem 0 1rem"><img src="/uploads/sections/<?= sanitize($settings['about_story_image']) ?>" alt="" style="max-height:160px;border-radius:8px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No image uploaded yet — a placeholder box shows on the page until you add one.</p>
      <?php endif; ?>
      <input type="file" name="about_story_image" accept="image/png,image/jpeg,image/webp">
      <?php if (!empty($settings['about_story_image'])): ?>
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500;margin-top:.6rem">
        <input type="checkbox" name="remove_about_story_image" value="1" style="width:auto"> Remove image
      </label>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Founder Story</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Body Text</label>
      <textarea name="about_founder_text" style="min-height:240px"><?= sanitize($settings['about_founder_text'] ?? '') ?></textarea>
      <small style="color:#8892A4;display:block;margin-top:.4rem">Press Enter for a new paragraph.</small>
    </div>
    <div class="form-group">
      <label>Section Image</label>
      <?php if (!empty($settings['about_founder_image'])): ?>
        <div style="margin:.5rem 0 1rem"><img src="/uploads/sections/<?= sanitize($settings['about_founder_image']) ?>" alt="" style="max-height:160px;border-radius:8px"></div>
      <?php else: ?>
        <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No image uploaded yet — a placeholder box shows on the page until you add one.</p>
      <?php endif; ?>
      <input type="file" name="about_founder_image" accept="image/png,image/jpeg,image/webp">
      <small style="color:#8892A4;display:block;margin-top:.4rem">A photo of Gamith Hewathenna works well here.</small>
      <?php if (!empty($settings['about_founder_image'])): ?>
      <label style="display:flex;align-items:center;gap:.5rem;font-weight:500;margin-top:.6rem">
        <input type="checkbox" name="remove_about_founder_image" value="1" style="width:auto"> Remove image
      </label>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>"Why Choose Us" Reasons</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Section Heading</label>
      <input name="about_reasons_heading" value="<?= sanitize($settings['about_reasons_heading'] ?? 'Why Australian Businesses Choose Creative Elements Over a Local Agency') ?>">
    </div>
    <?php
    $reasonDefaults = [
        1 => ['Global quality, local pricing', 'Melbourne and Sydney agency rates without Melbourne and Sydney overheads — you get more for your budget.'],
        2 => ['Built to rank in Australia', "Every website and campaign is optimised specifically for Australian search behaviour and Google's local ranking factors."],
        3 => ['Senior team on every project', 'No juniors, no outsourcing. Gamith and the core team handle your work personally from start to finish.'],
        4 => ['We stay after launch', "After-sale support is built into how we work — not an upsell. We're your long-term partner, not a one-project shop."],
    ];
    for ($i = 1; $i <= 4; $i++): ?>
    <div class="form-row">
      <div class="form-group"><label>Reason <?= $i ?> Title</label><input name="reason<?= $i ?>_title" value="<?= sanitize($settings["reason{$i}_title"] ?? $reasonDefaults[$i][0]) ?>"></div>
      <div class="form-group"><label>Reason <?= $i ?> Description</label><input name="reason<?= $i ?>_desc" value="<?= sanitize($settings["reason{$i}_desc"] ?? $reasonDefaults[$i][1]) ?>"></div>
    </div>
    <?php endfor; ?>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Bottom Call to Action</h2></div>
  <div class="card-body">
    <div class="form-group">
      <label>Heading</label>
      <input name="about_cta_heading" value="<?= sanitize($settings['about_cta_heading'] ?? 'Ready to work with a digital agency that treats your business like its own?') ?>">
    </div>
    <div class="form-group">
      <label>Text</label>
      <textarea name="about_cta_text"><?= sanitize($settings['about_cta_text'] ?? "Get a free consultation and find out exactly what we'd do to grow your Melbourne or Sydney business online.") ?></textarea>
    </div>
  </div>
</div>

<button type="submit" class="btn btn-primary">Save About Page</button>
</form>

<?php require_once 'admin-footer.php'; ?>
