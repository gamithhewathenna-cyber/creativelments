<?php
$adminTitle = 'GEO Locations';
require_once 'admin-auth.php';

// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM locations WHERE id=?")->execute([$_GET['delete']]);
    regenerateSitemap($db);
    header('Location: /admin/locations.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM locations WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE locations SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    regenerateSitemap($db);
    header('Location: /admin/locations.php');
    exit;
}

$formError = '';
$editLoc   = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = intval($_POST['id'] ?? 0);
    $city    = trim($_POST['city'] ?? '');
    $region  = trim($_POST['region'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $slug    = trim($_POST['slug'] ?? preg_replace('/[^a-z0-9]+/', '-', strtolower($city)));
    $slug    = trim($slug, '-');
    $seoTitle = trim($_POST['seo_title'] ?? '');
    $metaDesc = trim($_POST['meta_description'] ?? '');
    $keyphrase = trim($_POST['focus_keyphrase'] ?? '');
    $h1      = trim($_POST['h1'] ?? '');
    $intro   = trim($_POST['intro'] ?? '');
    $s1h = trim($_POST['section1_heading'] ?? ''); $s1c = trim($_POST['section1_content'] ?? '');
    $s2h = trim($_POST['section2_heading'] ?? ''); $s2c = trim($_POST['section2_content'] ?? '');
    $s3h = trim($_POST['section3_heading'] ?? ''); $s3c = trim($_POST['section3_content'] ?? '');
    $faqs = [];
    for ($i = 1; $i <= 5; $i++) {
        $faqs["faq{$i}_q"] = trim($_POST["faq{$i}_q"] ?? '');
        $faqs["faq{$i}_a"] = trim($_POST["faq{$i}_a"] ?? '');
    }
    $ctaHeading = trim($_POST['cta_heading'] ?? '');
    $ctaText    = trim($_POST['cta_text'] ?? '');
    $sort       = intval($_POST['sort_order'] ?? 0);
    $image      = '';

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $formError = 'Image must be a JPG, PNG or WEBP file.';
        } elseif (!is_dir('../uploads/sections') || !is_writable('../uploads/sections')) {
            $formError = 'Upload folder "uploads/sections" is missing or not writable on the server. Create it and set permissions to 755.';
        } else {
            $newName = uniqid('loc_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/sections/' . $newName)) {
                $image = $newName;
            } else {
                $formError = 'Image upload failed. Please try again.';
            }
        }
    }

    if (!$city)    $formError = $formError ?: 'City is required.';
    if (!$country) $formError = $formError ?: 'Country is required.';

    if (!$formError) {
        try {
            $fields = ['city','region','country','slug','seo_title','meta_description','focus_keyphrase','h1','intro',
                       'section1_heading','section1_content','section2_heading','section2_content','section3_heading','section3_content',
                       'faq1_q','faq1_a','faq2_q','faq2_a','faq3_q','faq3_a','faq4_q','faq4_a','faq5_q','faq5_a',
                       'cta_heading','cta_text','sort_order'];
            $values = [$city,$region,$country,$slug,$seoTitle,$metaDesc,$keyphrase,$h1,$intro,
                       $s1h,$s1c,$s2h,$s2c,$s3h,$s3c,
                       $faqs['faq1_q'],$faqs['faq1_a'],$faqs['faq2_q'],$faqs['faq2_a'],$faqs['faq3_q'],$faqs['faq3_a'],
                       $faqs['faq4_q'],$faqs['faq4_a'],$faqs['faq5_q'],$faqs['faq5_a'],
                       $ctaHeading,$ctaText,$sort];

            if ($id) {
                $sql = "UPDATE locations SET " . implode(',', array_map(fn($f) => "$f=?", $fields)) . ($image ? ",image=?" : "") . " WHERE id=?";
                $params = $values;
                if ($image) $params[] = $image;
                $params[] = $id;
                $db->prepare($sql)->execute($params);
            } else {
                $sql = "INSERT INTO locations (" . implode(',', $fields) . ",image) VALUES (" . implode(',', array_fill(0, count($fields) + 1, '?')) . ")";
                $params = $values;
                $params[] = $image;
                $db->prepare($sql)->execute($params);
            }
            regenerateSitemap($db);
            header('Location: /admin/locations.php?msg=saved');
            exit;
        } catch (PDOException $e) {
            $formError = 'Database error: ' . $e->getMessage();
        }
    }

    $editLoc = array_merge(['id' => $id, 'city' => $city, 'region' => $region, 'country' => $country, 'slug' => $slug,
        'seo_title' => $seoTitle, 'meta_description' => $metaDesc, 'focus_keyphrase' => $keyphrase, 'h1' => $h1, 'intro' => $intro,
        'section1_heading' => $s1h, 'section1_content' => $s1c, 'section2_heading' => $s2h, 'section2_content' => $s2c,
        'section3_heading' => $s3h, 'section3_content' => $s3c, 'cta_heading' => $ctaHeading, 'cta_text' => $ctaText, 'sort_order' => $sort], $faqs);
}

