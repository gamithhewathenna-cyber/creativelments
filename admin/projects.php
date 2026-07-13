<?php
$adminTitle = 'Projects';
require_once 'admin-auth.php';

// Delete project
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM projects WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/projects.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM projects WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE projects SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/projects.php');
    exit;
}

// Delete category
if (isset($_GET['delete_cat'])) {
    $db->prepare("DELETE FROM project_categories WHERE id=?")->execute([$_GET['delete_cat']]);
    header('Location: /admin/projects.php?msg=cat_deleted');
    exit;
}

$formError = '';
$editProject = null;

// Add a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_category'])) {
    $catName = trim($_POST['new_category']);
    if ($catName !== '') {
        $exists = $db->prepare("SELECT id FROM project_categories WHERE name=?");
        $exists->execute([$catName]);
        if (!$exists->fetch()) {
            $maxSort = (int) $db->query("SELECT COALESCE(MAX(sort_order),0) FROM project_categories")->fetchColumn();
            $db->prepare("INSERT INTO project_categories (name,sort_order) VALUES (?,?)")->execute([$catName, $maxSort + 1]);
        }
    }
    header('Location: /admin/projects.php?msg=cat_saved');
    exit;
}

// Save (add or edit) project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['new_category'])) {
    $id       = intval($_POST['id'] ?? 0);
    $title    = trim($_POST['title'] ?? '');
    $category = implode(',', array_map('trim', $_POST['categories'] ?? []));
    $desc     = trim($_POST['description'] ?? '');
    $link     = trim($_POST['link'] ?? '');
    $sort     = intval($_POST['sort_order'] ?? 0);
    $image    = '';
    $image2   = '';
    $image3   = '';

    if (!empty($_FILES['image']['name']) || !empty($_FILES['image2']['name']) || !empty($_FILES['image3']['name'])) {
        if (!is_dir('../uploads/projects') || !is_writable('../uploads/projects')) {
            $formError = 'Upload folder "uploads/projects" is missing or not writable on the server. Create it and set permissions to 755.';
        } elseif (!extension_loaded('gd')) {
            $formError = 'The GD image library is not enabled on this server, so images cannot be resized.';
        }
    }

    // Handle main image upload (also generates the grid thumbnail)
    if (!$formError && !empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
            $formError = 'Image must be a JPG, PNG, WEBP or GIF file.';
        } else {
            $baseName  = uniqid('proj_');
            $fullName  = $baseName . '.' . $ext;
            $thumbName = $baseName . '_thumb.' . $ext;
            $fullOk  = resizeCoverCrop($_FILES['image']['tmp_name'], '../uploads/projects/' . $fullName, 1080, 1350, $ext);
            $thumbOk = $fullOk && resizeCoverCrop($_FILES['image']['tmp_name'], '../uploads/projects/' . $thumbName, 300, 375, $ext);
            if ($fullOk && $thumbOk) {
                $image = $fullName;
            } else {
                $formError = 'Image processing failed. Please try again.';
            }
        }
    }

    // Handle the two extra gallery images shown in the project popup
    foreach (['image2', 'image3'] as $field) {
        if ($formError || empty($_FILES[$field]['name'])) continue;
        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
            $formError = 'Gallery images must be a JPG, PNG, WEBP or GIF file.';
            continue;
        }
        $newName = uniqid('proj_') . '.' . $ext;
        if (resizeCoverCrop($_FILES[$field]['tmp_name'], '../uploads/projects/' . $newName, 1080, 1350, $ext)) {
            $$field = $newName;
        } else {
            $formError = 'Gallery image processing failed. Please try again.';
        }
    }

    if (!$formError) {
        if ($id) {
            $sql = "UPDATE projects SET title=?,category=?,description=?,link=?,sort_order=?"
                 . ($image ? ",image=?" : "") . ($image2 ? ",image2=?" : "") . ($image3 ? ",image3=?" : "") . " WHERE id=?";
            $params = [$title,$category,$desc,$link,$sort];
            if ($image) $params[] = $image;
            if ($image2) $params[] = $image2;
            if ($image3) $params[] = $image3;
            $params[] = $id;
        } else {
            $sql = "INSERT INTO projects (title,category,description,link,sort_order,image,image2,image3) VALUES (?,?,?,?,?,?,?,?)";
            $params = [$title,$category,$desc,$link,$sort,$image,$image2,$image3];
        }
        $db->prepare($sql)->execute($params);
        header('Location: /admin/projects.php?msg=saved');
        exit;
    }

    // Validation failed — redisplay the form with what was typed
    $editProject = ['id' => $id, 'title' => $title, 'category' => $category, 'description' => $desc, 'link' => $link, 'sort_order' => $sort];
}

if (isset($_GET['edit']) && !$editProject) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editProject = $stmt->fetch();
}

$projects   = $db->query("SELECT * FROM projects ORDER BY sort_order")->fetchAll();
$categories = $db->query("SELECT * FROM project_categories ORDER BY sort_order")->fetchAll();
require_once 'admin-header.php';

