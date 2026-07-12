<?php
// ⚠️ ONE-TIME USE ONLY — delete this file from the server immediately after running it once.
// Seeds 5 New Zealand GEO landing pages (Auckland, Wellington, Christchurch, Hamilton, Tauranga).
// Safe to re-run: it skips any city slug that already exists, so it won't create duplicates.
require_once __DIR__ . '/../includes/config.php';
requireLogin();
requireAdmin();
$db = getDB();

$locations = [

[
'city' => 'Auckland', 'region' => 'Auckland Region', 'country' => 'New Zealand', 'slug' => 'auckland',
'seo_title' => 'Web Design & Digital Marketing Auckland | Creative Elements',
'meta_description' => "Creative Elements helps Auckland businesses grow online with expert web design, SEO, and branding. Trusted across New Zealand. Get a free consultation today.",
'focus_keyphrase' => 'web design Auckland',
'h1' => 'Web Design & Digital Marketing in Auckland, NZ',
'intro' => "Auckland is New Zealand's largest city and its main business, finance, and immigration gateway — home to roughly a third of the country's population and by far its most competitive digital market. Creative Elements helps Auckland businesses build websites and digital marketing campaigns that can genuinely compete for attention in New Zealand's busiest city.",

'section1_heading' => 'Web Design & Development for Auckland Businesses',
'section1_content' => "Auckland concentrates more competing businesses than anywhere else in New Zealand, which means a generic or slow-loading website gets lost quickly among far more established local competitors. We build fast, mobile-first websites specifically engineered around how Auckland customers search and what actually gets them to enquire or buy.\n\nWhether you're a professional services firm in the CBD, a hospitality or retail business across the North Shore, West Auckland, or the eastern suburbs, or an e-commerce brand shipping nationwide from an Auckland warehouse, we build to the same standard: clean modern design, load times under two seconds, and clear conversion paths.\n\nWe also build every Auckland project with technical SEO from day one, since ranking well in New Zealand's largest and most contested market takes more than a good-looking site — it takes one that's actually structured to be found.",

'section2_heading' => 'Why Auckland Businesses Choose Creative Elements',
'section2_content' => "Auckland agency pricing reflects the city's status as New Zealand's commercial capital, which can put quality design out of reach for smaller and growing businesses. We run a remote-first studio specifically to close that gap — the same calibre of strategy and design without a CBD office lease baked into your invoice.\n\nEvery Auckland client works directly with our senior team rather than a rotating cast of account managers, meaning faster turnarounds and a final result that actually matches what you asked for.\n\nWe're transparent about pricing and scope from the first conversation, which matters in a market as competitive and fast-moving as Auckland's.",

'section3_heading' => "Supporting Auckland's Finance, Trade & Retail Economy",
'section3_content' => "Auckland's finance and professional services sector, concentrated in the CBD and Viaduct, needs polished, credibility-first websites that build trust before a prospective client ever makes contact. We build with that first-impression pressure in mind.\n\nThe city's role as New Zealand's major import and export gateway, anchored by the Ports of Auckland, supports a large ecosystem of logistics, trade, and distribution businesses that need clear, professional websites to support partnerships and procurement.\n\nAnd Auckland's dense retail, hospitality, and e-commerce scene — spread across a sprawling, car-dependent city — depends heavily on strong local SEO and mobile-first design, since customers are typically searching from their phone and deciding quickly which nearby option to choose.",

'faq1_q' => 'How much does a website cost in Auckland?', 'faq1_a' => "Pricing depends on scope, but we keep it transparent from the first conversation. Because we work remotely without Auckland CBD overheads, our packages are typically more accessible than comparable local agencies while matching the same design quality.",
'faq2_q' => 'Do you work with businesses outside central Auckland?', 'faq2_a' => "Yes — a large share of our Auckland clients are based on the North Shore, in West Auckland, and across South Auckland. Since we work remotely, your specific area doesn't affect turnaround time or quality.",
'faq3_q' => 'How long does it take to build a website for an Auckland business?', 'faq3_a' => "Most projects launch within 3-6 weeks from kickoff, depending on scope. We'll confirm a firm timeline before any work begins.",
'faq4_q' => 'Can you help my Auckland business rank higher on Google?', 'faq4_a' => "Yes. Every website we build includes technical SEO foundations, and we also offer ongoing SEO campaigns targeted specifically at Auckland and New Zealand search terms.",
'faq5_q' => 'Do I need to be based in Auckland to work with you?', 'faq5_a' => "No. We work with Auckland businesses entirely remotely via video calls and email — the same process we use across New Zealand and internationally.",

'cta_heading' => 'Ready to Grow Your Auckland Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business stand out in New Zealand's biggest market.",
'sort_order' => 1,
],

[
'city' => 'Wellington', 'region' => 'Wellington Region', 'country' => 'New Zealand', 'slug' => 'wellington',
'seo_title' => 'Web Design & Digital Marketing Wellington | Creative Elements',
'meta_description' => "Creative Elements builds modern, high-converting websites for Wellington businesses across government, tech, and the creative sector. Web design & SEO for NZ's capital.",
'focus_keyphrase' => 'web design Wellington',
'h1' => 'Web Design & Digital Marketing in Wellington, NZ',
'intro' => "Wellington is New Zealand's capital — a compact, creative, and famously design-conscious city built around government, film and screen production, and a fast-growing tech scene. Creative Elements helps Wellington businesses build websites that meet the city's high visual and functional standards.",

'section1_heading' => 'Web Design Built for a Design-Literate Capital',
'section1_content' => "Wellington has a well-earned reputation for design and creative quality — driven partly by its screen and film industry heritage — and local businesses are held to that same visual standard. A dated or generic website stands out here for the wrong reasons.\n\nWe build clean, modern websites for Wellington businesses that meet that bar: considered design, fast performance, and clear conversion paths, whether you're a government-adjacent consultancy in the CBD, a hospitality venue on Cuba Street, or a screen and creative industries business supporting Wellington's production sector.\n\nWe never let design outrun function — every Wellington site is structured with SEO fundamentals from day one, so it performs as well as it looks.",

'section2_heading' => 'Why Wellington Businesses Choose Creative Elements',
'section2_content' => "Wellington's creative and agency scene is excellent but can be expensive, particularly for smaller businesses and consultancies without a large marketing budget. We built our studio to close that gap — the same design calibre, delivered remotely, without central Wellington office overheads.\n\nEvery Wellington client works directly with our senior design and development team, which matters in a city where creative nuance and attention to detail are genuinely expected, not optional extras.\n\nWe're also upfront about pricing and scope from the first conversation, reflecting the same straightforward, no-nonsense approach Wellington's business community tends to value.",

'section3_heading' => "Supporting Wellington's Government, Screen & Tech Sectors",
'section3_content' => "Wellington's large public sector and government-adjacent consultancy community needs polished, credibility-first websites that communicate clearly and professionally — built to reassure institutional stakeholders and withstand scrutiny.\n\nThe city's globally recognised screen and creative industries, anchored by companies like Weta Workshop and a wider ecosystem of production and post-production businesses, need portfolio-driven websites that showcase work beautifully while remaining genuinely structured for search visibility.\n\nAnd Wellington's growing tech and startup scene, benefiting from the city's talent pool and compact, connected business community, needs modern, fast websites that can compete for attention and investment on a leaner budget than larger markets.",

'faq1_q' => 'Does Wellington get a different design approach because of its creative reputation?', 'faq1_a' => "We tailor the visual direction to your specific brand and audience, but we do bring extra attention to design polish for Wellington clients, given the city's strong design-literate market and screen industry heritage.",
'faq2_q' => 'Do you build websites for screen and creative industries businesses?', 'faq2_a' => "Yes — we build portfolio-focused websites for production, post-production, and creative businesses, balancing strong visual presentation with genuine search performance.",
'faq3_q' => 'Can you help government-adjacent consultancies build credibility online?', 'faq3_a' => "Yes — we focus on polished, professional design for this sector, built to reassure institutional stakeholders evaluating a firm before making contact.",
'faq4_q' => 'Do you work with Wellington tech startups?', 'faq4_a' => "Yes — we build modern, lean websites designed to help startups compete for attention and investor interest against larger, better-funded competitors.",
'faq5_q' => 'How does your pricing compare to Wellington creative agencies?', 'faq5_a' => "Generally more accessible — operating remotely without central Wellington studio overheads lets us offer the same design calibre at a more reasonable price point.",

'cta_heading' => 'Ready to Build a Website That Matches Wellington\'s Standards?',
'cta_text' => "Get a free consultation and see exactly how we'd bring your brand to life for New Zealand's capital.",
'sort_order' => 2,
],

[
'city' => 'Christchurch', 'region' => 'Canterbury', 'country' => 'New Zealand', 'slug' => 'christchurch',
'seo_title' => 'Web Design & Digital Marketing Christchurch | Creative Elements',
'meta_description' => "Creative Elements builds modern websites for Christchurch businesses across construction, agritech, and professional services. Web design & SEO for Canterbury, NZ.",
'focus_keyphrase' => 'web design Christchurch',
'h1' => 'Web Design & Digital Marketing in Christchurch, NZ',
'intro' => "Christchurch is the South Island's largest city and one of New Zealand's most transformed urban centres — rebuilt with a modern new CBD following the 2011 earthquakes, and now home to a growing agritech and engineering innovation sector. Creative Elements helps Christchurch businesses build a digital presence that matches that momentum.",

'section1_heading' => 'Web Design for a Rebuilt, Fast-Moving City',
'section1_content' => "Christchurch's rebuilt CBD has brought a wave of new and relocated businesses into the city centre, alongside significant growth in construction, engineering, and agritech across greater Canterbury. That growth means more competition for local customers than the city has seen in over a decade.\n\nWe build fast, mobile-first websites for Christchurch businesses ready for that competition — clean, modern design for professional services and engineering firms in the new CBD, and lead-generation-focused sites for the construction and trades businesses driving the city's ongoing rebuild and expansion.\n\nWe also build with Christchurch's agritech and innovation sector in mind, since this is a market increasingly built around technical credibility as much as visual presentation.",

'section2_heading' => 'Why Christchurch Businesses Choose Creative Elements',
'section2_content' => "Christchurch businesses have told us the same thing many rebuilding cities experience: a lot of agencies over-promised during the reconstruction boom and under-delivered. We focus on straightforward, transparent project scoping and a finished website that actually reflects what was agreed.\n\nWe operate remote-first, which keeps pricing more accessible than agencies carrying new CBD office overheads, while still giving every Christchurch client direct access to senior designers and developers.\n\nWe're upfront about timelines and pricing from the first conversation — particularly valued in a city that has learned to be cautious about grand promises since 2011.",

'section3_heading' => "Supporting Christchurch's Construction, Agritech & Engineering Economy",
'section3_content' => "Christchurch's construction and property sector, still shaped by the scale of the post-earthquake rebuild, needs credible, fast-loading websites with strong local SEO — since so much new work is won through being visible to Cantabrians actively searching for rebuild-related services.\n\nThe city's growing agritech and engineering innovation sector, supported by Lincoln University and a strong agricultural research base, needs professional, technically credible websites that reassure corporate and research partners evaluating new technology or services.\n\nAnd Christchurch's recovering hospitality, retail, and tourism sector — increasingly centred around the new CBD's laneways and precincts — needs visually engaging, mobile-first websites to draw both locals and the steadily returning flow of South Island visitors.",

'faq1_q' => 'Do you build websites for construction and rebuild-related businesses?', 'faq1_a' => "Yes — this is one of our most common Christchurch project types. We focus on fast, credible websites with strong local SEO, since so much rebuild-related work is won through local search visibility.",
'faq2_q' => 'Can you help an agritech or engineering business communicate technical credibility online?', 'faq2_a' => "Yes — we build professional websites with clear capability and research credentials, designed to reassure corporate and institutional partners evaluating new technology.",
'faq3_q' => 'Is Christchurch\'s market different because of the rebuild?', 'faq3_a' => "In some ways, yes — there's more new and relocated competition in the CBD than a decade ago, and local businesses increasingly need a credible, modern website to stand out.",
'faq4_q' => 'Do you help with local SEO across greater Canterbury?', 'faq4_a' => "Yes — local SEO targeting Christchurch and the wider Canterbury region is a core part of what we offer, particularly for construction, trades, and professional services clients.",
'faq5_q' => 'How long does a typical Christchurch project take?', 'faq5_a' => "Most websites launch within 3-6 weeks of kickoff. We'll confirm a firm timeline before starting any work and keep you updated throughout.",

'cta_heading' => 'Ready to Grow Your Christchurch Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business stand out in the South Island's largest city.",
'sort_order' => 3,
],

[
'city' => 'Hamilton', 'region' => 'Waikato', 'country' => 'New Zealand', 'slug' => 'hamilton',
'seo_title' => 'Web Design & Digital Marketing Hamilton | Creative Elements',
'meta_description' => "Creative Elements builds affordable, professional websites for Hamilton businesses across agriculture, education, and local services. Web design & SEO for Waikato, NZ.",
'focus_keyphrase' => 'web design Hamilton',
'h1' => 'Web Design & Digital Marketing in Hamilton, NZ',
'intro' => "Hamilton sits at the heart of the Waikato — New Zealand's agricultural heartland — and has grown rapidly as an affordable, well-connected alternative to Auckland, just over an hour up the road. Creative Elements helps Hamilton businesses build websites that work as hard as the region's agribusiness and education sectors do.",

'section1_heading' => 'Web Design for a Growing Regional Hub',
'section1_content' => "Hamilton's steady population growth — driven partly by Aucklanders seeking more affordable living within commuting distance — has brought more competition for local business than the city has traditionally seen. A dated or slow website no longer cuts it in a market attracting new residents and new competitors alike.\n\nWe build fast, mobile-first websites for Hamilton businesses across agribusiness, trades, professional services, and retail, with clear conversion paths and strong local SEO so you're found by both long-standing Waikato customers and the steady flow of new arrivals.\n\nWe also build with the University of Waikato's presence in mind, since Hamilton's education sector supports a distinct audience of students, researchers, and academic-adjacent businesses with their own specific expectations.",

'section2_heading' => 'Why Hamilton Businesses Choose Creative Elements',
'section2_content' => "Hamilton businesses are often more price-sensitive than their Auckland counterparts, and rightly so — agency pricing calibrated for Auckland's market rarely makes sense for a regional city like Hamilton. We built our studio specifically to offer agency-quality design and development at a price built for Waikato's actual market conditions.\n\nEvery Hamilton client works directly with senior designers and developers, and we're transparent about pricing and timelines from the first conversation — valuable in a close-knit regional business community where reputation still travels fast.\n\nWe also understand the practical, no-nonsense culture common across Waikato's agricultural and trades sectors, and we build websites that reflect that: clear, functional, and genuinely useful rather than needlessly flashy.",

'section3_heading' => "Supporting Hamilton's Agriculture, Education & Trades Economy",
'section3_content' => "The Waikato's dairy and agribusiness sector, one of the most significant in New Zealand, needs professional websites that communicate scale and reliability to corporate and trade partners, alongside clear, accessible information for the wider rural community it serves.\n\nHamilton's education sector, anchored by the University of Waikato, supports a range of academic-adjacent and student-facing businesses that need clear, credible websites built around a distinctly different audience than the region's agricultural base.\n\nAnd Hamilton's growing trades and local services economy — expanding alongside the city's population growth — depends heavily on local SEO, since so many new residents are searching for local providers for the first time after relocating from Auckland or elsewhere.",

'faq1_q' => 'Is Hamilton a more affordable market for web design than Auckland?', 'faq1_a' => "Generally, yes — our pricing is built around what makes sense for Hamilton and the wider Waikato market, rather than Auckland-calibrated agency rates.",
'faq2_q' => 'Do you build websites for agribusiness and dairy-related companies?', 'faq2_a' => "Yes — this is a significant part of our Hamilton and Waikato client base. We focus on professional, credible websites that communicate scale and reliability to corporate and trade partners.",
'faq3_q' => 'Can you help a Hamilton trades business get found online?', 'faq3_a' => "Yes — local SEO is a core part of what we offer for Hamilton trades and service businesses, especially important given the city's fast-growing population of new residents searching for local providers.",
'faq4_q' => 'Do you work with education or university-adjacent businesses in Hamilton?', 'faq4_a' => "Yes — we build clear, credible websites for businesses serving the University of Waikato community, tailored to that specific audience rather than a generic template.",
'faq5_q' => 'How long does a typical Hamilton project take?', 'faq5_a' => "Most websites launch within 3-6 weeks of kickoff. We'll confirm a firm timeline before starting any work.",

'cta_heading' => 'Ready to Grow Your Hamilton Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business reach more customers across the Waikato.",
'sort_order' => 4,
],

[
'city' => 'Tauranga', 'region' => 'Bay of Plenty', 'country' => 'New Zealand', 'slug' => 'tauranga',
'seo_title' => 'Web Design & Digital Marketing Tauranga | Creative Elements',
'meta_description' => "Creative Elements builds modern websites for Tauranga businesses across horticulture, logistics, and tourism. Web design & SEO for the Bay of Plenty, NZ.",
'focus_keyphrase' => 'web design Tauranga',
'h1' => 'Web Design & Digital Marketing in Tauranga, NZ',
'intro' => "Tauranga is home to New Zealand's busiest port by trade volume and one of the country's fastest-growing cities, driven by horticulture exports, logistics, and a steady wave of lifestyle migration to the Bay of Plenty. Creative Elements helps Tauranga businesses build a digital presence that keeps pace with that growth.",

'section1_heading' => 'Web Design for One of New Zealand\'s Fastest-Growing Cities',
'section1_content' => "Tauranga's rapid population growth — driven by both young families and retirees relocating for lifestyle and climate — has brought a wave of new local competition across retail, trades, and professional services. A website that performed fine a few years ago may no longer hold up against newer, better-resourced competitors moving into the region.\n\nWe build fast, mobile-first websites for Tauranga businesses ready for that growth: professional, credibility-first sites for logistics and export-related businesses supporting the Port of Tauranga, and lead-generation-focused sites for the trades and local service businesses serving the city's rapidly expanding suburbs.\n\nWe also build with Tauranga's tourism and lifestyle economy in mind, since the wider Bay of Plenty — including Mount Maunganui — draws a steady flow of domestic and international visitors alongside its resident population.",

'section2_heading' => 'Why Tauranga Businesses Choose Creative Elements',
'section2_content' => "As Tauranga has grown, so has competition among local agencies and freelancers, with pricing and quality varying widely. We offer a consistent alternative: senior-level design and development, delivered remotely, with transparent pricing from the very first conversation.\n\nEvery Tauranga client works directly with our senior team rather than a rotating cast of account managers, which matters in a city where many businesses are still relatively new to serious digital investment and want a partner they can trust to explain the process clearly.\n\nWe also understand Tauranga's economy spans very different audiences — from horticultural exporters to lifestyle retailers — and we tailor our approach to which of those audiences your business actually serves.",

'section3_heading' => "Supporting Tauranga's Horticulture, Logistics & Tourism Economy",
'section3_content' => "The Bay of Plenty's horticulture sector, particularly kiwifruit, is one of New Zealand's most significant export industries, and businesses across this supply chain need professional websites that communicate scale and reliability to corporate buyers and international partners.\n\nTauranga's position as home to New Zealand's busiest port supports a substantial logistics, freight, and distribution ecosystem, and we build websites for this sector that make service capabilities and scale immediately clear to prospective partners.\n\nAnd the wider Tauranga and Mount Maunganui area's tourism and hospitality scene needs visually engaging, mobile-first websites with strong booking flows, since visitors are often comparing options on their phone while already in the region.",

'faq1_q' => 'Do you build websites for horticulture and export businesses?', 'faq1_a' => "Yes — this is a significant part of our Tauranga and Bay of Plenty client base, particularly kiwifruit and horticulture-adjacent businesses. We focus on professional websites that communicate scale and reliability to corporate and international buyers.",
'faq2_q' => 'Can you help a logistics business near the Port of Tauranga?', 'faq2_a' => "Yes — we build websites that make service areas, capacity, and capabilities clear to prospective partners, tailored to the freight and distribution businesses supporting New Zealand's busiest port.",
'faq3_q' => 'Do you work with tourism and hospitality businesses in Mount Maunganui?', 'faq3_a' => "Yes — we build visually engaging, mobile-first websites with strong booking flows for hospitality and tourism businesses across the wider Tauranga and Mount Maunganui area.",
'faq4_q' => 'Is Tauranga\'s market becoming more competitive?', 'faq4_a' => "Yes — rapid population growth has brought more local competition across retail, trades, and professional services. A modern, well-optimised website matters more than it used to here.",
'faq5_q' => 'How long does a typical Tauranga project take?', 'faq5_a' => "Most websites launch within 3-6 weeks of kickoff. We'll confirm a firm timeline before starting any work and keep you updated throughout.",

'cta_heading' => 'Ready to Grow Your Tauranga Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business keep pace with one of New Zealand's fastest-growing cities.",
'sort_order' => 5,
],

];

