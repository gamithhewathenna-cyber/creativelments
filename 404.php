<?php
header('HTTP/1.1 404 Not Found');
$pageTitle = 'Page Not Found';
require_once 'includes/header.php';
?>

<section class="section" style="text-align:center;padding-top:6rem;padding-bottom:6rem">
  <div class="container">
    <span class="section-label">404</span>
    <h1>Page Not Found</h1>
    <p style="margin-top:1rem;color:#313131">The page you're looking for doesn't exist or may have been moved.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:2rem">
      <a href="/" class="btn btn-primary">Back to Home</a>
      <a href="/our-work.php" class="btn btn-outline">View Our Work</a>
      <a href="/contact.php" class="btn btn-outline">Contact Us</a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
