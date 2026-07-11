<?php
$adminTitle = 'Client Logos';
require_once 'admin-auth.php';

// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM client_logos WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/client-logos.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM client_logos WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE client_logos SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/client-logos.php');
    exit;
}

$formError = '';
$editLogo = null;

// Save (add or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = intval($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $sort  = intval($_POST['sort_order'] ?? 0);
    $image = '';

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','svg'])) {
            $formError = 'Image must be a JPG, PNG, WEBP or SVG file.';
        } elseif (!is_dir('../uploads/logos') || !is_writable('../uploads/logos')) {
            $formError = 'Upload folder "uploads/logos" is missing or not writable on the server. Create it and set permissions to 755.';
        } else {
            $newName = uniqid('logo_') . '.' . $ext;
            $dest    = '../uploads/logos/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = $newName;
            } else {
                $formError = 'Image upload failed. Please try again.';
            }
        }
    } elseif (!$id) {
        $formError = 'Please choose a logo image.';
    }

    if (!$formError) {
        if ($id) {
            $sql = "UPDATE client_logos SET name=?,sort_order=?" . ($image ? ",image=?" : "") . " WHERE id=?";
            $params = $image ? [$name,$sort,$image,$id] : [$name,$sort,$id];
        } else {
            $sql = "INSERT INTO client_logos (name,sort_order,image) VALUES (?,?,?)";
            $params = [$name,$sort,$image];
        }
        $db->prepare($sql)->execute($params);
        header('Location: /admin/client-logos.php?msg=saved');
        exit;
    }

    // Validation failed — redisplay the form with what was typed
    $editLogo = ['id' => $id, 'name' => $name, 'sort_order' => $sort];
}

if (isset($_GET['edit']) && !$editLogo) {
    $stmt = $db->prepare("SELECT * FROM client_logos WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editLogo = $stmt->fetch();
}

$logos = $db->query("SELECT * FROM client_logos ORDER BY sort_order")->fetchAll();
require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'saved' ? 'Logo saved successfully.' : 'Logo deleted.' ?></div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editLogo ? 'Edit Logo' : 'Add New Logo' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editLogo['id'] ?? 0 ?>">
      <div class="form-row">
        <div class="form-group"><label>Company Name (for alt text)</label><input name="name" value="<?= sanitize($editLogo['name'] ?? '') ?>" placeholder="e.g. Tree Service Chester"></div>
        <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editLogo['sort_order'] ?? 0 ?>" style="width:100px"></div>
      </div>
      <div class="form-group">
        <label>Logo Image <?= ($editLogo['id'] ?? 0) ? '' : '*' ?></label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg">
        <small style="color:#8892A4;display:block;margin-top:.4rem">Transparent PNG or SVG recommended.</small>
        <?php if (!empty($editLogo['image'])): ?><br><small>Current: <?= sanitize($editLogo['image']) ?></small><?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save Logo</button>
      <?php if ($editLogo): ?><a href="/admin/client-logos.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<!-- Logos Table -->
<div class="card">
  <div class="card-header"><h2>All Logos (<?= count($logos) ?>)</h2></div>
  <table>
    <thead><tr><th>Logo</th><th>Name</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($logos as $l): ?>
    <tr>
      <td><?php if ($l['image']): ?><img src="<?= SITE_URL ?>/uploads/logos/<?= sanitize($l['image']) ?>" alt="" style="max-width:100px;max-height:40px;object-fit:contain"><?php else: ?>&mdash;<?php endif; ?></td>
      <td><strong><?= sanitize($l['name']) ?></strong></td>
      <td><span class="pill <?= $l['active'] ? 'pill-green' : 'pill-red' ?>"><?= $l['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $l['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?toggle=<?= $l['id'] ?>" class="btn btn-outline btn-sm"><?= $l['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $l['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$logos): ?><tr><td colspan="4" style="text-align:center;color:#313131;padding:2rem">No logos yet. Add your first one above.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
