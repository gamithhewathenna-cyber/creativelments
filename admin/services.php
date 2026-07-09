<?php
$adminTitle = 'Services';
require_once 'admin-header.php';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM services WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/services.php?msg=deleted');
    exit;
}
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM services WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE services SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/services.php');
    exit;
}

$formError = '';
$editSvc = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = intval($_POST['id'] ?? 0);
    $t         = trim($_POST['title'] ?? '');
    $slug      = trim($_POST['slug'] ?? preg_replace('/[^a-z0-9]+/', '-', strtolower($t)));
    $slug      = trim($slug, '-');
    $desc      = trim($_POST['description'] ?? '');
    $heading1  = trim($_POST['content_heading'] ?? '');
    $content   = trim($_POST['content'] ?? '');
    $heading2  = trim($_POST['content2_heading'] ?? '');
    $content2  = trim($_POST['content2'] ?? '');
    $icon      = trim($_POST['icon'] ?? 'star');
    $sort      = intval($_POST['sort_order'] ?? 0);
    $image1    = '';
    $image2    = '';

    // Handle the two detail image uploads
    if (!empty($_FILES['detail_image1']['name']) || !empty($_FILES['detail_image2']['name'])) {
        if (!is_dir('../uploads/services') || !is_writable('../uploads/services')) {
            $formError = 'Upload folder "uploads/services" is missing or not writable on the server. Create it and set permissions to 755.';
        }
    }
    if (!$formError && !empty($_FILES['detail_image1']['name'])) {
        $ext = strtolower(pathinfo($_FILES['detail_image1']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $formError = 'Section 1 image must be a JPG, PNG or WEBP file.';
        } else {
            $newName = uniqid('svc_') . '.' . $ext;
            if (move_uploaded_file($_FILES['detail_image1']['tmp_name'], '../uploads/services/' . $newName)) {
                $image1 = $newName;
            } else {
                $formError = 'Section 1 image upload failed. Please try again.';
            }
        }
    }
    if (!$formError && !empty($_FILES['detail_image2']['name'])) {
        $ext = strtolower(pathinfo($_FILES['detail_image2']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $formError = 'Section 2 image must be a JPG, PNG or WEBP file.';
        } else {
            $newName = uniqid('svc_') . '.' . $ext;
            if (move_uploaded_file($_FILES['detail_image2']['tmp_name'], '../uploads/services/' . $newName)) {
                $image2 = $newName;
            } else {
                $formError = 'Section 2 image upload failed. Please try again.';
            }
        }
    }

    if (!$formError) {
        try {
            if ($id) {
                $sql = "UPDATE services SET title=?,slug=?,description=?,content_heading=?,content=?,content2_heading=?,content2=?,icon=?,sort_order=?"
                     . ($image1 ? ",detail_image1=?" : "") . ($image2 ? ",detail_image2=?" : "") . " WHERE id=?";
                $params = [$t,$slug,$desc,$heading1,$content,$heading2,$content2,$icon,$sort];
                if ($image1) $params[] = $image1;
                if ($image2) $params[] = $image2;
                $params[] = $id;
                $db->prepare($sql)->execute($params);
            } else {
                $db->prepare("INSERT INTO services (title,slug,description,content_heading,content,content2_heading,content2,icon,sort_order,detail_image1,detail_image2) VALUES (?,?,?,?,?,?,?,?,?,?,?)")
                   ->execute([$t,$slug,$desc,$heading1,$content,$heading2,$content2,$icon,$sort,$image1,$image2]);
            }
            header('Location: /admin/services.php?msg=saved');
            exit;
        } catch (PDOException $e) {
            $formError = 'Database error: ' . $e->getMessage();
        }
    }

    // Validation failed — redisplay the form with what was typed
    $editSvc = ['id' => $id, 'title' => $t, 'slug' => $slug, 'description' => $desc, 'content_heading' => $heading1, 'content' => $content, 'content2_heading' => $heading2, 'content2' => $content2, 'icon' => $icon, 'sort_order' => $sort];
}

if (isset($_GET['edit']) && !$editSvc) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editSvc = $stmt->fetch();
}
$services = $db->query("SELECT * FROM services ORDER BY sort_order")->fetchAll();
if (isset($_GET['msg'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>
<?php if ($formError): ?><div class="alert alert-error"><?= htmlspecialchars($formError) ?></div><?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editSvc ? 'Edit Service' : 'Add Service' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editSvc['id'] ?? 0 ?>">
      <div class="form-row">
        <div class="form-group"><label>Title *</label><input name="title" required value="<?= sanitize($editSvc['title'] ?? '') ?>" oninput="autoSlug(this)"></div>
        <div class="form-group">
          <label>Icon</label>
          <select name="icon">
            <?php foreach (['monitor','pen-tool','trending-up','shopping-bag','share-2','tablet','star'] as $ico): ?>
            <option value="<?= $ico ?>" <?= ($editSvc['icon'] ?? 'star') === $ico ? 'selected' : '' ?>><?= $ico ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>URL Slug</label>
        <input name="slug" id="slug" value="<?= sanitize($editSvc['slug'] ?? '') ?>">
        <small style="color:#8892A4;display:block;margin-top:.4rem">The page will be at /service.php?slug=your-slug</small>
      </div>
      <div class="form-group"><label>Short Description (shown on the Services grid card)</label><textarea name="description"><?= sanitize($editSvc['description'] ?? '') ?></textarea></div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editSvc['sort_order'] ?? 0 ?>" style="width:100px"></div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Full Details Page — Section 1</h3>
      <div class="form-group">
        <label>Section 1 Heading</label>
        <input name="content_heading" value="<?= sanitize($editSvc['content_heading'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Section 1 Text</label>
        <textarea name="content" style="min-height:160px"><?= sanitize($editSvc['content'] ?? '') ?></textarea>
        <small style="color:#8892A4;display:block;margin-top:.4rem">Press Enter for a new paragraph. Leave blank to just reuse the short description.</small>
      </div>
      <div class="form-group">
        <label>Section 1 Image</label>
        <?php if (!empty($editSvc['detail_image1'])): ?>
          <div style="margin:.5rem 0 1rem"><img src="/uploads/services/<?= sanitize($editSvc['detail_image1']) ?>" alt="" style="max-height:160px;border-radius:8px"></div>
        <?php else: ?>
          <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No image uploaded yet — a placeholder box shows on the page until you add one.</p>
        <?php endif; ?>
        <input type="file" name="detail_image1" accept="image/png,image/jpeg,image/webp">
      </div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">Full Details Page — Section 2</h3>
      <div class="form-group">
        <label>Section 2 Heading</label>
        <input name="content2_heading" value="<?= sanitize($editSvc['content2_heading'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Section 2 Text</label>
        <textarea name="content2" style="min-height:160px"><?= sanitize($editSvc['content2'] ?? '') ?></textarea>
        <small style="color:#8892A4;display:block;margin-top:.4rem">Press Enter for a new paragraph. Leave blank to hide this section.</small>
      </div>
      <div class="form-group">
        <label>Section 2 Image</label>
        <?php if (!empty($editSvc['detail_image2'])): ?>
          <div style="margin:.5rem 0 1rem"><img src="/uploads/services/<?= sanitize($editSvc['detail_image2']) ?>" alt="" style="max-height:160px;border-radius:8px"></div>
        <?php else: ?>
          <p style="color:#313131;font-size:.85rem;margin:.5rem 0 1rem">No image uploaded yet — a placeholder box shows on the page until you add one.</p>
        <?php endif; ?>
        <input type="file" name="detail_image2" accept="image/png,image/jpeg,image/webp">
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top:1rem">Save Service</button>
      <?php if ($editSvc): ?><a href="/admin/services.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
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
  <table>
    <thead><tr><th>Title</th><th>Icon</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($services as $s): ?>
    <tr>
      <td><strong><?= sanitize($s['title']) ?></strong></td>
      <td><?= sanitize($s['icon']) ?></td>
      <td><span class="pill <?= $s['active'] ? 'pill-green' : 'pill-red' ?>"><?= $s['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $s['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <?php if (!empty($s['slug'])): ?><a href="/service.php?slug=<?= urlencode($s['slug']) ?>" class="btn btn-outline btn-sm" target="_blank">View</a><?php endif; ?>
        <a href="?toggle=<?= $s['id'] ?>" class="btn btn-outline btn-sm"><?= $s['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