if (isset($_GET['edit']) && !$editLoc) {
    $stmt = $db->prepare("SELECT * FROM locations WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editLoc = $stmt->fetch();
}

$locations = $db->query("SELECT * FROM locations ORDER BY country, sort_order")->fetchAll();
require_once 'admin-header.php';

if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'saved' ? 'Location saved successfully.' : 'Location deleted.' ?></div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editLoc ? 'Edit Location' : 'Add New Location' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editLoc['id'] ?? 0 ?>">

      <h3 style="font-size:.95rem;margin-bottom:1rem">Basics</h3>
      <div class="form-row">
        <div class="form-group"><label>City *</label><input name="city" required value="<?= sanitize($editLoc['city'] ?? '') ?>" oninput="autoSlug(this)"></div>
        <div class="form-group"><label>State / Region</label><input name="region" value="<?= sanitize($editLoc['region'] ?? '') ?>" placeholder="e.g. New South Wales / California"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Country *</label><input name="country" required value="<?= sanitize($editLoc['country'] ?? '') ?>" placeholder="Australia / United States"></div>
        <div class="form-group"><label>URL Slug</label><input name="slug" id="slug" value="<?= sanitize($editLoc['slug'] ?? '') ?>">
          <small style="color:#8892A4;display:block;margin-top:.4rem">Page will be at /location.php?slug=your-slug</small>
        </div>
      </div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editLoc['sort_order'] ?? 0 ?>" style="width:100px"></div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Hero</h3>
      <div class="form-group"><label>H1 Heading</label><input name="h1" value="<?= sanitize($editLoc['h1'] ?? '') ?>" placeholder="e.g. Web Design & Digital Marketing in Sydney"></div>
      <div class="form-group"><label>Intro Paragraph</label><textarea name="intro" style="min-height:100px"><?= sanitize($editLoc['intro'] ?? '') ?></textarea></div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Section 1</h3>
      <div class="form-group"><label>Heading (H2)</label><input name="section1_heading" value="<?= sanitize($editLoc['section1_heading'] ?? '') ?>"></div>
      <div class="form-group"><label>Content</label><textarea name="section1_content" style="min-height:160px"><?= sanitize($editLoc['section1_content'] ?? '') ?></textarea></div>

      <div class="form-group">
        <label>Section Image</label>
        <?php if (!empty($editLoc['image'])): ?>
          <div style="margin:.5rem 0 1rem"><img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($editLoc['image']) ?>" alt="" style="max-height:160px;border-radius:8px"></div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
      </div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Section 2</h3>
      <div class="form-group"><label>Heading (H2)</label><input name="section2_heading" value="<?= sanitize($editLoc['section2_heading'] ?? '') ?>"></div>
      <div class="form-group"><label>Content</label><textarea name="section2_content" style="min-height:160px"><?= sanitize($editLoc['section2_content'] ?? '') ?></textarea></div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Section 3</h3>
      <div class="form-group"><label>Heading (H2)</label><input name="section3_heading" value="<?= sanitize($editLoc['section3_heading'] ?? '') ?>"></div>
      <div class="form-group"><label>Content</label><textarea name="section3_content" style="min-height:160px"><?= sanitize($editLoc['section3_content'] ?? '') ?></textarea></div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">FAQs (shown with FAQ schema)</h3>
      <?php for ($i = 1; $i <= 5; $i++): ?>
      <div class="form-row">
        <div class="form-group"><label>FAQ <?= $i ?> Question</label><input name="faq<?= $i ?>_q" value="<?= sanitize($editLoc["faq{$i}_q"] ?? '') ?>"></div>
        <div class="form-group"><label>FAQ <?= $i ?> Answer</label><input name="faq<?= $i ?>_a" value="<?= sanitize($editLoc["faq{$i}_a"] ?? '') ?>"></div>
      </div>
      <?php endfor; ?>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Call To Action</h3>
      <div class="form-group"><label>CTA Heading</label><input name="cta_heading" value="<?= sanitize($editLoc['cta_heading'] ?? '') ?>"></div>
      <div class="form-group"><label>CTA Text</label><textarea name="cta_text"><?= sanitize($editLoc['cta_text'] ?? '') ?></textarea></div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">SEO</h3>
      <div class="form-group"><label>Focus Keyphrase</label><input name="focus_keyphrase" value="<?= sanitize($editLoc['focus_keyphrase'] ?? '') ?>"></div>
      <div class="form-group"><label>SEO Title</label><input name="seo_title" value="<?= sanitize($editLoc['seo_title'] ?? '') ?>"></div>
      <div class="form-group"><label>Meta Description</label><textarea name="meta_description" style="min-height:80px"><?= sanitize($editLoc['meta_description'] ?? '') ?></textarea></div>

      <button type="submit" class="btn btn-primary" style="margin-top:1rem">Save Location</button>
      <?php if ($editLoc): ?><a href="/admin/locations.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
    <script>
    function autoSlug(input) {
      const slugEl = document.getElementById('slug');
      if (!slugEl.dataset.edited) {
        slugEl.value = input.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
      }
    }
    document.getElementById('slug')?.addEventListener('input', function() { this.dataset.edited = 'true'; });
    </script>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>All Locations (<?= count($locations) ?>)</h2></div>
  <table>
    <thead><tr><th>City</th><th>Country</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($locations as $l): ?>
    <tr>
      <td><strong><?= sanitize($l['city']) ?></strong></td>
      <td><?= sanitize($l['country']) ?></td>
      <td><span class="pill <?= $l['active'] ? 'pill-green' : 'pill-red' ?>"><?= $l['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $l['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <?php if (!empty($l['slug'])): ?><a href="/location.php?slug=<?= urlencode($l['slug']) ?>" class="btn btn-outline btn-sm" target="_blank">View</a><?php endif; ?>
        <a href="?toggle=<?= $l['id'] ?>" class="btn btn-outline btn-sm"><?= $l['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $l['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$locations): ?><tr><td colspan="4" style="text-align:center;color:#313131;padding:2rem">No locations yet. Add your first one above.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
