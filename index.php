<?php
$pageTitle = 'Digital Marketing Agency for Melbourne & Sydney';
require_once 'includes/header.php';

$services      = $db->query("SELECT * FROM services WHERE active=1 ORDER BY sort_order")->fetchAll();
$projects      = $db->query("SELECT * FROM projects WHERE active=1 ORDER BY RAND() LIMIT 12")->fetchAll();
$testimonials  = $db->query("SELECT * FROM testimonials WHERE active=1 ORDER BY sort_order")->fetchAll();
$stats         = $db->query("SELECT * FROM stats ORDER BY sort_order")->fetchAll();
$heroSlides    = $db->query("SELECT * FROM hero_slides WHERE active=1 ORDER BY sort_order")->fetchAll();
$clientLogos   = $db->query("SELECT * FROM client_logos WHERE active=1 ORDER BY sort_order")->fetchAll();
?>

<!-- ===== HERO ===== -->
<?php if ($heroSlides): ?>
<section class="hero">
  <div class="hero-slider">
    <?php foreach ($heroSlides as $i => $slide):
      $mobileImg = !empty($slide['image_mobile']) ? $slide['image_mobile'] : $slide['image'];
    ?>
    <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>" style="--bg-desktop:url('<?= SITE_URL ?>/uploads/hero/<?= sanitize($slide['image']) ?>');--bg-mobile:url('<?= SITE_URL ?>/uploads/hero/<?= sanitize($mobileImg) ?>')">
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
          <h3>
            <?php if (!empty($svc['slug'])): ?>
            <a href="/service.php?slug=<?= urlencode($svc['slug']) ?>" style="color:inherit"><?= sanitize($svc['title']) ?></a>
            <?php else: ?>
            <?= sanitize($svc['title']) ?>
            <?php endif; ?>
          </h3>
          <p><?= sanitize($svc['description']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===== SEO BEFORE / AFTER ===== -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header seo-header-box">
      <span class="section-label">Real Results</span>
      <h2>See the Difference Real SEO Makes</h2>
      <p>From poor visibility to higher rankings and increased organic traffic. Our SEO strategies deliver measurable improvements that help businesses attract more customers and grow online.</p>
    </div>
    <div class="seo-compare-grid">
      <div class="seo-compare-item">
        <h3>Before SEO</h3>
        <?php if (!empty($settings['seo_before_image'])): ?>
          <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($settings['seo_before_image']) ?>" alt="Before SEO — low search visibility, poor rankings, and low impressions" class="seo-compare-image" loading="lazy">
        <?php else: ?>
          <div class="seo-compare-placeholder">Upload a Search Console / Analytics<br>screenshot from Admin → Settings</div>
        <?php endif; ?>
      </div>
      <div class="seo-compare-item">
        <h3>After SEO</h3>
        <?php if (!empty($settings['seo_after_image'])): ?>
          <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($settings['seo_after_image']) ?>" alt="After SEO — improved rankings, visibility, and organic traffic" class="seo-compare-image" loading="lazy">
        <?php else: ?>
          <div class="seo-compare-placeholder">Upload a Search Console / Analytics<br>screenshot from Admin → Settings</div>
        <?php endif; ?>
      </div>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a href="/contact.php" class="btn btn-primary">Let's Optimize Your Website</a>
    </div>
  </div>
</section>

<!-- ===== GROWTH CTA BANNER ===== -->
<section class="cta-banner"<?= !empty($settings['cta_banner_image']) ? ' style="background-image:url(\'' . SITE_URL . '/uploads/banners/' . sanitize($settings['cta_banner_image']) . '\')"' : '' ?>>
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
          <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($settings['unique_section_image']) ?>" alt="What Makes Us Unique" loading="lazy">
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
          <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($settings['why_us_image']) ?>" alt="Global Standards, Local Understanding" class="why-visual-image" loading="lazy">
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
      <div class="portfolio-slide project-trigger"
           data-title="<?= sanitize($proj['title']) ?>"
           data-desc="<?= sanitize($proj['description'] ?? '') ?>"
           data-img1="<?= $proj['image'] ? SITE_URL . '/uploads/projects/' . sanitize($proj['image']) : '' ?>"
           data-img2="<?= !empty($proj['image2']) ? SITE_URL . '/uploads/projects/' . sanitize($proj['image2']) : '' ?>"
           data-img3="<?= !empty($proj['image3']) ? SITE_URL . '/uploads/projects/' . sanitize($proj['image3']) : '' ?>"
           data-link="<?= sanitize($proj['link'] ?? '') ?>">
        <?php if ($proj['image']): ?>
          <img class="portfolio-slide-img" src="<?= SITE_URL ?>/uploads/projects/<?= sanitize($proj['image']) ?>" alt="<?= sanitize($proj['title']) ?>" loading="lazy">
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
    <div class="section-header logos-section-header">
      <h2 class="logos-heading">Trusted by 130+ Businesses Across Australia & Sri Lanka</h2>
    </div>
    <div class="logos-grid">
      <?php foreach ($clientLogos as $logo): ?>
      <div class="logos-item">
        <img src="<?= SITE_URL ?>/uploads/logos/<?= sanitize($logo['image']) ?>" alt="<?= sanitize($logo['name']) ?>" loading="lazy">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== TESTIMONIALS ===== -->
<?php if ($testimonials): ?>
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2>What Australian & Global Clients Say About Us</h2>
    </div>
    <div class="reviews-scroll-wrap">
      <div class="reviews-scroll" id="reviewsScroll">
        <?php foreach ($testimonials as $t): ?>
        <div class="testimonial-card">
          <div class="testimonial-author">
            <div class="author-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
            <div class="author-info">
              <strong><?= sanitize($t['name']) ?></strong>
              <span><?= date('j F Y', strtotime($t['created_at'])) ?></span>
            </div>
            <svg class="google-icon" viewBox="0 0 48 48" width="20" height="20">
              <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
              <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
              <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
              <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
            </svg>
          </div>
          <div class="stars"><?= str_repeat('★', intval($t['rating'])) ?></div>
          <p class="review-text"><?= sanitize($t['content']) ?></p>
          <button class="review-more" type="button">Read more</button>
        </div>
        <?php endforeach; ?>
      </div>
      <?php if (count($testimonials) > 1): ?>
      <button class="review-arrow review-arrow-prev" aria-label="Previous reviews">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
      </button>
      <button class="review-arrow review-arrow-next" aria-label="Next reviews">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
