<?php
$pageTitle = 'Services';
require_once 'includes/header.php';
$services = $db->query("SELECT * FROM services WHERE active=1 ORDER BY sort_order")->fetchAll();
?>

<section class="page-hero">
  <div class="container">
    <span class="section-label">What We Offer</span>
    <h1>Our Services</h1>
    <p>Everything your business needs to dominate online — from stunning websites to SEO and social media that converts.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="services-grid">
      <?php
      $icons = [
        'monitor'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
        'pen-tool'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><circle cx="11" cy="11" r="2"/></svg>',
        'trending-up'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
        'shopping-bag'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'share-2'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>',
        'tablet'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
        'star'=>'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
      ];
      foreach ($services as $svc): ?>
      <div class="service-card">
        <div class="service-icon"><?= $icons[$svc['icon']] ?? $icons['star'] ?></div>
        <h3><?= sanitize($svc['title']) ?></h3>
        <p><?= sanitize($svc['description']) ?></p>
        <div style="margin-top:1.25rem">
          <?php if (!empty($svc['slug'])): ?>
          <a href="/service.php?slug=<?= urlencode($svc['slug']) ?>" class="btn btn-primary btn-sm" style="font-size:.82rem;padding:.6rem 1.2rem">Get Pricing</a>
          <?php else: ?>
          <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn btn-primary btn-sm" target="_blank" style="font-size:.82rem;padding:.6rem 1.2rem">Get Pricing</a>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- How We Deliver Results -->
<section class="section">
  <div class="container">
    <div class="section-header process-header">
      <h2>How We Deliver Results for Australian Businesses</h2>
    </div>
    <div class="process-flow">
      <?php
      $steps = [
        ['Discovery & Strategy', 'We deep-dive into your business, your Australian market, and your competitors — then build a clear strategy before a single pixel is designed.'],
        ['Creative Design', "Our designers create visuals your Melbourne or Sydney audience will respond to — on-brand, user-first, and built to convert."],
        ['Build & Development', "Our developers build fast, scalable, SEO-ready solutions — whether it's a WordPress site, Shopify store, or custom web app."],
        ['Launch & Ongoing Support', "We test across every device and browser, launch with confidence, and stay on hand after go-live — because your success doesn't stop at launch day."],
      ];
      foreach ($steps as $i => $step): ?>
      <?php if ($i > 0): ?><div class="process-arrow">→</div><?php endif; ?>
      <div class="process-step">
        <div class="process-num"><?= sprintf('%02d', $i + 1) ?></div>
        <h3><?= htmlspecialchars($step[0]) ?></h3>
        <p><?= htmlspecialchars($step[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container">
    <h2>Not Sure Which Service You Need?</h2>
    <p>Chat with us — we'll build you a custom package that fits your business goals and budget.</p>
    <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn" style="background:white;color:#804899;font-weight:700" target="_blank">Free Consultation</a>
  </div>
</section>

<a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="wa-float" target="_blank" aria-label="WhatsApp">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
</a>

<?php require_once 'includes/footer.php'; ?>