$inserted = 0;
$skipped  = 0;

foreach ($locations as $loc) {
    $exists = $db->prepare("SELECT id FROM locations WHERE slug = ?");
    $exists->execute([$loc['slug']]);
    if ($exists->fetch()) {
        $skipped++;
        continue;
    }

    $sql = "INSERT INTO locations (city, region, country, slug, seo_title, meta_description, focus_keyphrase, h1, intro,
            section1_heading, section1_content, section2_heading, section2_content, section3_heading, section3_content,
            faq1_q, faq1_a, faq2_q, faq2_a, faq3_q, faq3_a, faq4_q, faq4_a, faq5_q, faq5_a,
            cta_heading, cta_text, sort_order)
            VALUES (:city,:region,:country,:slug,:seo_title,:meta_description,:focus_keyphrase,:h1,:intro,
            :section1_heading,:section1_content,:section2_heading,:section2_content,:section3_heading,:section3_content,
            :faq1_q,:faq1_a,:faq2_q,:faq2_a,:faq3_q,:faq3_a,:faq4_q,:faq4_a,:faq5_q,:faq5_a,
            :cta_heading,:cta_text,:sort_order)";
    $db->prepare($sql)->execute($loc);
    $inserted++;
}

regenerateSitemap($db);

echo "<h2>New Zealand location seeding complete</h2>";
echo "<p>Inserted: <strong>$inserted</strong> &nbsp; Skipped (already existed): <strong>$skipped</strong></p>";
echo "<p><a href='/admin/locations.php'>Go to Admin → GEO Locations</a></p>";
echo "<p style='color:red;font-weight:bold'>Delete this file (admin/seed-locations-nz.php) from the server right now.</p>";
