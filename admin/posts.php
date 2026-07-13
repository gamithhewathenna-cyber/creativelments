<?php
$adminTitle = 'Blog Posts';
require_once 'admin-auth.php';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM posts WHERE id=?")->execute([$_GET['delete']]);
    regenerateSitemap($db);
    header('Location: /admin/posts.php?msg=deleted');
    exit;
}

// Delete category
if (isset($_GET['delete_cat'])) {
    $db->prepare("DELETE FROM post_categories WHERE id=?")->execute([$_GET['delete_cat']]);
    header('Location: /admin/posts.php?msg=cat_deleted');
    exit;
}

// Add a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_category'])) {
    $catName = trim($_POST['new_category']);
    if ($catName !== '') {
        $exists = $db->prepare("SELECT id FROM post_categories WHERE name=?");
        $exists->execute([$catName]);
        if (!$exists->fetch()) {
            $maxSort = (int) $db->query("SELECT COALESCE(MAX(sort_order),0) FROM post_categories")->fetchColumn();
            $db->prepare("INSERT INTO post_categories (name,sort_order) VALUES (?,?)")->execute([$catName, $maxSort + 1]);
        }
    }
    header('Location: /admin/posts.php?msg=cat_saved');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['new_category'])) {
    $id      = intval($_POST['id'] ?? 0);
    $title   = trim($_POST['title'] ?? '');
    $slug    = trim($_POST['slug'] ?? preg_replace('/[^a-z0-9]+/', '-', strtolower($title)));
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $cat     = trim($_POST['category'] ?? 'General');
    $status  = $_POST['status'] ?? 'draft';
    $keyphrase = trim($_POST['focus_keyphrase'] ?? '');
    $seoTitle  = trim($_POST['seo_title'] ?? '');
    $metaDesc  = trim($_POST['meta_description'] ?? '');

    $formError = '';
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        if (!is_dir('../uploads/blog') || !is_writable('../uploads/blog')) {
            $formError = 'Upload folder "uploads/blog" is missing or not writable on the server. Create it and set permissions to 755.';
        } else {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                $formError = 'Featured image must be a JPG, PNG or WEBP file.';
            } else {
                $newName = uniqid('blog_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/blog/' . $newName)) {
                    $image = $newName;
                } else {
                    $formError = 'Featured image upload failed. Please try again.';
                }
            }
        }
    }

    if (!$formError) {
        if ($id) {
            $sql = "UPDATE posts SET title=?,slug=?,excerpt=?,content=?,category=?,status=?,focus_keyphrase=?,seo_title=?,meta_description=?" . ($image ? ",image=?" : "") . " WHERE id=?";
            $params = [$title,$slug,$excerpt,$content,$cat,$status,$keyphrase,$seoTitle,$metaDesc];
            if ($image) $params[] = $image;
            $params[] = $id;
            $db->prepare($sql)->execute($params);
        } else {
            $db->prepare("INSERT INTO posts (title,slug,excerpt,content,image,category,status,focus_keyphrase,seo_title,meta_description) VALUES (?,?,?,?,?,?,?,?,?,?)")->execute([$title,$slug,$excerpt,$content,$image,$cat,$status,$keyphrase,$seoTitle,$metaDesc]);
        }
        regenerateSitemap($db);
        header('Location: /admin/posts.php?msg=saved');
        exit;
    }

    // Upload/validation failed — redisplay the form with what was typed
    $ep = ['id' => $id, 'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'content' => $content, 'category' => $cat, 'status' => $status, 'focus_keyphrase' => $keyphrase, 'seo_title' => $seoTitle, 'meta_description' => $metaDesc];
}
if (!isset($ep)) {
    $ep = null;
    if (isset($_GET['edit'])) { $s = $db->prepare("SELECT * FROM posts WHERE id=?"); $s->execute([$_GET['edit']]); $ep = $s->fetch(); }
}
$posts = $db->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();
$postCategories = $db->query("SELECT * FROM post_categories ORDER BY sort_order")->fetchAll();
require_once 'admin-header.php';

$postMsgs = [
    'saved'       => 'Saved.',
    'deleted'     => 'Post deleted.',
    'cat_saved'   => 'Category saved.',
    'cat_deleted' => 'Category deleted.',
];
if (isset($_GET['msg']) && isset($postMsgs[$_GET['msg']])): ?>
<div class="alert alert-success"><?= $postMsgs[$_GET['msg']] ?></div>
<?php endif; ?>
<?php if (!empty($formError)): ?><div class="alert alert-error"><?= htmlspecialchars($formError) ?></div><?php endif; ?>

