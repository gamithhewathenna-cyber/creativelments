<?php
$pageTitle = 'Blog';
require_once 'includes/header.php';
$posts = $db->query("SELECT id,title,slug,excerpt,image,category,created_at FROM posts WHERE status='published' ORDER BY created_at DESC")->fetchAll();
?>

<section class="page-hero">
  <div class="container">
    <span class="section-label">Insights & Tips</span>
    <h1>Our Blog</h1>
    <p>Digital marketing, web design tips, and insights for Australian businesses.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($posts): ?>
    <div class="blog-grid">
      <?php foreach ($posts as $post): ?>
      <a href="/blog-post.php?slug=<?= urlencode($post['slug']) ?>" class="blog-card" style="display:block;text-decoration:none;color:inherit">
        <div class="blog-thumb">
          <?php if ($post['image']): ?>
            <img src="<?= SITE_URL ?>/uploads/<?= sanitize($post['image']) ?>" alt="<?= sanitize($post['title']) ?>" loading="lazy">
          <?php else: ?>
            <?= sanitize($post['category']) ?>
          <?php endif; ?>
        </div>
        <div class="blog-body">
          <span class="blog-cat"><?= sanitize($post['category']) ?></span>
          <h3><?= sanitize($post['title']) ?></h3>
          <p><?= sanitize($post['excerpt']) ?></p>
          <div class="blog-meta"><?= date('d M Y', strtotime($post['created_at'])) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="text-align:center;color:#313131;padding:4rem 0">No blog posts yet. Add posts from the admin panel.</p>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
