<?php
$adminTitle = 'Projects';
require_once 'admin-header.php';

$msg = '';

// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM projects WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/projects.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $row = $db->prepare("SELECT active FROM projects WHERE id=?")->execute([$_GET['toggle']]);
    $stmt = $db->prepare("SELECT active FROM projects WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE projects SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/projects.php');
    exit;
}

// Save (add or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = intval($_POST['id'] ?? 0);
    $title    = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $link     = trim($_POST['link'] ?? '');
    $sort     = intval($_POST['sort_order'] ?? 0);
    $image    = '';

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
            $newName = uniqid('proj_') . '.' . $ext;
            $dest    = '../uploads/projects/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = $newName;
            }
        }
    }

    if ($id) {
        $sql = "UPDATE projects SET title=?,category=?,description=?,link=?,sort_order=?" . ($image ? ",image=?" : "") . " WHERE id=?";
        $params = $image ? [$title,$category,$desc,$link,$sort,$image,$id] : [$title,$category,$desc,$link,$sort,$id];
    } else {
        $sql = "INSERT INTO projects (title,category,description,link,sort_order,image) VALUES (?,?,?,?,?,?)";
        $params = [$title,$category,$desc,$link,$sort,$image];
    }
    $db->prepare($sql)->execute($params);
    header('Location: /admin/projects.php?msg=saved');
    exit;
}

$editProject = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editProject = $stmt->fetch();
}

$projects = $db->query("SELECT * FROM projects ORDER BY sort_order")->fetchAll();
if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'saved' ? 'Project saved successfully.' : 'Project deleted.' ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editProject ? 'Edit Project' : 'Add New Project' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editProject['id'] ?? 0 ?>">
      <div class="form-row">
        <div class="form-group"><label>Project Title *</label><input name="title" required value="<?= sanitize($editProject['title'] ?? '') ?>"></div>
        <div class="form-group"><label>Category</label><input name="category" value="<?= sanitize($editProject['category'] ?? '') ?>" placeholder="e.g. Web Design"></div>
      </div>
      <div class="form-group"><label>Description</label><textarea name="description"><?= sanitize($editProject['description'] ?? '') ?></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Project Link (optional)</label><input name="link" type="url" value="<?= sanitize($editProject['link'] ?? '') ?>"></div>
        <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editProject['sort_order'] ?? 0 ?>"></div>
      </div>
      <div class="form-group"><label>Project Image</label><input type="file" name="image" accept="image/*">
        <?php if (!empty($editProject['image'])): ?><br><small>Current: <?= sanitize($editProject['image']) ?></small><?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save Project</button>
      <?php if ($editProject): ?><a href="/admin/projects.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<!-- Projects Table -->
<div class="card">
  <div class="card-header"><h2>All Projects (<?= count($projects) ?>)</h2></div>
  <table>
    <thead><tr><th>Title</th><th>Category</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($projects as $p): ?>
    <tr>
      <td><strong><?= sanitize($p['title']) ?></strong></td>
      <td><?= sanitize($p['category']) ?></td>
      <td><span class="pill <?= $p['active'] ? 'pill-green' : 'pill-red' ?>"><?= $p['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?toggle=<?= $p['id'] ?>" class="btn btn-outline btn-sm"><?= $p['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
