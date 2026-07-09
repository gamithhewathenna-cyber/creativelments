<?php
require_once 'includes/config.php';
$db = getDB();
$slug = $_GET['slug'] ?? '';
$stmt = $db->prepare("SELECT * FROM services WHERE slug=? AND active=1");
$stmt->execute([$slug]);
$service = $stmt->fetch();
if (!$service) { header('HTTP/1.0 404 Not Found'); $pageTitle = '404'; } else { $pageTitle = $service['title']; }

require_once 'includes/header.php';

$icons = [
    'monitor'      => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
    'pen-tool'     => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><circle cx="11" cy="11" r="2"/></svg>',
    'trending-up'  => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
    'shopping-bag' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
    'share-2'      => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>',
    'tablet'       => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
    'star'         => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
];
?>

<?php if ($service): ?>
<section class="page-hero">
  <div class="container">
    <span class="section-label">Services</span>
    <h1><?= sanitize($service['title']) ?></h1>
    <?php if ($service['description']): ?><p><?= sanitize($service['description']) ?></p><?php endif; ?>
  </div>
</section>

<!-- Section 1 -->
<section class="section">
  <div class="container">
    <div class="why-grid">
      <div>
        <div class="service-icon" style="margin-bottom:1.25rem"><?= $icons[$service['icon']] ?? $icons['star'] ?></div>
        <div style="line-height:1.85;font-size:.97rem;color:#313131">
          <?= nl2br(sanitize($service['content'] ?: $service['description'])) ?>
        </div>
      </div>
      <div class="why-visual">
        <?php if (!empty($service['detail_image1'])): ?>
          <img src="/uploads/services/<?= sanitize($service['detail_image1']) ?>" alt="<?= sanitize($service['title']) ?>" class="why-visual-image">
        <?php else: ?>
          <div class="why-visual-placeholder">Upload an image from<br>Admin → Services</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Section 2 -->
<?php if (!empty($service['content2']) || !empty($service['detail_image2'])): ?>
<section class="section section-alt">
  <div class="container">
    <div class="why-grid">
      <div class="why-visual" style="order:-1">
        <?php if (!empty($service['detail_image2'])): ?>
          <img src="/uploads/services/<?= sanitize($service['detail_image2']) ?>" alt="<?= sanitize($service['title']) ?>" class="why-visual-image">
        <?php else: ?>
          <div class="why-visual-placeholder">Upload an image from<br>Admin → Services</div>
        <?php endif; ?>
      </div>
      <div>
        <div style="line-height:1.85;font-size:.97rem;color:#313131">
          <?= nl2br(sanitize($service['content2'])) ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section" style="text-align:center;padding-top:0">
  <div class="container" style="display:flex;gap:1rem;flex-wrap:wrap;justify-content:center">
    <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn btn-primary" target="_blank">Get Pricing</a>
    <a href="/contact.php" class="btn btn-outline" style="color:#0A0F1E;border-color:#E2E8F0">Contact Us</a>
    <a href="/services.php" class="btn btn-dark">← Back to Services</a>
  </div>
</section>
<?php else: ?>
<section class="section" style="text-align:center">
  <h2>Service not found</h2>
  <a href="/services.php" class="btn btn-dark" style="margin-top:1.5rem">← Back to Services</a>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
