<?php
$adminTitle = 'Testimonials';
require_once 'admin-auth.php';

if (isset($_GET['delete'])) { $db->prepare("DELETE FROM testimonials WHERE id=?")->execute([$_GET['delete']]); header('Location: /admin/testimonials.php?msg=deleted'); exit; }
if (isset($_GET['toggle'])) { $s = $db->prepare("SELECT active FROM testimonials WHERE id=?"); $s->execute([$_GET['toggle']]); $c = $s->fetchColumn(); $db->prepare("UPDATE testimonials SET active=? WHERE id=?")->execute([!$c,$_GET['toggle']]); header('Location: /admin/testimonials.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = intval($_POST['id'] ?? 0);
    $n    = trim($_POST['name'] ?? '');
    $co   = trim($_POST['company'] ?? '');
    $loc  = trim($_POST['location'] ?? '');
    $ct   = trim($_POST['content'] ?? '');
    $rat  = intval($_POST['rating'] ?? 5);
    $sort = intval($_POST['sort_order'] ?? 0);
    if ($id) {
        $db->prepare("UPDATE testimonials SET name=?,company=?,location=?,content=?,rating=?,sort_order=? WHERE id=?")->execute([$n,$co,$loc,$ct,$rat,$sort,$id]);
    } else {
        $db->prepare("INSERT INTO testimonials (name,company,location,content,rating,sort_order) VALUES (?,?,?,?,?,?)")->execute([$n,$co,$loc,$ct,$rat,$sort]);
    }
    header('Location: /admin/testimonials.php?msg=saved');
    exit;
}
$et = null;
if (isset($_GET['edit'])) { $s = $db->prepare("SELECT * FROM testimonials WHERE id=?"); $s->execute([$_GET['edit']]); $et = $s->fetch(); }
$tms = $db->query("SELECT * FROM testimonials ORDER BY sort_order")->fetchAll();
require_once 'admin-header.php';
if (isset($_GET['msg'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $et ? 'Edit Testimonial' : 'Add Testimonial' ?></h2></div>
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $et['id'] ?? 0 ?>">
      <div class="form-row">
        <div class="form-group"><label>Client Name *</label><input name="name" required value="<?= sanitize($et['name'] ?? '') ?>"></div>
        <div class="form-group"><label>Company</label><input name="company" value="<?= sanitize($et['company'] ?? '') ?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Location</label><input name="location" value="<?= sanitize($et['location'] ?? '') ?>" placeholder="e.g. Melbourne, AU"></div>
        <div class="form-group"><label>Rating (1-5)</label><input name="rating" type="number" min="1" max="5" value="<?= $et['rating'] ?? 5 ?>"></div>
      </div>
      <div class="form-group"><label>Testimonial *</label><textarea name="content" required><?= sanitize($et['content'] ?? '') ?></textarea></div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $et['sort_order'] ?? 0 ?>" style="width:100px"></div>
      <button type="submit" class="btn btn-primary">Save</button>
      <?php if ($et): ?><a href="/admin/testimonials.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<div class="card">
  <table>
    <thead><tr><th>Name</th><th>Company</th><th>Rating</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($tms as $t): ?>
    <tr>
      <td><?= sanitize($t['name']) ?></td>
      <td><?= sanitize($t['company']) ?></td>
      <td><?= str_repeat('★', $t['rating']) ?></td>
      <td><span class="pill <?= $t['active'] ? 'pill-green':'pill-red' ?>"><?= $t['active']?'Active':'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $t['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?toggle=<?= $t['id'] ?>" class="btn btn-outline btn-sm"><?= $t['active']?'Hide':'Show' ?></a>
        <a href="?delete=<?= $t['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
