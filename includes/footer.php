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
