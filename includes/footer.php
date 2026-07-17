</main>

<!-- Footer -->
<footer class="footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="/" class="logo logo-light">
        <?php if (!empty($settings['footer_logo'])): ?>
          <img src="<?= SITE_URL ?>/uploads/branding/<?= sanitize($settings['footer_logo']) ?>" alt="<?= sanitize(SITE_NAME) ?>" class="logo-img">
        <?php else: ?>
          <span class="logo-mark">CE</span>
          <span class="logo-text">Creative<br><em>Elements</em></span>
        <?php endif; ?>
      </a>
      <p>A trusted digital agency serving businesses across Melbourne, Sydney, and Sri Lanka. Web design, SEO, branding, and digital marketing that drives real growth.</p>
      <div class="footer-social">
        <a href="<?= sanitize($settings['facebook'] ?? '#') ?>" target="_blank" rel="noopener" aria-label="Facebook">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="<?= sanitize($settings['instagram'] ?? '#') ?>" target="_blank" rel="noopener" aria-label="Instagram">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <?php if (!empty($settings['linkedin'])): ?>
        <a href="<?= sanitize($settings['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45z"/></svg>
        </a>
        <?php endif; ?>
        <?php if (!empty($settings['google_business'])): ?>
        <a href="<?= sanitize($settings['google_business']) ?>" target="_blank" rel="noopener" aria-label="Google Business Profile">
          <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/our-work.php">Our Work</a></li>
        <li><a href="/services.php">Services</a></li>
        <li><a href="/blog.php">Blog</a></li>
        <li><a href="/about.php">About Us</a></li>
        <li><a href="/contact.php">Contact</a></li>
      </ul>
    </div>
    <div class="footer-services">
      <h4>Services</h4>
      <ul>
        <?php
        $footerServices = $db->query("SELECT title, slug FROM services WHERE active=1 ORDER BY sort_order LIMIT 6")->fetchAll();
        foreach ($footerServices as $fs):
          $fsUrl = !empty($fs['slug']) ? '/service.php?slug=' . urlencode($fs['slug']) : '/services.php';
        ?>
        <li><a href="<?= $fsUrl ?>"><?= sanitize($fs['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="footer-contact">
      <h4>Get In Touch</h4>
      <p><?= sanitize($settings['address'] ?? '') ?></p>
      <p><a href="tel:<?= sanitize($settings['phone'] ?? '') ?>"><?= sanitize($settings['phone'] ?? '') ?></a></p>
      <p><a href="mailto:<?= sanitize($settings['email'] ?? '') ?>"><?= sanitize($settings['email'] ?? '') ?></a></p>
      <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn-whatsapp" target="_blank">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        Chat on WhatsApp
      </a>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p>Creative Elements &copy; <?= date('Y') ?> — All Rights Reserved</p>
      <p>Developed by Creative Elements</p>
    </div>
  </div>
</footer>

<!-- Floating WhatsApp -->
<a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="wa-float" target="_blank" aria-label="Chat on WhatsApp">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
  <span>Chat on WhatsApp</span>
</a>

<?php if ($currentPage !== 'contact'): ?>
<!-- Floating Contact button -->
<button type="button" class="contact-float" id="contactFloatBtn" aria-label="Book Appointment">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  <span>Book Appointment</span>
</button>

<!-- Contact Form Popup -->
<div class="contact-modal" id="contactModal">
  <button class="project-modal-close" id="contactModalClose" aria-label="Close">&times;</button>
  <div class="contact-modal-inner">
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
</div>
<?php endif; ?>

<!-- Marketing Chatbot Widget -->
<div class="chatbot-widget" id="chatbotWidget" data-phone="<?= sanitize($settings['phone'] ?? '') ?>">
  <button type="button" class="chatbot-toggle" id="chatbotToggle" aria-label="Open chat">
    <svg class="chatbot-icon-chat" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <svg class="chatbot-icon-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    <span class="chatbot-badge" id="chatbotBadge"></span>
  </button>

  <div class="chatbot-panel" id="chatbotPanel">
    <div class="chatbot-header">
      <div class="chatbot-header-avatar">CE</div>
      <div class="chatbot-header-info">
        <h4>Creative Elements</h4>
        <span><span class="chatbot-status-dot"></span>Typically replies instantly</span>
      </div>
    </div>
    <div class="chatbot-messages" id="chatbotMessages"></div>
    <form class="chatbot-input-row" id="chatbotForm">
      <input type="text" id="chatbotInput" placeholder="Type your message…" autocomplete="off">
      <button type="submit" aria-label="Send message">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
      </button>
    </form>
  </div>
</div>

<!-- Project Detail Popup -->
<div class="project-modal" id="projectModal">
  <button class="project-modal-close" id="projectModalClose" aria-label="Close">&times;</button>
  <div class="project-modal-inner">
    <h2 id="projectModalTitle"></h2>
    <p id="projectModalDesc"></p>
    <div class="project-modal-gallery" id="projectModalGallery"></div>
    <a href="#" id="projectModalLink" class="project-modal-link" target="_blank" rel="noopener" style="display:none">Visit Live Project ↗</a>

    <div class="project-modal-footer">
      <div class="project-modal-social">
        <a href="<?= sanitize($settings['facebook'] ?? '#') ?>" target="_blank" rel="noopener" aria-label="Facebook">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="<?= sanitize($settings['instagram'] ?? '#') ?>" target="_blank" rel="noopener" aria-label="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <?php if (!empty($settings['linkedin'])): ?>
        <a href="<?= sanitize($settings['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45z"/></svg>
        </a>
        <?php endif; ?>
        <?php if (!empty($settings['google_business'])): ?>
        <a href="<?= sanitize($settings['google_business']) ?>" target="_blank" rel="noopener" aria-label="Google Business Profile">
          <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
        </a>
        <?php endif; ?>
      </div>
      <a href="/contact.php" class="btn btn-primary">Start Your Project</a>
    </div>
  </div>
</div>

<script src="/assets/js/main.js?v=<?= @filemtime(__DIR__ . '/../assets/js/main.js') ?: time() ?>"></script>
</body>
</html>
