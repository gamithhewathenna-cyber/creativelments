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
        <?php
          $shareUrl   = urlencode($canonicalUrl);
          $shareTitle = urlencode($post['title']);
        ?>
        <div class="blog-share">
          <span class="blog-share-label">Share:</span>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" rel="noopener" aria-label="Share on X (Twitter)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.6 8.7L23.3 22h-7.1l-5.5-6.9L4.4 22H1.3l8.1-9.3L1 2h7.3l5 6.3L18.9 2zm-1.2 18h1.9L7.3 4h-2l12.4 16z"/></svg>
          </a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45z"/></svg>
          </a>
          <a href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.1-1.7-.9-2-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.5-2.4-1.5-.9-.8-1.5-1.7-1.6-2-.2-.3 0-.5.1-.6.1-.1.3-.3.4-.5.1-.1.2-.3.3-.4.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.5-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1.1 2.8 1.2 3c.1.2 2.2 3.3 5.3 4.6.7.3 1.3.5 1.8.7.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3z"/><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.6 0-3.1-.4-4.4-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 20.2 12 8.2 8.2 0 0 1 12 20.2z"/></svg>
          </a>
          <button type="button" class="blog-share-copy" data-url="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>" aria-label="Copy link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>
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
