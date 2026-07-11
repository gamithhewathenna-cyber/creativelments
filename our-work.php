<?php
$pageTitle = 'Our Work';
require_once 'includes/header.php';
$projects = $db->query("SELECT * FROM projects WHERE active=1 ORDER BY sort_order")->fetchAll();
$categories = $db->query("SELECT DISTINCT category FROM projects WHERE active=1 ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
?>

<section class="section">
  <div class="container">
    <!-- Filter Tabs -->
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2.5rem;justify-content:flex-start">
      <button class="filter-btn active" data-filter="all">All</button>
      <?php foreach ($categories as $cat): ?>
      <button class="filter-btn" data-filter="<?= sanitize($cat) ?>"><?= sanitize($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="portfolio-grid" id="portfolioGrid">
      <?php foreach ($projects as $proj): ?>
      <div class="portfolio-item" data-category="<?= sanitize($proj['category']) ?>">
        <?php if ($proj['image']): ?>
          <img class="portfolio-img" src="<?= SITE_URL ?>/uploads/projects/<?= sanitize($proj['image']) ?>" alt="<?= sanitize($proj['title']) ?>">
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

<a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="wa-float" target="_blank">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
</a>

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
      const show = filter === 'all' || item.dataset.category === filter;
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
