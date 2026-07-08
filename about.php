<?php
$pageTitle = 'About Us';
require_once 'includes/header.php';
$stats = $db->query("SELECT * FROM stats ORDER BY sort_order")->fetchAll();
?>

<section class="page-hero">
  <div class="container">
    <span class="section-label">Who We Are</span>
    <h1>About Creative Elements</h1>
    <p>A trusted digital agency delivering global-standard creative for businesses in Melbourne, Sydney, and Sri Lanka.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="section-label">Our Story</span>
        <h2>Built on Results, Driven by Creativity</h2>
        <p style="color:#313131;margin-top:1rem;font-size:.95rem;line-height:1.8">
          <?= sanitize($settings['about_text'] ?? '') ?>
        </p>
        <p style="color:#313131;margin-top:1rem;font-size:.95rem;line-height:1.8">
          Founded and headquartered in Colombo, Sri Lanka, we serve clients across Melbourne and Sydney who want world-class digital output without the premium agency price tag. We believe great design shouldn't cost a fortune — and great results shouldn't be a mystery.
        </p>
        <div style="display:flex;gap:1rem;margin-top:1.75rem;flex-wrap:wrap">
          <a href="/contact.php" class="btn btn-primary">Work With Us</a>
          <a href="/our-work.php" class="btn btn-dark">View Our Work</a>
        </div>
      </div>
      <div class="why-visual">
        <div class="why-card">
          <h3>Our Mission</h3>
          <p style="color:rgba(255,255,255,.7)">To make digital excellence accessible to every business — whether you're a solo operator in Sydney or a growing brand in Melbourne. We bring global creative talent at a price that works for you.</p>
          <div style="margin-top:1.25rem">
            <span class="why-badge">🇦🇺 Australia-Focused</span>
            <span class="why-badge">🌍 Global Standards</span>
            <span class="why-badge">💬 Transparent Pricing</span>
            <span class="why-badge">⚡ Fast Turnaround</span>
          </div>
        </div>
      </div>
    </div>
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

<!-- Values -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <span class="section-label">What Drives Us</span>
      <h2>Our Core Values</h2>
    </div>
    <div class="services-grid">
      <?php
      $values = [
        ['⚡', 'Speed & Efficiency', 'We respect your time. Projects are scoped, scheduled, and delivered on time — every time.'],
        ['🎯', 'Results First', 'Every design decision is made with your business goals in mind. Beautiful and functional.'],
        ['💬', 'Transparent Communication', 'No jargon, no surprises. Clear updates and honest pricing throughout.'],
        ['🔧', 'After-Launch Support', 'We don\'t disappear after going live. Dedicated support for every client, always.'],
        ['🌍', 'Global Quality, Local Pricing', 'World-class creative at prices designed for the Australian small business market.'],
        ['🤝', 'Long-Term Partnerships', 'We build relationships, not just websites. Most of our clients have been with us 3+ years.'],
      ];
      foreach ($values as $v): ?>
      <div class="service-card">
        <div style="font-size:2rem;margin-bottom:1rem"><?= $v[0] ?></div>
        <h3><?= htmlspecialchars($v[1]) ?></h3>
        <p><?= htmlspecialchars($v[2]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container">
    <h2>Want to Know More?</h2>
    <p>Book a free 30-minute strategy call and let's talk about your business goals.</p>
    <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn" style="background:white;color:#804899;font-weight:700" target="_blank">Chat on WhatsApp</a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
