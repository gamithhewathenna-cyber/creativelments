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
    <div class="contact-grid">
      <!-- Form -->
      <div>
        <div id="formSuccess" class="form-success" style="display:none">✅ Thank you! We'll be in touch within 24 hours.</div>
        <div id="formError" class="form-error" style="display:none"></div>
        <form class="contact-form" id="contactForm" novalidate>
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(bin2hex(random_bytes(16))) ?>">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Full Name *</label>
              <input type="text" id="name" name="name" required placeholder="Your name">
            </div>
            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" required placeholder="you@example.com">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="+61 4xx xxx xxx">
            </div>
            <div class="form-group">
              <label for="service">Service Interested In</label>
              <select id="service" name="service">
                <option value="">Select a service…</option>
                <option>Web Design & Development</option>
                <option>Graphic Design Subscriptions</option>
                <option>Digital Marketing & SEO</option>
                <option>Shopify Store Setup</option>
                <option>Social Media Management</option>
                <option>Digital Menu Boards</option>
                <option>Other</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="message">Your Message *</label>
            <textarea id="message" name="message" required placeholder="Tell us about your project…"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:1rem">Send Message</button>
        </form>
      </div>

      <!-- Contact Info -->
      <div class="contact-info">
        <div class="contact-card">
          <h3>Contact Information</h3>
          <div class="contact-item">
            <div class="contact-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
              <strong>Address</strong>
              <a href="#"><?= sanitize($settings['address'] ?? '') ?></a>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l.81-.81a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div>
              <strong>Phone</strong>
              <a href="tel:<?= sanitize($settings['phone'] ?? '') ?>"><?= sanitize($settings['phone'] ?? '') ?></a>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div>
              <strong>Email</strong>
              <a href="mailto:<?= sanitize($settings['email'] ?? '') ?>"><?= sanitize($settings['email'] ?? '') ?></a>
            </div>
          </div>
        </div>

        <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn-whatsapp" target="_blank" style="display:inline-flex">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
          Chat on WhatsApp — Quick Response
        </a>

        <div style="background:#F7F8FC;border-radius:12px;padding:1.5rem">
          <h4 style="font-size:1rem;margin-bottom:.75rem">Business Hours</h4>
          <p style="font-size:.88rem;color:#313131;margin-bottom:.4rem">Monday – Friday: 9 AM – 6 PM (SLST)</p>
          <p style="font-size:.88rem;color:#313131;margin-bottom:.4rem">Saturday: 9 AM – 2 PM</p>
          <p style="font-size:.88rem;color:#313131">Sunday: Closed</p>
        </div>
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
