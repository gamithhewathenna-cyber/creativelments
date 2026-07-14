<?php
require_once 'includes/config.php';
$db = getDB();
$slug = $_GET['slug'] ?? '';
$stmt = $db->prepare("SELECT * FROM posts WHERE slug=? AND status='published'");
$stmt->execute([$slug]);
$post = $stmt->fetch();
if (!$post) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = '404';
} else {
    $pageTitle      = $post['title'];
    $seoTitle       = $post['seo_title'] ?? '';
    $seoDescription = $post['meta_description'] ?? '';
    if (!empty($post['image'])) {
        $ogImage = rtrim(SITE_URL, '/') . '/uploads/blog/' . $post['image'];
    }
}

require_once 'includes/header.php';

$relatedPosts = [];
if ($post) {
    echo renderBreadcrumbs([
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Blog', 'url' => '/blog.php'],
        ['label' => $post['title'], 'url' => null],
    ]);
    $rp = $db->prepare("SELECT title, slug, excerpt, image, created_at FROM posts WHERE status='published' AND id != ? ORDER BY created_at DESC LIMIT 3");
    $rp->execute([$post['id']]);
    $relatedPosts = $rp->fetchAll();

    $recentStmt = $db->prepare("SELECT title, slug, image, created_at FROM posts WHERE status='published' AND id != ? ORDER BY created_at DESC LIMIT 5");
    $recentStmt->execute([$post['id']]);
    $recentPosts = $recentStmt->fetchAll();

    $recentWork = $db->query("SELECT title, image, link FROM projects WHERE active=1 ORDER BY created_at DESC LIMIT 3")->fetchAll();
}

if ($post):
    $articleSchema = [
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => $post['title'],
        'description'   => $post['excerpt'] ?: $post['meta_description'],
        'datePublished' => date('c', strtotime($post['created_at'])),
        'dateModified'  => date('c', strtotime($post['updated_at'] ?? $post['created_at'])),
        'author'        => ['@type' => 'Organization', 'name' => SITE_NAME],
        'publisher'     => ['@type' => 'Organization', 'name' => SITE_NAME],
        'mainEntityOfPage' => $canonicalUrl,
    ];
    if (!empty($ogImage)) $articleSchema['image'] = $ogImage;
    echo '<script type="application/ld+json">' . json_encode($articleSchema, JSON_UNESCAPED_SLASHES) . '</script>';
endif;
?>

<?php if ($post): ?>
<section class="page-hero">
  <div class="container">
    <span class="section-label"><?= sanitize($post['category']) ?></span>
    <h1><?= sanitize($post['title']) ?></h1>
    <?php if ($post['excerpt']): ?>
    <p><?= sanitize($post['excerpt']) ?></p>
    <?php endif; ?>
    <p style="margin-top:1rem;color:rgba(255,255,255,.5);font-size:.85rem"><?= date('d M Y', strtotime($post['created_at'])) ?></p>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width:1200px">
    <div class="blog-post-layout">
      <div class="blog-post-main">
        <?php if (!empty($post['image'])): ?>
        <img src="<?= SITE_URL ?>/uploads/blog/<?= sanitize($post['image']) ?>" alt="<?= sanitize($post['title']) ?>" style="width:100%;border-radius:12px;margin-bottom:2rem" loading="lazy">
        <?php endif; ?>
        <div class="blog-content" style="line-height:1.85;font-size:.97rem">
          <?= $post['content'] ?>
        </div>
        <div style="margin-top:3rem;padding-top:1.5rem;border-top:1px solid #E2E8F0">
          <a href="/blog.php" class="btn btn-dark">← Back to Blog</a>
        </div>
      </div>

      <div class="blog-sidebar-col">
        <?php if ($recentPosts): ?>
        <aside class="blog-sidebar">
          <h3 class="blog-sidebar-title">Recent Posts</h3>
          <?php foreach ($recentPosts as $rec): ?>
          <a href="/blog-post.php?slug=<?= urlencode($rec['slug']) ?>" class="blog-sidebar-item">
            <div class="blog-sidebar-thumb">
              <?php if ($rec['image']): ?>
                <img src="<?= SITE_URL ?>/uploads/blog/<?= sanitize($rec['image']) ?>" alt="<?= sanitize($rec['title']) ?>" loading="lazy">
              <?php endif; ?>
            </div>
            <div>
              <h4><?= sanitize($rec['title']) ?></h4>
              <span><?= date('d M Y', strtotime($rec['created_at'])) ?></span>
            </div>
          </a>
          <?php endforeach; ?>
        </aside>
        <?php endif; ?>

        <?php if ($recentWork): ?>
        <aside class="blog-sidebar blog-sidebar-work">
          <h3 class="blog-sidebar-title">Our Recent Work</h3>
          <div class="blog-sidebar-work-grid">
            <?php foreach ($recentWork as $w): ?>
            <a href="<?= !empty($w['link']) ? sanitize($w['link']) : '/our-work.php' ?>" class="blog-work-item" <?= !empty($w['link']) ? 'target="_blank" rel="noopener"' : '' ?>>
              <?php if ($w['image']): ?>
                <img src="<?= SITE_URL ?>/uploads/projects/<?= sanitize($w['image']) ?>" alt="<?= sanitize($w['title']) ?>" loading="lazy">
              <?php endif; ?>
              <span><?= sanitize($w['title']) ?></span>
            </a>
            <?php endforeach; ?>
          </div>
          <a href="/our-work.php" class="btn btn-outline" style="width:100%;text-align:center;margin-top:1.25rem">View All Work</a>
        </aside>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if ($relatedPosts): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-header"><h2>More From the Blog</h2></div>
    <div class="blog-grid">
      <?php foreach ($relatedPosts as $rp): ?>
      <a href="/blog-post.php?slug=<?= urlencode($rp['slug']) ?>" class="blog-card" style="display:block;text-decoration:none;color:inherit">
        <div class="blog-thumb">
          <?php if ($rp['image']): ?>
            <img src="<?= SITE_URL ?>/uploads/blog/<?= sanitize($rp['image']) ?>" alt="<?= sanitize($rp['title']) ?>" loading="lazy">
          <?php else: ?>
            <?= sanitize($rp['title']) ?>
          <?php endif; ?>
        </div>
        <div class="blog-body">
          <h3><?= sanitize($rp['title']) ?></h3>
          <p><?= sanitize($rp['excerpt']) ?></p>
          <div class="blog-meta"><?= date('d M Y', strtotime($rp['created_at'])) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php else: ?>
<section class="section" style="text-align:center">
  <h2>Post not found</h2>
  <a href="/blog.php" class="btn btn-dark" style="margin-top:1.5rem">← Back to Blog</a>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
