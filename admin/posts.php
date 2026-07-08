<?php
$adminTitle = 'Blog Posts';
require_once 'admin-header.php';

if (isset($_GET['delete'])) { $db->prepare("DELETE FROM posts WHERE id=?")->execute([$_GET['delete']]); header('Location: /admin/posts.php?msg=deleted'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = intval($_POST['id'] ?? 0);
    $title   = trim($_POST['title'] ?? '');
    $slug    = trim($_POST['slug'] ?? preg_replace('/[^a-z0-9]+/', '-', strtolower($title)));
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $cat     = trim($_POST['category'] ?? 'General');
    $status  = $_POST['status'] ?? 'draft';
    if ($id) {
        $db->prepare("UPDATE posts SET title=?,slug=?,excerpt=?,content=?,category=?,status=? WHERE id=?")->execute([$title,$slug,$excerpt,$content,$cat,$status,$id]);
    } else {
        $db->prepare("INSERT INTO posts (title,slug,excerpt,content,category,status) VALUES (?,?,?,?,?,?)")->execute([$title,$slug,$excerpt,$content,$cat,$status]);
    }
    header('Location: /admin/posts.php?msg=saved');
    exit;
}
$ep = null;
if (isset($_GET['edit'])) { $s = $db->prepare("SELECT * FROM posts WHERE id=?"); $s->execute([$_GET['edit']]); $ep = $s->fetch(); }
$posts = $db->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();
if (isset($_GET['msg'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $ep ? 'Edit Post' : 'New Post' ?></h2></div>
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $ep['id'] ?? 0 ?>">
      <div class="form-group"><label>Title *</label><input name="title" required value="<?= sanitize($ep['title'] ?? '') ?>" oninput="autoSlug(this)"></div>
      <div class="form-group"><label>URL Slug</label><input name="slug" id="slug" value="<?= sanitize($ep['slug'] ?? '') ?>"></div>
      <div class="form-row">
        <div class="form-group"><label>Category</label><input name="category" value="<?= sanitize($ep['category'] ?? 'General') ?>"></div>
        <div class="form-group"><label>Status</label>
          <select name="status">
            <option value="draft" <?= ($ep['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="published" <?= ($ep['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
          </select>
        </div>
      </div>
      <div class="form-group"><label>Excerpt</label><textarea name="excerpt" style="min-height:80px"><?= sanitize($ep['excerpt'] ?? '') ?></textarea></div>
      <div class="form-group"><label>Content</label><textarea name="content" style="min-height:300px"><?= sanitize($ep['content'] ?? '') ?></textarea></div>
      <button type="submit" class="btn btn-primary">Save Post</button>
      <?php if ($ep): ?><a href="/admin/posts.php" class="btn btn-outline" style="margin-left:.5rem">Cancel</a><?php endif; ?>
    </form>
  </div>
</div>

<div class="card">
  <table>
    <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($posts as $p): ?>
    <tr>
      <td><strong><?= sanitize($p['title']) ?></strong></td>
      <td><?= sanitize($p['category']) ?></td>
      <td><span class="pill <?= $p['status']==='published'?'pill-green':'pill-yellow' ?>"><?= ucfirst($p['status']) ?></span></td>
      <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
      <td style="display:flex;gap:.5rem">
        <a href="?edit=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
        <a href="/blog-post.php?slug=<?= urlencode($p['slug']) ?>" class="btn btn-outline btn-sm" target="_blank">View</a>
        <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
function autoSlug(input) {
  const slugEl = document.getElementById('slug');
  if (!slugEl.dataset.edited) {
    slugEl.value = input.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
  }
}
document.getElementById('slug')?.addEventListener('input', function() { this.dataset.edited = 'true'; });
</script>

<?php require_once 'admin-footer.php'; ?>
