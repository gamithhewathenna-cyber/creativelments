<?php
$pageTitle = 'Digital Marketing Agency for Melbourne & Sydney';
require_once 'includes/header.php';

$services      = $db->query("SELECT * FROM services WHERE active=1 ORDER BY sort_order")->fetchAll();
$projects      = $db->query("SELECT * FROM projects WHERE active=1 ORDER BY sort_order LIMIT 6")->fetchAll();
$testimonials  = $db->query("SELECT * FROM testimonials WHERE active=1 ORDER BY sort_order")->fetchAll();
$stats         = $db->query("SELECT * FROM stats ORDER BY sort_order")->fetchAll();
$heroSlides    = $db->query("SELECT * FROM hero_slides WHERE active=1 ORDER BY sort_order")->fetchAll();
$clientLogos   = $db->query("SELECT * FROM client_logos WHERE active=1 ORDER BY sort_order")->fetchAll();
?>

<!-- ===== HERO ===== -->
<?php if ($heroSlides): ?>
<section class="hero">
  <div class="hero-slider">
    <?php foreach ($heroSlides as $i => $slide): ?>
    <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>" style="background-image:url('/uploads/hero/<?= sanitize($slide['image']) ?>')">
      <div class="container">
        <div class="hero-content">
          <?php if (!empty($slide['badge'])): ?>
          <div class="hero-badge"><span>⚡</span> <?= sanitize($slide['badge']) ?></div>
          <?php endif; ?>
          <h1<?= !empty($slide['title_font_size']) ? ' style="font-size:' . intval($slide['title_font_size']) . 'px"' : '' ?>><?= sanitizeBr($slide['title']) ?></h1>
          <?php if (!empty($slide['description'])): ?>
          <p><?= sanitize($slide['description']) ?></p>
          <?php endif; ?>
          <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
          <div class="hero-cta">
            <a href="<?= sanitize($slide['button_link']) ?>" class="btn btn-primary" target="_blank"><?= sanitize($slide['button_text']) ?></a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php if (count($heroSlides) > 1): ?>
  <button class="hero-arrow hero-arrow-prev" aria-label="Previous slide">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
  </button>
  <button class="hero-arrow hero-arrow-next" aria-label="Next slide">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
  </button>
  <?php endif; ?>
</section>
<?php endif; ?>