<!-- Manage Categories -->
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2>Blog Categories</h2></div>
  <div class="card-body">
    <form method="POST" style="display:flex;gap:.75rem;align-items:flex-end;margin-bottom:1.25rem;flex-wrap:wrap">
      <div class="form-group" style="margin-bottom:0;flex:1;min-width:200px">
        <label>New Category Name</label>
        <input name="new_category" placeholder="e.g. SEO Tips">
      </div>
      <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
    <?php if ($postCategories): ?>
    <div style="display:flex;flex-wrap:wrap;gap:.6rem">
      <?php foreach ($postCategories as $cat): ?>
      <span class="pill pill-blue" style="display:inline-flex;align-items:center;gap:.5rem">
        <?= sanitize($cat['name']) ?>
        <a href="?delete_cat=<?= $cat['id'] ?>" onclick="return confirm('Delete category &quot;<?= sanitize($cat['name']) ?>&quot;? Posts already using it will keep the text but it won\'t be selectable anymore.')" style="color:#991B1B;font-weight:700;text-decoration:none">&times;</a>
      </span>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:#313131;font-size:.85rem">No categories yet — add one above before creating posts.</p>
    <?php endif; ?>
  </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><h2><?= $ep ? 'Edit Post' : 'New Post' ?></h2></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $ep['id'] ?? 0 ?>">
      <div class="form-group"><label>Title *</label><input name="title" required value="<?= sanitize($ep['title'] ?? '') ?>" oninput="autoSlug(this)"></div>
      <div class="form-group"><label>URL Slug</label><input name="slug" id="slug" value="<?= sanitize($ep['slug'] ?? '') ?>"></div>
      <div class="form-row">
        <div class="form-group">
          <label>Category</label>
          <select name="category">
            <?php $curCat = $ep['category'] ?? 'General'; ?>
            <?php $catNames = array_map(fn($c) => $c['name'], $postCategories); ?>
            <?php if ($curCat !== '' && !in_array($curCat, $catNames, true)): ?>
            <option value="<?= sanitize($curCat) ?>" selected><?= sanitize($curCat) ?></option>
            <?php endif; ?>
            <?php foreach ($postCategories as $cat): ?>
            <option value="<?= sanitize($cat['name']) ?>" <?= $curCat === $cat['name'] ? 'selected' : '' ?>><?= sanitize($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (!$postCategories): ?>
          <small style="color:#8892A4;display:block;margin-top:.4rem">Add a category above first.</small>
          <?php endif; ?>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status">
            <option value="draft" <?= ($ep['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="published" <?= ($ep['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Featured Image</label>
        <?php if (!empty($ep['image'])): ?>
          <div style="margin:.5rem 0 1rem"><img src="<?= SITE_URL ?>/uploads/blog/<?= sanitize($ep['image']) ?>" alt="" style="max-height:160px;border-radius:8px"></div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
      </div>
      <div class="form-group"><label>Excerpt</label><textarea name="excerpt" style="min-height:80px"><?= sanitize($ep['excerpt'] ?? '') ?></textarea></div>
      <div class="form-group">
        <label>Content</label>
        <textarea name="content" style="min-height:300px"><?= sanitize($ep['content'] ?? '') ?></textarea>
        <small style="color:#8892A4;display:block;margin-top:.4rem">You can write HTML here (e.g. copy-paste formatted content from another editor) — it will be rendered as-is on the blog page.</small>
      </div>

      <hr style="border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0">
      <h3 style="font-size:.95rem;margin-bottom:1rem">SEO</h3>
      <div class="form-group">
        <label>Focus Keyphrase</label>
        <input name="focus_keyphrase" value="<?= sanitize($ep['focus_keyphrase'] ?? '') ?>" placeholder="e.g. website design tips">
        <small style="color:#8892A4;display:block;margin-top:.4rem">For your own reference when writing the content above — not shown on the page.</small>
      </div>
      <div class="form-group">
        <label>SEO Title</label>
        <input name="seo_title" value="<?= sanitize($ep['seo_title'] ?? '') ?>" placeholder="Leave blank to use the post title">
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description" style="min-height:80px" placeholder="Leave blank to use the site default"><?= sanitize($ep['meta_description'] ?? '') ?></textarea>
      </div>

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
