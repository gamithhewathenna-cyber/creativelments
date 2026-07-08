<?php
$pageTitle = 'Digital Marketing Agency for Melbourne & Sydney';
require_once 'includes/header.php';

$services      = $db->query("SELECT * FROM services WHERE active=1 ORDER BY sort_order")->fetchAll();
$projects      = $db->query("SELECT * FROM projects WHERE active=1 ORDER BY sort_order LIMIT 6")->fetchAll();
$testimonials  = $db->query("SELECT * FROM testimonials WHERE active=1 ORDER BY sort_order")->fetchAll();
$stats         = $db->query("SELECT * FROM stats ORDER BY sort_order")->fetchAll();
$about         = $settings['about_text'] ?? '';
?>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid-overlay"></div>
  <div class="container">
    <div class="hero-content">
      <div class="hero-badge">
        <span>⚡</span> Trusted by 130+ businesses across Australia & Sri Lanka
      </div>
      <h1>Your Digital Agency for <em>Melbourne & Sydney</em></h1>
      <p>We combine global design standards with local market knowledge — so your business gets found, clicked, and remembered.</p>
      <div class="hero-cta">
        <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn btn-primary" target="_blank">Get a Free Quote</a>
        <a href="/our-work.php" class="btn btn-outline">View Our Work</a>
      </div>
      <div class="hero-slides">
        <?php
        $slides = [
          'Web Design & Development — Fast, modern, Google-ready',
          'Social Media Management — Content that converts',
          'Ongoing Support — We don\'t disappear after launch',
          'Design Retainers — Fresh creative, every month',
          'Flexible Payment Plans — Pay in stages, zero stress',
        ];
        foreach ($slides as $i => $slide): ?>
        <div class="slide-item <?= $i === 0 ? 'active' : '' ?>">
          <span class="slide-dot"></span>
          <span class="slide-label"><?= htmlspecialchars($slide) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Hero Card -->
    <div class="hero-card">
      <div class="hero-card-title">Start Your Project</div>
      <div class="hero-card-sub">Free strategy session — no obligation, just results.</div>
      <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn btn-primary" target="_blank">
        Chat on WhatsApp
      </a>
      <div class="hero-card-divider"></div>
      <div class="hero-card-stats">
        <div class="hcs-item">
          <div class="hcs-num">15+</div>
          <div class="hcs-label">Years Experience</div>
        </div>
        <div class="hcs-item">
          <div class="hcs-num">130+</div>
          <div class="hcs-label">Clients Worldwide</div>
        </div>
        <div class="hcs-item">
          <div class="hcs-num">3</div>
          <div class="hcs-label">Countries Served</div>
        </div>
        <div class="hcs-item">
          <div class="hcs-num">100%</div>
          <div class="hcs-label">On-time Delivery</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== STATS BAND ===== -->
<section class="stats-band">
  <div class="container">
    <div class="stats-grid">
      <?php foreach ($stats as $stat): ?>
      <div>
        <div class="stat-num counter" data-target="<?= intval($stat['value']) ?>"><?= sanitize($stat['value']) ?><?= sanitize($stat['suffix']) ?></div>
        <div class="stat-label"><?= sanitize($stat['label']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== SERVICES ===== -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="section-label">What We Do</span>
      <h2>Why Melbourne & Sydney Businesses Choose Creative Elements</h2>
      <p>We combine global design standards with local market knowledge — so your business gets found, clicked, and remembered.</p>
    </div>
    <div class="services-grid">
      <?php
      $icons = [
        'monitor'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
        'pen-tool'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>',
        'trending-up'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
        'shopping-bag' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'share-2'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>',
        'tablet'       => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
        'star'         => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
      ];
      foreach ($services as $svc): ?>
      <div class="service-card">
        <div class="service-icon"><?= $icons[$svc['icon']] ?? $icons['star'] ?></div>
        <h3><?= sanitize($svc['title']) ?></h3>
        <p><?= sanitize($svc['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a href="/services.php" class="btn btn-dark">View All Services</a>
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
        <div class="why-card">
          <h3><?= sanitize($settings['hero_title'] ?? 'About Creative Elements') ?></h3>
          <p><?= sanitize(substr($about, 0, 280)) ?>…</p>
          <a href="/about.php" class="btn btn-primary">Learn More About Us</a>
          <div style="margin-top:1.5rem">
            <?php foreach (['Web Design', 'SEO', 'Branding', 'Shopify', 'Google Ads', 'Social Media'] as $tag): ?>
            <span class="why-badge"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== RECENT WORK ===== -->
<?php if ($projects): ?>
<section class="section section-dark">
  <div class="container">
    <div class="section-header">
      <span class="section-label">Our Work</span>
      <h2>Recent Projects</h2>
      <p>A selection of branding, web, and creative work delivered for our clients.</p>
    </div>
    <div class="portfolio-grid">
      <?php foreach ($projects as $proj): ?>
      <div class="portfolio-item">
        <?php if ($proj['image']): ?>
          <img class="portfolio-img" src="/uploads/projects/<?= sanitize($proj['image']) ?>" alt="<?= sanitize($proj['title']) ?>">
        <?php else: ?>
          <div class="portfolio-placeholder"><?= sanitize($proj['title']) ?></div>
        <?php endif; ?>
        <div class="portfolio-overlay">
          <div class="portfolio-meta">
            <h4><?= sanitize($proj['title']) ?></h4>
            <span><?= sanitize($proj['category']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a href="/our-work.php" class="btn btn-primary">View All Projects</a>
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
