<?php
$pageTitle = 'About Us';
require_once 'includes/header.php';
$stats = $db->query("SELECT * FROM stats ORDER BY sort_order")->fetchAll();

$reasonDefaults = [
    1 => ['Global quality, local pricing', 'Melbourne and Sydney agency rates without Melbourne and Sydney overheads — you get more for your budget.'],
    2 => ['Built to rank in Australia', "Every website and campaign is optimised specifically for Australian search behaviour and Google's local ranking factors."],
    3 => ['Senior team on every project', 'No juniors, no outsourcing. Gamith and the core team handle your work personally from start to finish.'],
    4 => ['We stay after launch', "After-sale support is built into how we work — not an upsell. We're your long-term partner, not a one-project shop."],
];
?>

<section class="page-hero page-hero-light">
  <div class="container">
    <span class="section-label">Who We Are</span>
    <h1><?= sanitize($settings['about_hero_heading'] ?? 'The Digital Agency Melbourne & Sydney Businesses Trust to Grow Online') ?></h1>
  </div>
</section>

<!-- Stats -->
<section class="stats-band">
  <div class="container">
    <div class="stats-grid">
      <?php foreach ($stats as $s): ?>
      <div>
        <div class="stat-num"><?= sanitize($s['value']) ?><?= sanitize($s['suffix']) ?></div>
        <div class="stat-label"><span class="stat-dot"></span><?= sanitize($s['label']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Our Agency Story -->
<section class="section">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="section-label">Our Agency Story</span>
        <h2><?= sanitize($settings['about_story_heading'] ?? 'From Colombo to Melbourne — How We Became the Agency Australian Businesses Rely On') ?></h2>
        <p style="color:#313131;margin-top:1rem;font-size:.95rem;line-height:1.8">
          <?= nl2br(sanitize($settings['about_text'] ?? "Creative Elements was founded in Colombo, Sri Lanka, with a single goal: help businesses grow online without the agency runaround. Today, we work with clients across Melbourne, Sydney, and Australia — delivering web design, SEO, branding, and digital marketing that produces results you can actually measure.\nAustralian businesses choose us because we combine global design standards with a deep understanding of what the Melbourne and Sydney markets respond to. We don't do generic templates or one-size-fits-all strategies — every project is built around your customers, your competitors, and your goals. With 15+ years of combined expertise and 130+ clients worldwide, we've built websites, brands, and campaigns for businesses ranging from Colombo startups to Australian hospitality groups and e-commerce stores. Whatever stage your business is at, we'll help you stand out and scale.")) ?>
        </p>
        <div style="display:flex;gap:1rem;margin-top:1.75rem;flex-wrap:wrap">
          <a href="/contact.php" class="btn btn-primary">Work With Us</a>
          <a href="/our-work.php" class="btn btn-dark">View Our Work</a>
        </div>
      </div>
      <div class="why-visual">
        <?php if (!empty($settings['about_story_image'])): ?>
          <img src="/uploads/sections/<?= sanitize($settings['about_story_image']) ?>" alt="Our Agency Story" class="why-visual-image">
        <?php else: ?>
          <div class="why-visual-placeholder">Upload an image from<br>Admin → About Page</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Founder Story -->
<section class="section section-alt">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="section-label" style="font-size:2.2rem;letter-spacing:normal;text-transform:none;font-weight:700">Founder Story</span>
        <p style="color:#313131;margin-top:1rem;font-size:.95rem;line-height:1.8">
          <?= nl2br(sanitize($settings['about_founder_text'] ?? "Creative Elements was founded in 2022 by Gamith Hewathenna — a multimedia specialist with over 15 years of experience across web design, branding, and digital marketing. What started as a passion for design became a full-service agency now trusted by businesses in Melbourne, Sydney, and beyond.\nGamith holds a BSc in Multimedia from Buckinghamshire New University, UK, giving him both the technical rigour and creative foundation to lead projects that perform. He built Creative Elements on the belief that businesses deserve agency-quality work at honest prices — regardless of where they're based.\nUnder Gamith's leadership, Creative Elements has delivered projects for clients across Australia — from Shopify stores for Melbourne retailers to full brand identities for Sydney hospitality groups. His hands-on approach means every client gets a senior creative mind on their project, not just a junior account manager.\nGamith's expertise spans branding, SEO, web and mobile development, digital marketing, and social media strategy. He's known for blending creative instinct with data-driven strategy — the combination that separates campaigns that look good from campaigns that actually grow revenue for Australian businesses.\nFor Gamith, every client project is personal. His dedication to craft, transparency, and measurable results continues to drive Creative Elements forward — making it the go-to digital partner for ambitious businesses in Melbourne, Sydney, Sri Lanka, and around the world.")) ?>
        </p>
      </div>
      <div class="why-visual">
        <?php if (!empty($settings['about_founder_image'])): ?>
          <img src="/uploads/sections/<?= sanitize($settings['about_founder_image']) ?>" alt="Gamith Hewathenna — Founder, Creative Elements" class="why-visual-image">
        <?php else: ?>
          <div class="why-visual-placeholder">Upload an image from<br>Admin → About Page</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2><?= sanitize($settings['about_reasons_heading'] ?? 'Why Australian Businesses Choose Creative Elements Over a Local Agency') ?></h2>
    </div>
    <div class="reasons-grid">
      <?php for ($i = 1; $i <= 4; $i++):
        $title = $settings["reason{$i}_title"] ?? $reasonDefaults[$i][0];
        $desc  = $settings["reason{$i}_desc"] ?? $reasonDefaults[$i][1];
      ?>
      <div class="service-card">
        <h3><?= sanitize($title) ?></h3>
        <p><?= sanitize($desc) ?></p>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container">
    <h2><?= sanitize($settings['about_cta_heading'] ?? 'Ready to work with a digital agency that treats your business like its own?') ?></h2>
    <p><?= sanitize($settings['about_cta_text'] ?? "Get a free consultation and find out exactly what we'd do to grow your Melbourne or Sydney business online.") ?></p>
    <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn" style="background:white;color:#804899;font-weight:700" target="_blank">Chat on WhatsApp</a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
