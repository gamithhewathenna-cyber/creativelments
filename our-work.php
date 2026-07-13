<?php
$pageTitle = 'Our Work';
require_once 'includes/header.php';
$projects = $db->query("SELECT * FROM projects WHERE active=1 ORDER BY RAND()")->fetchAll();

// Projects can belong to multiple categories (comma-separated) — split and de-duplicate
// them here, but keep the order defined in Admin → Projects → Project Categories.
$allCategoryNames = $db->query("SELECT name FROM project_categories ORDER BY sort_order")->fetchAll(PDO::FETCH_COLUMN);
$usedCategoryNames = [];
foreach ($projects as $p) {
    foreach (array_filter(array_map('trim', explode(',', $p['category']))) as $c) {
        $usedCategoryNames[$c] = true;
    }
}
$categories = array_values(array_filter($allCategoryNames, fn($c) => isset($usedCategoryNames[$c])));
echo renderBreadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Our Work', 'url' => null],
]);
?>

<section class="section no-reveal">
  <div class="container">
    <h1 class="sr-only">Our Work</h1>
    <!-- Filter Tabs -->
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2.5rem;justify-content:flex-start">
      <button class="filter-btn active" data-filter="all">All</button>
      <?php foreach ($categories as $cat): ?>
      <button class="filter-btn" data-filter="<?= sanitize($cat) ?>"><?= sanitize($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="portfolio-grid" id="portfolioGrid">
      <?php foreach ($projects as $proj): ?>
      <div class="portfolio-item project-trigger" data-category="<?= sanitize($proj['category']) ?>"
           data-title="<?= sanitize($proj['title']) ?>"
           data-desc="<?= sanitize($proj['description'] ?? '') ?>"
           data-img1="<?= $proj['image'] ? SITE_URL . '/uploads/projects/' . sanitize($proj['image']) : '' ?>"
           data-img2="<?= !empty($proj['image2']) ? SITE_URL . '/uploads/projects/' . sanitize($proj['image2']) : '' ?>"
           data-img3="<?= !empty($proj['image3']) ? SITE_URL . '/uploads/projects/' . sanitize($proj['image3']) : '' ?>"
           data-link="<?= sanitize($proj['link'] ?? '') ?>">
        <?php if ($proj['image']): ?>
          <img class="portfolio-img" src="<?= SITE_URL ?>/uploads/projects/<?= sanitize($proj['image']) ?>" alt="<?= sanitize($proj['title']) ?>" loading="lazy">
        <?php else: ?>
          <div class="portfolio-placeholder"><?= sanitize($proj['title']) ?></div>
        <?php endif; ?>
        <div class="portfolio-overlay">
          <div class="portfolio-meta">
            <h4><?= sanitize($proj['title']) ?></h4>
            <span><?= sanitize(str_replace(',', ', ', $proj['category'])) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($projects)): ?>
    <p style="text-align:center;color:#313131;padding:4rem 0">No projects yet. Add them from the admin panel.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-section cta-section-light">
  <div class="container">
    <h2>Like What You See?</h2>
    <p>We'd be interested in learning more about your project.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn btn-primary" target="_blank">Start a Project</a>
      <a href="/contact.php" class="btn btn-outline">Contact Us</a>
    </div>
  </div>
</section>

<style>
.filter-btn {
  padding: .55rem 1.25rem;
  border: 1.5px solid #E2E8F0;
  border-radius: 100px;
  background: white;
  cursor: pointer;
  font-size: .85rem;
  font-weight: 600;
  color: #0A0F1E;
  transition: all .2s;
}
.filter-btn:hover, .filter-btn.active {
  background: #804899;
  border-color: #804899;
  color: white;
}
</style>
<script>
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filter = btn.dataset.filter;
    const items = Array.from(document.querySelectorAll('.portfolio-item'));

    // FIRST — record where every currently-visible tile sits on screen
    const firstRects = new Map();
    items.forEach(item => {
      if (item.style.display !== 'none') firstRects.set(item, item.getBoundingClientRect());
    });

    // Apply the filter — this is what actually changes the grid layout
    items.forEach(item => {
      const itemCategories = item.dataset.category.split(',').map(c => c.trim());
      const show = filter === 'all' || itemCategories.includes(filter);
      item.style.display = show ? '' : 'none';
    });

    // LAST + INVERT + PLAY — animate each visible tile from its old spot to its new one
    items.forEach(item => {
      if (item.style.display === 'none') return;
      const last = item.getBoundingClientRect();
      const first = firstRects.get(item);

      item.style.transition = 'none';
      if (first) {
        const dx = first.left - last.left;
        const dy = first.top - last.top;
        item.style.transform = `translate(${dx}px, ${dy}px)`;
        item.style.opacity = '1';
      } else {
        // tile is newly appearing — start it slightly scaled down and faded out
        item.style.transform = 'scale(.9)';
        item.style.opacity = '0';
      }

      void item.offsetWidth; // force reflow so the browser registers the starting position
      item.style.transition = 'transform .5s cubic-bezier(.4,0,.2,1), opacity .4s ease';
      item.style.transform = '';
      item.style.opacity = '';
    });
  });
});
</script>

<?php require_once 'includes/footer.php'; ?>
