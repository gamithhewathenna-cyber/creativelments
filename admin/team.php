<?php
$adminTitle = 'Team';
require_once 'admin-auth.php';

// Delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM team_members WHERE id=?")->execute([$_GET['delete']]);
    header('Location: /admin/team.php?msg=deleted');
    exit;
}

// Toggle active
if (isset($_GET['toggle'])) {
    $stmt = $db->prepare("SELECT active FROM team_members WHERE id=?");
    $stmt->execute([$_GET['toggle']]);
    $cur = $stmt->fetchColumn();
    $db->prepare("UPDATE team_members SET active=? WHERE id=?")->execute([!$cur, $_GET['toggle']]);
    header('Location: /admin/team.php');
    exit;
}

// Move up / down (swap position, then renumber sort_order sequentially)
if (isset($_GET['move_up']) || isset($_GET['move_down'])) {
    $direction = isset($_GET['move_up']) ? -1 : 1;
    $id = intval($_GET['move_up'] ?? $_GET['move_down']);
    $order = $db->query("SELECT id FROM team_members ORDER BY sort_order, id")->fetchAll(PDO::FETCH_COLUMN);
    $pos = array_search($id, $order);
    if ($pos !== false) {
        $swapPos = $pos + $direction;
        if (isset($order[$swapPos])) {
            [$order[$pos], $order[$swapPos]] = [$order[$swapPos], $order[$pos]];
        }
    }
    foreach ($order as $i => $memberId) {
        $db->prepare("UPDATE team_members SET sort_order=? WHERE id=?")->execute([$i, $memberId]);
    }
    header('Location: /admin/team.php');
    exit;
}

$formError = '';
$editMember = null;

// Save (add or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = intval($_POST['id'] ?? 0);
    $name     = trim($_POST['name'] ?? '');
    $jobTitle = trim($_POST['job_title'] ?? '');
    $sort     = intval($_POST['sort_order'] ?? 0);
    $image    = '';

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $formError = 'Image must be a JPG, PNG or WEBP file.';
        } elseif (!is_dir('../uploads/team') || !is_writable('../uploads/team')) {
            $formError = 'Upload folder "uploads/team" is missing or not writable on the server. Create it and set permissions to 755.';
        } else {
            $newName = uniqid('team_') . '.' . $ext;
            $dest    = '../uploads/team/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = $newName;
            } else {
                $formError = 'Image upload failed. Please try again.';
            }
        }
    }

    if (!$formError) {
        if ($id) {
            $sql = "UPDATE team_members SET name=?,job_title=?,sort_order=?" . ($image ? ",image=?" : "") . " WHERE id=?";
            $params = $image ? [$name,$jobTitle,$sort,$image,$id] : [$name,$jobTitle,$sort,$id];
        } else {
            $sql = "INSERT INTO team_members (name,job_title,sort_order,image) VALUES (?,?,?,?)";
            $params = [$name,$jobTitle,$sort,$image];
        }
        $db->prepare($sql)->execute($params);
        header('Location: /admin/team.php?msg=saved');
        exit;
    }

    // Validation failed — redisplay the form with what was typed
    $editMember = ['id' => $id, 'name' => $name, 'job_title' => $jobTitle, 'sort_order' => $sort];
}

if (isset($_GET['edit']) && !$editMember) {
    $stmt = $db->prepare("SELECT * FROM team_members WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editMember = $stmt->fetch();
}

$members = $db->query("SELECT * FROM team_members ORDER BY sort_order")->fetchAll();
require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'saved' ? 'Team member saved successfully.' : 'Team member deleted.' ?></div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editMember ? 'Edit Team Member' : 'Add New Team Member' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $editMember['id'] ?? 0 ?>">
      <div class="form-row">
        <div class="form-group"><label>Name *</label><input name="name" required value="<?= sanitize($editMember['name'] ?? '') ?>" placeholder="e.g. Gamith Hewathenna"></div>
        <div class="form-group"><label>Job Title *</label><input name="job_title" required value="<?= sanitize($editMember['job_title'] ?? '') ?>" placeholder="e.g. Founder & Creative Director"></div>
      </div>
      <div class="form-group"><label>Sort Order</label><input name="sort_order" type="number" value="<?= $editMember['sort_order'] ?? 0 ?>" style="width:100px"></div>
      <div class="form-group">
        <label>Photo</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        <small style="color:#8892A4;display:block;margin-top:.4rem">Square photo recommended.</small>
        <?php if (!empty($editMember['image'])): ?><br><small>Current: <?= sanitize($editMember['image']) ?></small><?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save Team Member</button>
      <?php if ($editMember): ?><a href="/admin/team.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<!-- Team Table -->
<div class="card">
  <div class="card-header"><h2>All Team Members (<?= count($members) ?>)</h2></div>
  <table>
    <thead><tr><th>Order</th><th>Photo</th><th>Name</th><th>Job Title</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($members as $i => $m): ?>
    <tr>
      <td style="display:flex;gap:.25rem">
        <?php if ($i > 0): ?><a href="?move_up=<?= $m['id'] ?>" class="btn btn-outline btn-sm" title="Move up">&uarr;</a><?php else: ?><span class="btn btn-outline btn-sm" style="visibility:hidden">&uarr;</span><?php endif; ?>
        <?php if ($i < count($members) - 1): ?><a href="?move_down=<?= $m['id'] ?>" class="btn btn-outline btn-sm" title="Move down">&darr;</a><?php else: ?><span class="btn btn-outline btn-sm" style="visibility:hidden">&darr;</span><?php endif; ?>
      </td>
      <td><?php if ($m['image']): ?><img src="<?= SITE_URL ?>/uploads/team/<?= sanitize($m['image']) ?>" alt="" style="width:44px;height:44px;object-fit:cover;border-radius:50%"><?php else: ?>&mdash;<?php endif; ?></td>
      <td><strong><?= sanitize($m['name']) ?></strong></td>
      <td><?= sanitize($m['job_title']) ?></td>
      <td><span class="pill <?= $m['active'] ? 'pill-green' : 'pill-red' ?>"><?= $m['active'] ? 'Active' : 'Hidden' ?></span></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $m['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?toggle=<?= $m['id'] ?>" class="btn btn-outline btn-sm"><?= $m['active'] ? 'Hide' : 'Show' ?></a>
        <a href="?delete=<?= $m['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$members): ?><tr><td colspan="6" style="text-align:center;color:#313131;padding:2rem">No team members yet. Add your first one above.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
