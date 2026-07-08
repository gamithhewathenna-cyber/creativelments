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
    $id   = intval($_POST['id'] ?? 0);
    $t    = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'star');
    $sort = intval($_POST['sort_order'] ?? 0);
    if ($id) {
        $db->prepare("UPDATE services SET title=?,description=?,icon=?,sort_order=? WHERE id=?")->execute([$t,$desc,$icon,$sort,$id]);
    } else {
        $db->prepare("INSERT INTO services (title,description,icon,sort_order) VALUES (?,?,?,?)")->execute([$t,$desc,$icon,$sort]);
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
        <div class="form-group"><label>Title *</label><input name="title" required value="<?= sanitize($editSvc['title'] ?? '') ?>"></div>
        <div class="form-group">
          <label>Icon</label>
          <select name="icon">
            <?php foreach (['monitor','pen-tool','trending-up','shopping-bag','share-2','tablet','star'] as $ico): ?>
            <option value="<?= $ico ?>" <?= ($editSvc['icon'] ?? 'star') === $ico ? 'selected' : '' ?>><?= $ico ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group"><label>Description</label><textarea name="description"><?= sanitize($editSvc['description'] ?? '') ?></textarea></div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editSvc['sort_order'] ?? 0 ?>" style="width:100px"></div>
      <button type="submit" class="btn btn-primary">Save Service</button>
      <?php if ($editSvc): ?><a href="/admin/services.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
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
        <a href="?toggle=<?= $s['id'] ?>" class="btn btn-outline btn-sm"><?= $s['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
