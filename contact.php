<?php
$pageTitle = 'Contact Us';
require_once 'includes/header.php';
echo renderBreadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Contact', 'url' => null],
]);
?>

<section class="page-hero">
  <div class="container">
    <span class="section-label">Get In Touch</span>
    <h1>Let's Talk</h1>
    <p>Ready to grow your business online? Send us a message and we'll get back to you within 24 hours.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-card-wrap">
      <div class="contact-card-info">
        <div class="contact-card-info-inner">
          <h3>Contact Information</h3>
          <p>Send us a message and our team will get back to you within 24 hours.</p>
          <div class="contact-info-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l.81-.81a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <a href="tel:<?= sanitize($settings['phone'] ?? '') ?>"><?= sanitize($settings['phone'] ?? '') ?></a>
          </div>
          <div class="contact-info-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <a href="mailto:<?= sanitize($settings['email'] ?? '') ?>"><?= sanitize($settings['email'] ?? '') ?></a>
          </div>
          <div class="contact-info-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span><?= sanitize($settings['address'] ?? '') ?></span>
          </div>
          <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="contact-info-whatsapp" target="_blank">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            Chat on WhatsApp
          </a>
        </div>
        <div class="contact-card-circle"></div>
      </div>

      <div class="contact-card-form">
        <div id="formSuccess" class="form-success" style="display:none">✅ Thank you! We'll be in touch within 24 hours.</div>
        <div id="formError" class="form-error" style="display:none"></div>
        <form class="contact-form contact-form-underline" id="contactForm" novalidate>
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(bin2hex(random_bytes(16))) ?>">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Your Name</label>
              <input type="text" id="name" name="name" required placeholder="John Smith">
            </div>
            <div class="form-group">
              <label for="email">Your Email</label>
              <input type="email" id="email" name="email" required placeholder="you@example.com">
            </div>
          </div>
          <div class="form-group">
            <label for="service">Service Interested In</label>
            <select id="service" name="service">
              <option value="">Select a service…</option>
              <option>Branding & Brand Identity</option>
              <option>Search Engine Optimisation (SEO)</option>
              <option>Web Design & Development</option>
              <option>Graphic Design Subscriptions</option>
              <option>Digital Marketing</option>
              <option>Shopify Store Setup</option>
              <option>Digital Menu Boards</option>
              <option>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" required placeholder="Write here your message"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
$auCities = $db->query("SELECT city, slug FROM locations WHERE active=1 AND country='Australia' ORDER BY sort_order")->fetchAll();
$usCities = $db->query("SELECT city, slug FROM locations WHERE active=1 AND country='United States' ORDER BY sort_order")->fetchAll();
$ukCities = $db->query("SELECT city, slug FROM locations WHERE active=1 AND country='United Kingdom' ORDER BY sort_order")->fetchAll();
$nzCities = $db->query("SELECT city, slug FROM locations WHERE active=1 AND country='New Zealand' ORDER BY sort_order")->fetchAll();
?>
<!-- Where We Serve / Local SEO -->
<section class="section section-alt">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="section-label">Local SEO</span>
        <h2>Where We Serve</h2>
        <p style="color:#313131;margin-top:.75rem;margin-bottom:1.75rem">Proudly delivering digital agency services to clients across these countries.</p>
        <div class="locations-grid">
          <div class="location-item">
            <div class="location-item-head"><span class="location-flag">🇦🇺</span><span>Australia</span></div>
            <?php if ($auCities): ?>
            <ul class="location-city-list">
              <?php foreach ($auCities as $c): ?>
              <li><a href="/location.php?slug=<?= urlencode($c['slug']) ?>"><?= sanitize($c['city']) ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <div class="location-item">
            <div class="location-item-head"><span class="location-flag">🇬🇧</span><span>United Kingdom</span></div>
            <?php if ($ukCities): ?>
            <ul class="location-city-list">
              <?php foreach ($ukCities as $c): ?>
              <li><a href="/location.php?slug=<?= urlencode($c['slug']) ?>"><?= sanitize($c['city']) ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <div class="location-item">
            <div class="location-item-head"><span class="location-flag">🇺🇸</span><span>United States</span></div>
            <?php if ($usCities): ?>
            <ul class="location-city-list">
              <?php foreach ($usCities as $c): ?>
              <li><a href="/location.php?slug=<?= urlencode($c['slug']) ?>"><?= sanitize($c['city']) ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <div class="location-item">
            <div class="location-item-head"><span class="location-flag">🇳🇿</span><span>New Zealand</span></div>
            <?php if ($nzCities): ?>
            <ul class="location-city-list">
              <?php foreach ($nzCities as $c): ?>
              <li><a href="/location.php?slug=<?= urlencode($c['slug']) ?>"><?= sanitize($c['city']) ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <div class="location-item"><div class="location-item-head"><span class="location-flag">🇦🇪</span><span>United Arab Emirates (Dubai)</span></div></div>
          <div class="location-item"><div class="location-item-head"><span class="location-flag">🇱🇰</span><span>Sri Lanka</span></div></div>
        </div>
      </div>
      <div class="why-visual">
        <?php if (!empty($settings['locations_image'])): ?>
          <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($settings['locations_image']) ?>" alt="Where We Serve" class="why-visual-image" loading="lazy">
        <?php else: ?>
          <div class="why-visual-placeholder">Upload an image from<br>Admin → Settings</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Location Map -->
<section class="section" style="padding-top:0">
  <div class="container">
    <iframe
      src="https://www.google.com/maps?q=<?= urlencode($settings['address'] ?? 'Creative Elements, Boralesgamuwa, Sri Lanka') ?>&output=embed"
      width="100%" height="420" style="border:0;border-radius:12px;display:block"
      loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Our location"></iframe>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