$messages = [
    'saved'       => 'Project saved successfully.',
    'deleted'     => 'Project deleted.',
    'cat_saved'   => 'Category saved.',
    'cat_deleted' => 'Category deleted.',
];
if (isset($_GET['msg']) && isset($messages[$_GET['msg']])): ?>
<div class="alert alert-success"><?= $messages[$_GET['msg']] ?></div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<!-- Manage Categories -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Project Categories</h2></div>
  <div class="card-body">
    <form method="POST" style="display:flex;gap:.75rem;align-items:flex-end;margin-bottom:1.25rem;flex-wrap:wrap">
      <div class="form-group" style="margin-bottom:0;flex:1;min-width:200px">
        <label>New Category Name</label>
        <input name="new_category" placeholder="e.g. E-commerce">
      </div>
      <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
    <?php if ($categories): ?>
    <div style="display:flex;flex-wrap:wrap;gap:.6rem">
      <?php foreach ($categories as $cat): ?>
      <span class="pill pill-blue" style="display:inline-flex;align-items:center;gap:.5rem">
        <?= sanitize($cat['name']) ?>
        <a href="?delete_cat=<?= $cat['id'] ?>" onclick="return confirm('Delete category &quot;<?= sanitize($cat['name']) ?>&quot;? Projects already using it will keep the text but it won\'t be selectable anymore.')" style="color:#991B1B;font-weight:700;text-decoration:none">&times;</a>
      </span>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:#313131;font-size:.85rem">No categories yet — add one above before creating projects.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Add/Edit Form -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editProject ? 'Edit Project' : 'Add New Project' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editProject['id'] ?? 0 ?>">
      <div class="form-group"><label>Project Title *</label><input name="title" required value="<?= sanitize($editProject['title'] ?? '') ?>"></div>
      <div class="form-group">
        <label>Categories</label>
        <?php $selectedCats = array_map('trim', explode(',', $editProject['category'] ?? '')); ?>
        <?php if ($categories): ?>
        <div style="display:flex;flex-wrap:wrap;gap:.75rem 1.5rem">
          <?php foreach ($categories as $cat): ?>
          <label style="display:flex;align-items:center;gap:.5rem;font-weight:500;font-size:.88rem">
            <input type="checkbox" name="categories[]" value="<?= sanitize($cat['name']) ?>" style="width:auto" <?= in_array($cat['name'], $selectedCats, true) ? 'checked' : '' ?>>
            <?= sanitize($cat['name']) ?>
          </label>
          <?php endforeach; ?>
        </div>
        <small style="color:#8892A4;display:block;margin-top:.5rem">Select one or more categories — the project will appear under each on the Our Work filter.</small>
        <?php else: ?>
        <small style="color:#8892A4;display:block;margin-top:.4rem">Add a category above first.</small>
        <?php endif; ?>
      </div>
      <div class="form-group"><label>Description</label><textarea name="description"><?= sanitize($editProject['description'] ?? '') ?></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Project Link (optional)</label><input name="link" type="url" value="<?= sanitize($editProject['link'] ?? '') ?>"></div>
        <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editProject['sort_order'] ?? 0 ?>"></div>
      </div>
      <div class="form-group"><label>Project Image (main grid image)</label><input type="file" name="image" accept="image/*">
        <?php if (!empty($editProject['image'])): ?><br><small>Current: <?= sanitize($editProject['image']) ?></small><?php endif; ?>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Gallery Image 2 (optional)</label><input type="file" name="image2" accept="image/*">
          <?php if (!empty($editProject['image2'])): ?><br><small>Current: <?= sanitize($editProject['image2']) ?></small><?php endif; ?>
        </div>
        <div class="form-group"><label>Gallery Image 3 (optional)</label><input type="file" name="image3" accept="image/*">
          <?php if (!empty($editProject['image3'])): ?><br><small>Current: <?= sanitize($editProject['image3']) ?></small><?php endif; ?>
        </div>
      </div>
      <small style="color:#8892A4;display:block;margin-top:-.75rem;margin-bottom:1.25rem">All three images show together when a visitor clicks the project on the site.</small>
      <button type="submit" class="btn btn-primary">Save Project</button>
      <?php if ($editProject): ?><a href="/admin/projects.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<!-- Projects Table -->
<div class="card">
  <div class="card-header"><h2>All Projects (<?= count($projects) ?>)</h2></div>
  <table>
    <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($projects as $p): ?>
    <tr>
      <td><?php if ($p['image']): $thumb = preg_replace('/(\.[^.]+)$/', '_thumb$1', $p['image']); ?><img src="<?= SITE_URL ?>/uploads/projects/<?= sanitize($thumb) ?>" alt="" style="width:60px;height:75px;object-fit:cover;border-radius:4px"><?php else: ?>&mdash;<?php endif; ?></td>
      <td><strong><?= sanitize($p['title']) ?></strong></td>
      <td><?= sanitize(str_replace(',', ', ', $p['category'])) ?></td>
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
