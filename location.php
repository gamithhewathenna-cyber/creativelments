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

<?php if ($loc): ?>
<section class="page-hero">
  <div class="container">
    <span class="section-label"><?= sanitize($loc['city']) ?>, <?= sanitize($loc['country']) ?></span>
    <h1><?= sanitize($loc['h1'] ?: ($loc['city'] . ' Digital Marketing & Web Design')) ?></h1>
    <?php if ($loc['intro']): ?><p><?= sanitize($loc['intro']) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width:860px">
    <?php if ($loc['section1_heading']): ?>
    <div style="margin-bottom:2.5rem">
      <h2><?= sanitize($loc['section1_heading']) ?></h2>
      <div style="line-height:1.85;font-size:.97rem;color:#313131;margin-top:1rem"><?= nl2br(sanitize($loc['section1_content'])) ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($loc['image'])): ?>
    <div style="margin:2.5rem 0">
      <img src="<?= SITE_URL ?>/uploads/sections/<?= sanitize($loc['image']) ?>" alt="Digital marketing and web design services for businesses in <?= sanitize($loc['city']) ?>, <?= sanitize($loc['country']) ?>" style="width:100%;border-radius:16px;object-fit:cover;max-height:420px" loading="lazy">
    </div>
    <?php endif; ?>

    <?php if ($loc['section2_heading']): ?>
    <div style="margin-bottom:2.5rem">
      <h2><?= sanitize($loc['section2_heading']) ?></h2>
      <div style="line-height:1.85;font-size:.97rem;color:#313131;margin-top:1rem"><?= nl2br(sanitize($loc['section2_content'])) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($loc['section3_heading']): ?>
    <div style="margin-bottom:2.5rem">
      <h2><?= sanitize($loc['section3_heading']) ?></h2>
      <div style="line-height:1.85;font-size:.97rem;color:#313131;margin-top:1rem"><?= nl2br(sanitize($loc['section3_content'])) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($faqItems): ?>
    <div style="margin-top:3rem">
      <h2>Frequently Asked Questions — <?= sanitize($loc['city']) ?></h2>
      <?php foreach ($faqItems as $f): ?>
      <div style="margin-top:1.5rem">
        <h3 style="font-size:1.05rem;margin-bottom:.5rem;color:var(--navy)"><?= sanitize($f['q']) ?></h3>
        <p style="color:#313131;font-size:.92rem;line-height:1.75"><?= sanitize($f['a']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($relatedServices): ?>
    <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid #E2E8F0">
      <h3 style="font-size:1rem;margin-bottom:1rem">Related Services</h3>
      <div style="display:flex;gap:.75rem;flex-wrap:wrap">
        <?php foreach ($relatedServices as $rs): ?>
        <a href="/service.php?slug=<?= urlencode($rs['slug']) ?>" class="btn btn-outline btn-sm"><?= sanitize($rs['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($otherLocations): ?>
    <div style="margin-top:2rem">
      <h3 style="font-size:1rem;margin-bottom:1rem">Other Cities We Serve</h3>
      <div style="display:flex;gap:.75rem;flex-wrap:wrap">
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
      <a href="https://wa.me/<?= sanitize($settings['whatsapp'] ?? '94777130597') ?>" class="btn" style="background:white;color:#804899;font-weight:700" target="_blank">Chat on WhatsApp</a>
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
