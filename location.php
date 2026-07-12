<?php
require_once 'includes/config.php';
$db = getDB();
$slug = $_GET['slug'] ?? '';
$stmt = $db->prepare("SELECT * FROM locations WHERE slug=? AND active=1");
$stmt->execute([$slug]);
$loc = $stmt->fetch();

if (!$loc) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = '404';
} else {
    $pageTitle      = $loc['h1'] ?: ($loc['city'] . ' — ' . $loc['country']);
    $seoTitle       = $loc['seo_title'] ?? '';
    $seoDescription = $loc['meta_description'] ?? '';
    if (!empty($loc['image'])) {
        $ogImage = rtrim(SITE_URL, '/') . '/uploads/sections/' . $loc['image'];
    }
}

require_once 'includes/header.php';

$faqItems = [];
$otherLocations = [];
$relatedServices = [];

if ($loc) {
    echo renderBreadcrumbs([
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Locations', 'url' => '/contact.php'],
        ['label' => $loc['city'], 'url' => null],
    ]);

    // ProfessionalService schema scoped to this city (in addition to the sitewide Organization schema in header.php)
    $localSchema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'ProfessionalService',
        'name'       => SITE_NAME . ' — ' . $loc['city'],
        'url'        => $canonicalUrl,
        'areaServed' => ['@type' => 'City', 'name' => $loc['city'] . ', ' . $loc['country']],
        'address'    => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $loc['city'],
            'addressRegion'   => $loc['region'],
            'addressCountry' => $loc['country'],
        ],
        'parentOrganization' => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL],
    ];
    echo '<script type="application/ld+json">' . json_encode($localSchema, JSON_UNESCAPED_SLASHES) . '</script>';

    for ($i = 1; $i <= 5; $i++) {
        if (!empty($loc["faq{$i}_q"]) && !empty($loc["faq{$i}_a"])) {
            $faqItems[] = ['q' => $loc["faq{$i}_q"], 'a' => $loc["faq{$i}_a"]];
        }
    }
    if ($faqItems) {
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => array_map(function ($f) {
                return ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]];
            }, $faqItems),
        ];
        echo '<script type="application/ld+json">' . json_encode($faqSchema, JSON_UNESCAPED_SLASHES) . '</script>';
    }

    $otherStmt = $db->prepare("SELECT city, slug, country FROM locations WHERE active=1 AND id != ? ORDER BY sort_order LIMIT 5");
    $otherStmt->execute([$loc['id']]);
    $otherLocations = $otherStmt->fetchAll();

    $relatedServices = $db->query("SELECT title, slug FROM services WHERE active=1 AND slug IS NOT NULL AND slug != '' ORDER BY sort_order LIMIT 3")->fetchAll();
}
?>

<?php
$locIcons = [
    'section1' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
    'section2' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
    'section3' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
];
$pinIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
$faqIcon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 2-3 4"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
$waIcon  = '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>';
?>
<?php if ($loc): ?>
<section class="page-hero">
  <div class="container">
    <span class="section-label location-badge"><?= $pinIcon ?> <?= sanitize($loc['city']) ?>, <?= sanitize($loc['country']) ?></span>
    <h1><?= sanitize($loc['h1'] ?: ($loc['city'] . ' Digital Marketing & Web Design')) ?></h1>
    <?php if ($loc['intro']): ?><p><?= sanitize($loc['intro']) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width:1200px">
    <?php if ($loc['section1_heading']): ?>
    <div class="location-block">
      <div class="service-icon"><?= $locIcons['section1'] ?></div>
      <h2><?= sanitize($loc['section1_heading']) ?></h2>
      <div class="location-copy"><?= nl2br(sanitize($loc['section1_content'])) ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($loc['image'])): ?>
    <div style="margin:2.5rem 0">
      <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($loc['image']) ?>" alt="Digital marketing and web design services for businesses in <?= sanitize($loc['city']) ?>, <?= sanitize($loc['country']) ?>" style="width:100%;border-radius:16px;object-fit:cover;max-height:420px" loading="lazy">
    </div>
    <?php endif; ?>

    <?php if ($loc['section2_heading']): ?>
    <div class="location-block">
      <div class="service-icon"><?= $locIcons['section2'] ?></div>
      <h2><?= sanitize($loc['section2_heading']) ?></h2>
      <div class="location-copy"><?= nl2br(sanitize($loc['section2_content'])) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($loc['section3_heading']): ?>
    <div class="location-block">
      <div class="service-icon"><?= $locIcons['section3'] ?></div>
      <h2><?= sanitize($loc['section3_heading']) ?></h2>
      <div class="location-copy"><?= nl2br(sanitize($loc['section3_content'])) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($faqItems): ?>
    <div class="location-faqs">
      <h2>Frequently Asked Questions — <?= sanitize($loc['city']) ?></h2>
      <?php foreach ($faqItems as $f): ?>
      <div class="location-faq-item">
        <h3><span class="location-faq-icon"><?= $faqIcon ?></span><?= sanitize($f['q']) ?></h3>
        <p><?= sanitize($f['a']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($relatedServices): ?>
    <div class="location-links-row">
      <h3><?= $locIcons['section1'] ?> Related Services</h3>
      <div class="location-links-list">
        <?php foreach ($relatedServices as $rs): ?>
        <a href="/service.php?slug=<?= urlencode($rs['slug']) ?>" class="btn btn-outline btn-sm"><?= sanitize($rs['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($otherLocations): ?>
    <div class="location-links-row">
      <h3><?= $pinIcon ?> Other Cities We Serve</h3>
      <div class="location-links-list">
        <?php foreach ($otherLocations as $ol): ?>
        <a href="/location.php?slug=<?= urlencode($ol['slug']) ?>" class="btn btn-outline btn-sm"><?= sanitize($ol['city']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="cta-section">
  <div class="container">
    <h2><?= sanitize($loc['cta_heading'] ?: ('Ready to Grow Your ' . $loc['city'] . ' Business Online?')) ?></h2>
    <p><?= sanitize($loc['cta_text'] ?: "Get a free consultation and see exactly how we'd help your business dominate search results.") ?></p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.5rem">
      <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn" style="background:white;color:#804899;font-weight:700" target="_blank"><?= $waIcon ?> Chat on WhatsApp</a>
      <a href="/contact.php" class="btn btn-dark">Contact Us</a>
    </div>
  </div>
</section>
<?php else: ?>
<section class="section" style="text-align:center">
  <h2>Location page not found</h2>
  <a href="/contact.php" class="btn btn-dark" style="margin-top:1.5rem">← Back to Contact</a>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
