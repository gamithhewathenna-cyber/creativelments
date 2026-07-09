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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = intval($_POST['id'] ?? 0);
    $t       = trim($_POST['title'] ?? '');
    $slug    = trim($_POST['slug'] ?? preg_replace('/[^a-z0-9]+/', '-', strtolower($t)));
    $slug    = trim($slug, '-');
    $desc    = trim($_POST['description'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $icon    = trim($_POST['icon'] ?? 'star');
    $sort    = intval($_POST['sort_order'] ?? 0);
    if ($id) {
        $db->prepare("UPDATE services SET title=?,slug=?,description=?,content=?,icon=?,sort_order=? WHERE id=?")->execute([$t,$slug,$desc,$content,$icon,$sort,$id]);
    } else {
        $db->prepare("INSERT INTO services (title,slug,description,content,icon,sort_order) VALUES (?,?,?,?,?,?)")->execute([$t,$slug,$desc,$content,$icon,$sort]);
    }
    header('Location: /admin/services.php?msg=saved');
    exit;
}
$editSvc = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editSvc = $stmt->fetch();
}
$services = $db->query("SELECT * FROM services ORDER BY sort_order")->fetchAll();
if (isset($_GET['msg'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editSvc ? 'Edit Service' : 'Add Service' ?></h2></div>
  <div class="card-body">
    <form method="POST">
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
      <div class="form-group">
        <label>Full Details (shown on the service's own page)</label>
        <textarea name="content" style="min-height:200px"><?= sanitize($editSvc['content'] ?? '') ?></textarea>
        <small style="color:#8892A4;display:block;margin-top:.4rem">Press Enter for a new paragraph. Leave blank to just reuse the short description.</small>
      </div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editSvc['sort_order'] ?? 0 ?>" style="width:100px"></div>
      <button type="submit" class="btn btn-primary">Save Service</button>
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