<!-- ===== STATS BAND ===== -->
<section class="stats-band">
  <div class="container">
    <div class="stats-grid">
      <?php foreach ($stats as $stat): ?>
      <div>
        <div class="stat-num counter" data-target="<?= intval($stat['value']) ?>" data-suffix="<?= sanitize($stat['suffix']) ?>"><?= sanitize($stat['value']) ?><?= sanitize($stat['suffix']) ?></div>
        <div class="stat-label"><span class="stat-dot"></span><?= sanitize($stat['label']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== SERVICES ===== -->
<section class="section">
  <div class="container">
    <div class="services-showcase">
      <div class="services-showcase-left">
        <h2>Why Melbourne & Sydney Businesses Choose Creative Elements</h2>
        <p>We combine global design standards with local market knowledge — so your business gets found, clicked, and remembered.</p>
        <a href="/services.php" class="btn btn-primary">Read More</a>
      </div>
      <div class="services-showcase-grid">
        <?php foreach (array_slice($services, 0, 4) as $svc): ?>
        <div class="services-showcase-item">
          <h3><?= sanitize($svc['title']) ?></h3>
          <p><?= sanitize($svc['description']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===== GROWTH CTA BANNER ===== -->
<section class="cta-banner"<?= !empty($settings['cta_banner_image']) ? ' style="background-image:url(\'/uploads/banners/' . sanitize($settings['cta_banner_image']) . '\')"' : '' ?>>
  <div class="cta-banner-overlay"></div>
  <div class="container">
    <div class="cta-banner-content">
      <h2>Ready to grow?</h2>
      <p>Get a free strategy session with our team – no obligation, just results.</p>
    </div>
  </div>
</section>

<!-- ===== WHAT MAKES US UNIQUE ===== -->
<section class="section">
  <div class="container">
    <div class="unique-showcase">
      <div class="unique-showcase-image">
        <?php if (!empty($settings['unique_section_image'])): ?>
          <img src="/uploads/sections/<?= sanitize($settings['unique_section_image']) ?>" alt="What Makes Us Unique">
        <?php else: ?>
          <div class="unique-showcase-placeholder">Upload an image from<br>Admin → Settings</div>
        <?php endif; ?>
      </div>
      <div class="unique-showcase-content">
        <h2>What Makes<br>Us Unique?</h2>
        <p><?= sanitize($settings['unique_section_text'] ?? '') ?></p>
      </div>
    </div>
  </div>
</section>

<!-- ===== WHY US ===== -->
<section class="section section-alt">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="section-label">Why Choose Us</span>
        <h2>Global Standards, Local Understanding</h2>
        <div class="why-list">
          <?php
          $whyItems = [
            ['Fast-Loading Websites', 'We optimise for Core Web Vitals so your site loads under 2 seconds on any device.'],
            ['Melbourne & Sydney Market Knowledge', 'We know what Australian consumers search for and build accordingly.'],
            ['Transparent Pricing', 'No hidden fees. Flexible payment plans designed for growing businesses.'],
            ['Dedicated After-Launch Support', 'We stay with you — maintenance, updates, and growth strategy included.'],
            ['15+ Years of Proven Expertise', '130+ satisfied clients across Australia and Sri Lanka.'],
          ];
          foreach ($whyItems as $item): ?>
          <div class="why-item">
            <div class="why-check">
              <svg viewBox="0 0 24 24" fill="none"><polyline points="20 6 9 17 4 12" stroke="white" stroke-width="3"/></svg>
            </div>
            <div>
              <h4><?= htmlspecialchars($item[0]) ?></h4>
              <p><?= htmlspecialchars($item[1]) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="why-visual">
        <?php if (!empty($settings['why_us_image'])): ?>
          <img src="/uploads/sections/<?= sanitize($settings['why_us_image']) ?>" alt="Global Standards, Local Understanding" class="why-visual-image">
        <?php else: ?>
          <div class="why-visual-placeholder">Upload an image from<br>Admin → Settings</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===== RECENT WORK ===== -->
<?php if ($projects): ?>
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2>Recent Projects</h2>
    </div>
  </div>
  <div class="portfolio-scroll-wrap">
    <div class="portfolio-scroll" id="portfolioScroll">
      <?php foreach ($projects as $proj): ?>
      <div class="portfolio-slide">
        <?php if ($proj['image']): ?>
          <img class="portfolio-slide-img" src="/uploads/projects/<?= sanitize($proj['image']) ?>" alt="<?= sanitize($proj['title']) ?>">
        <?php else: ?>
          <div class="portfolio-placeholder"><?= sanitize($proj['title']) ?></div>
        <?php endif; ?>
        <div class="portfolio-slide-caption"><?= sanitize($proj['title']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if (count($projects) > 1): ?>
    <button class="portfolio-arrow portfolio-arrow-prev" aria-label="Previous projects">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="portfolio-arrow portfolio-arrow-next" aria-label="Next projects">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <?php endif; ?>
  </div>
  <div class="container">
    <div style="text-align:center;margin-top:2.5rem">
      <a href="/our-work.php" class="btn btn-primary">View All Projects</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== CLIENT LOGOS ===== -->
<?php if ($clientLogos): ?>
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="logos-heading">Trusted by 130+ Businesses Across Australia & Sri Lanka</h2>
    </div>
    <div class="logos-grid">
      <?php foreach ($clientLogos as $logo): ?>
      <div class="logos-item">
        <img src="/uploads/logos/<?= sanitize($logo['image']) ?>" alt="<?= sanitize($logo['name']) ?>">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== TESTIMONIALS ===== -->
<?php if ($testimonials): ?>
<section class="section section-dark">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Client Reviews</span>
      <h2>What Australian & Global Clients Say</h2>
    </div>
    <div class="testimonials-grid">
      <?php foreach ($testimonials as $t): ?>
      <div class="testimonial-card">
        <div class="stars"><?= str_repeat('★', intval($t['rating'])) ?></div>
        <p>"<?= sanitize($t['content']) ?>"</p>
        <div class="testimonial-author">
          <div class="author-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
          <div class="author-info">
            <strong><?= sanitize($t['name']) ?></strong>
            <span><?= sanitize($t['company']) ?> — <?= sanitize($t['location']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== CTA ===== -->
<section class="cta-section">
  <div class="container">
    <h2>Ready to Grow?</h2>
    <p>Get a free strategy session with our team — no obligation, just results.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn" style="background:white;color:#804899;font-weight:700" target="_blank">Chat on WhatsApp</a>
      <a href="/contact.php" class="btn btn-outline">Send a Message</a>
    </div>
  </div>
</section>

<!-- Floating WhatsApp -->
<a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="wa-float" target="_blank" aria-label="WhatsApp">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
</a>

<?php require_once 'includes/footer.php'; ?>
