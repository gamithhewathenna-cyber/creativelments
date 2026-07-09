<?php
$adminTitle = 'Users';
$requireAdminRole = true;
require_once 'admin-auth.php';

$formError = '';
$msg = '';

// Delete
if (isset($_GET['delete'])) {
    $delId = intval($_GET['delete']);
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE id=?");
    $stmt->execute([$delId]);
    $target = $stmt->fetch();

    if (!$target) {
        $formError = 'User not found.';
    } elseif ($target['username'] === $_SESSION['admin_user']) {
        $formError = 'You cannot delete your own account while logged in.';
    } elseif ($target['role'] === 'admin' && $db->query("SELECT COUNT(*) FROM admin_users WHERE role='admin'")->fetchColumn() <= 1) {
        $formError = 'Cannot delete the last remaining admin account.';
    } else {
        $db->prepare("DELETE FROM admin_users WHERE id=?")->execute([$delId]);
        header('Location: /admin/users.php?msg=deleted');
        exit;
    }
}

// Add or edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = intval($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $role     = ($_POST['role'] ?? 'editor') === 'admin' ? 'admin' : 'editor';
    $password = $_POST['password'] ?? '';

    if (!$username) {
        $formError = 'Username is required.';
    } elseif (!$id && !$password) {
        $formError = 'Password is required for a new user.';
    } else {
        try {
            if ($id) {
                // Prevent demoting the last remaining admin
                $stmt = $db->prepare("SELECT * FROM admin_users WHERE id=?");
                $stmt->execute([$id]);
                $existing = $stmt->fetch();
                if ($existing && $existing['role'] === 'admin' && $role === 'editor'
                    && $db->query("SELECT COUNT(*) FROM admin_users WHERE role='admin'")->fetchColumn() <= 1) {
                    $formError = 'Cannot demote the last remaining admin account.';
                } else {
                    if ($password) {
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $db->prepare("UPDATE admin_users SET username=?, email=?, role=?, password_hash=? WHERE id=?")
                           ->execute([$username, $email, $role, $hash, $id]);
                    } else {
                        $db->prepare("UPDATE admin_users SET username=?, email=?, role=? WHERE id=?")
                           ->execute([$username, $email, $role, $id]);
                    }
                    header('Location: /admin/users.php?msg=saved');
                    exit;
                }
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $db->prepare("INSERT INTO admin_users (username, password_hash, email, role) VALUES (?,?,?,?)")
                   ->execute([$username, $hash, $email, $role]);
                header('Location: /admin/users.php?msg=saved');
                exit;
            }
        } catch (PDOException $e) {
            $formError = str_contains($e->getMessage(), 'Duplicate') ? 'That username is already taken.' : 'Database error: ' . $e->getMessage();
        }
    }
}

$editUser = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editUser = $stmt->fetch();
}

$users = $db->query("SELECT * FROM admin_users ORDER BY created_at")->fetchAll();

require_once 'admin-header.php';
if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'saved' ? 'User saved successfully.' : 'User deleted.' ?></div>
<?php endif; ?>
<?php if ($formError): ?>
<div class="alert alert-error"><?= htmlspecialchars($formError) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $editUser ? 'Edit User' : 'Add New User' ?></h2></div>
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $editUser['id'] ?? 0 ?>">
      <div class="form-row">
        <div class="form-group"><label>Username *</label><input name="username" required value="<?= sanitize($editUser['username'] ?? '') ?>"></div>
        <div class="form-group"><label>Email</label><input name="email" type="email" value="<?= sanitize($editUser['email'] ?? '') ?>"></div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Password <?= $editUser ? '(leave blank to keep current)' : '*' ?></label>
          <input name="password" type="password" autocomplete="new-password">
        </div>
        <div class="form-group">
          <label>Role *</label>
          <select name="role">
            <option value="editor" <?= (($editUser['role'] ?? 'editor') === 'editor') ? 'selected' : '' ?>>Editor (content only — no Settings/Users access)</option>
            <option value="admin" <?= (($editUser['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin (full access)</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Save User</button>
      <?php if ($editUser): ?><a href="/admin/users.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>All Users (<?= count($users) ?>)</h2></div>
  <table>
    <thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Last Login</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
    <tr>
      <td><strong><?= sanitize($u['username']) ?></strong><?= $u['username'] === $_SESSION['admin_user'] ? ' <span style="color:#8892A4">(you)</span>' : '' ?></td>
      <td><?= sanitize($u['email']) ?></td>
      <td><span class="pill <?= $u['role'] === 'admin' ? 'pill-blue' : 'pill-yellow' ?>"><?= $u['role'] === 'admin' ? 'Admin' : 'Editor' ?></span></td>
      <td><?= $u['last_login'] ? date('d M Y, g:ia', strtotime($u['last_login'])) : 'Never' ?></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $u['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once 'admin-footer.php'; ?>
