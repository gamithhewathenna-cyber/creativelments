<?php
// ⚠️ ONE-TIME USE ONLY — delete this file from the server immediately after running it once.
// Seeds 5 UK GEO landing pages (London, Manchester, Birmingham, Edinburgh, Glasgow).
// Safe to re-run: it skips any city slug that already exists, so it won't create duplicates.
require_once __DIR__ . '/../includes/config.php';
requireLogin();
requireAdmin();
$db = getDB();

$locations = [

[
'city' => 'London', 'region' => 'England', 'country' => 'United Kingdom', 'slug' => 'london',
'seo_title' => 'Web Design & Digital Marketing London | Creative Elements',
'meta_description' => "Creative Elements helps London businesses compete online with expert web design, SEO, and branding. Trusted across the UK. Get a free consultation today.",
'focus_keyphrase' => 'web design London',
'h1' => 'Web Design & Digital Marketing in London, UK',
'intro' => "London is one of the most competitive digital markets in the world — a global hub for finance, media, and professional services where thousands of businesses compete for the same customers and the same Google rankings. Creative Elements helps London businesses build websites and digital marketing campaigns that can genuinely compete in that environment, without the Central London agency price tag.",

'section1_heading' => 'Web Design & Development for London Businesses',
'section1_content' => "Standing out in London means competing with an enormous concentration of businesses, many with agency budgets far larger than a growing company can justify. A slow or generic website doesn't just underperform here — it gets lost entirely among far better-resourced competitors.\n\nWe build fast, mobile-first websites for London businesses engineered around two things: how your customers actually search, and what makes them convert instead of clicking back to a competitor. That means clean, modern design, load times under two seconds, and calls-to-action placed exactly where visitors are ready to act.\n\nWhether you need a corporate site for a professional services firm in the City or Canary Wharf, a visually driven site for a hospitality brand in Shoreditch or Notting Hill, or a full e-commerce build for a retail brand shipping across the UK, we build it to the same global design standard — backed by technical SEO from day one so London customers can actually find you.",

'section2_heading' => 'Why London Businesses Choose Creative Elements',
'section2_content' => "London agency rates are among the highest in the world, largely driven by Central London office overheads that get passed straight onto clients. We run a remote-first studio, which means you get the same — or better — calibre of design and strategy without paying for someone else's office lease in Zone 1.\n\nEvery London client works directly with our senior team, not a junior account manager relaying messages back and forth. That means faster turnarounds, fewer miscommunications, and a website that actually reflects what you asked for.\n\nWe're also transparent about pricing from the first conversation — no vague estimates. You'll know exactly what you're paying for and why, whether that's a new website, an SEO campaign, or ongoing digital marketing support for your London business.",

'section3_heading' => "Supporting London's Finance, Media & Retail Economy",
'section3_content' => "London's financial and professional services sector, concentrated in the City and Canary Wharf, needs websites that project instant credibility — often the deciding factor before a prospective client ever picks up the phone. We build with that first-impression pressure in mind: polished, fast, and unambiguous about what your firm does and why it can be trusted.\n\nLondon's massive media and creative industries, based heavily around Soho and Shoreditch, need something different — visually bold, brand-forward websites that stand out in an oversaturated content environment while still converting on enquiry or booking flows underneath the design.\n\nAnd London's independent retail and hospitality scene, spread across dozens of distinct boroughs and neighbourhoods, needs strong local SEO and mobile-first design, since most customers are searching from their phone a few streets away and deciding in seconds where to go.",

'faq1_q' => 'Can a website really make a difference in a market as saturated as London?', 'faq1_a' => "Yes — in fact it matters more here, not less. With so many competitors, a fast, well-structured, SEO-optimised site is often the deciding factor in whether a customer chooses you over the business one search result down. We build specifically to win that margin.",
'faq2_q' => 'Do you work with businesses across all London boroughs?', 'faq2_a' => "Yes — from Central London through to Greater London boroughs like Croydon, Ealing, and Barnet. Since we work remotely, your specific borough or postcode doesn't affect turnaround time or access to our team.",
'faq3_q' => 'How do you keep pricing competitive in such an expensive market?', 'faq3_a' => "By operating remote-first, without a physical London office to fund through client invoices. That lets us offer agency-calibre design and strategy at a more accessible price point than comparable Central London agencies.",
'faq4_q' => 'Can you help my London business rank against much bigger competitors?', 'faq4_a' => "We build strong technical SEO foundations into every site and can run targeted local and national SEO campaigns to help you compete for the specific searches your customers use — even against larger, better-funded competitors.",
'faq5_q' => 'How fast can you turn around a project for a London business?', 'faq5_a' => "Most websites launch within 3-6 weeks depending on scope. We understand London businesses often move fast, and we can discuss expedited timelines for time-sensitive launches or campaigns.",

'cta_heading' => 'Ready to Compete in One of the World\'s Toughest Markets?',
'cta_text' => "Get a free consultation and see exactly how we'd help your London business stand out and convert.",
'sort_order' => 1,
],

[
'city' => 'Manchester', 'region' => 'England', 'country' => 'United Kingdom', 'slug' => 'manchester',
'seo_title' => 'Web Design & Digital Marketing Manchester | Creative Elements',
'meta_description' => "Creative Elements builds fast, modern websites for Manchester businesses across media, tech, and retail. Web design, SEO & branding for Greater Manchester.",
'focus_keyphrase' => 'web design Manchester',
'h1' => 'Web Design & Digital Marketing in Manchester, UK',
'intro' => "Manchester has become the UK's second major hub for media, tech, and digital business — driven by MediaCityUK, a growing startup scene, and a wave of companies relocating from London to cut costs without sacrificing talent. Creative Elements helps Manchester businesses build a digital presence that keeps pace with that growth.",

'section1_heading' => 'Web Design & Development for Manchester Businesses',
'section1_content' => "As more digital and media businesses set up in Greater Manchester, local competition for customers and search visibility has grown sharply — a website that stood out five years ago may not hold up today. We build modern, fast, mobile-first websites for Manchester businesses designed to meet that raised bar.\n\nWhether you're a media or production company near MediaCityUK and Salford Quays, a creative or independent retail business in the Northern Quarter, or a professional services firm in the city centre, we build sites engineered around clear conversion paths and genuine search visibility, not just visual polish.\n\nWe also build with growth in mind, since many Manchester businesses are actively scaling — a site that can expand with new services or locations without a costly rebuild a year later.",

'section2_heading' => 'Why Manchester Businesses Choose Creative Elements',
'section2_content' => "As Manchester's profile has grown, so has the cost of comparable local agency work — many Manchester businesses now face pricing closer to London rates than the affordable alternative the city used to represent. We built our studio to close that gap: the same design and development calibre, delivered remotely, without the overhead driving up local agency quotes.\n\nEvery Manchester client works directly with senior designers and developers, and we're upfront about pricing and scope from the very first conversation — particularly valuable in a market attracting more national and international competition every year.\n\nWe also understand Manchester's business culture blends northern practicality with genuine creative ambition, and we build websites that reflect both: substance first, without sacrificing visual quality.",

'section3_heading' => "Supporting Manchester's Media, Tech & Retail Economy",
'section3_content' => "Manchester's media and broadcasting sector, anchored by MediaCityUK, needs professional, portfolio-driven websites that hold up next to major broadcaster and production company sites — polished enough to build credibility, structured enough to actually rank.\n\nThe city's fast-growing tech and startup scene, often described as the UK's leading digital hub outside London, needs lean, modern websites that can compete for talent and investor attention on a leaner budget than London-based rivals.\n\nAnd Manchester's strong retail and e-commerce base — from the city centre's Northern Quarter independents to larger Trafford-based operations — depends on conversion-focused, mobile-first websites and strong local SEO to compete both online and against Manchester's dense in-person retail scene.",

'faq1_q' => 'Is Manchester still more affordable than London for web design?', 'faq1_a' => "It varies — Manchester agency pricing has risen as more businesses relocate here, but our remote-first model still lets us offer more accessible pricing than most comparable agencies in either city, without compromising on quality.",
'faq2_q' => 'Do you work with media and production companies near MediaCityUK?', 'faq2_a' => "Yes — we build portfolio-focused, credibility-first websites for media and production businesses, balancing visual impact with genuine search performance.",
'faq3_q' => 'Can you help a Manchester tech startup compete for investor attention?', 'faq3_a' => "Yes. We build modern, fast websites specifically designed to help startups present professionally and compete for attention against better-funded rivals, often on a leaner timeline and budget.",
'faq4_q' => 'Do you help with local SEO across Greater Manchester?', 'faq4_a' => "Yes — local SEO targeting specific Greater Manchester areas, from the city centre to Salford, Stockport, and beyond, is a core part of what we offer for retail and service businesses.",
'faq5_q' => 'How long does a typical Manchester project take?', 'faq5_a' => "Most websites launch within 3-6 weeks of kickoff. We'll confirm a firm timeline before starting any work and keep you updated throughout.",

'cta_heading' => 'Ready to Grow Your Manchester Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help you compete in the UK's fastest-growing digital hub outside London.",
'sort_order' => 2,
],

[
'city' => 'Birmingham', 'region' => 'England', 'country' => 'United Kingdom', 'slug' => 'birmingham',
'seo_title' => 'Web Design & Digital Marketing Birmingham | Creative Elements',
'meta_description' => "Creative Elements builds professional websites for Birmingham businesses across manufacturing, logistics, and professional services. Web design & SEO for the West Midlands.",
'focus_keyphrase' => 'web design Birmingham',
'h1' => 'Web Design & Digital Marketing in Birmingham, UK',
'intro' => "Birmingham is the UK's second city — a major manufacturing and logistics hub sitting at the literal centre of the country's motorway network, now going through a significant wave of investment following the 2022 Commonwealth Games and the arrival of HS2. Creative Elements helps Birmingham businesses build a digital presence ready for that growth.",

'section1_heading' => 'Web Design & Development for Birmingham Businesses',
'section1_content' => "Birmingham's central location has always made it a natural base for manufacturing, logistics, and distribution businesses serving the whole of the UK — but that same reach means competing for customers well beyond the city itself. A slow or dated website doesn't just cost local business; it costs contracts from across the country.\n\nWe build fast, mobile-first websites for Birmingham businesses that project scale and reliability, whether you're a manufacturing or automotive supply chain business, a logistics operator, or a professional services firm serving clients regionally or nationally.\n\nWe also build with Birmingham's diverse, multicultural business community in mind — clear, accessible design that communicates effectively across a genuinely varied customer base, backed by SEO fundamentals from day one.",

'section2_heading' => 'Why Birmingham Businesses Choose Creative Elements',
'section2_content' => "Birmingham businesses often tell us the same thing: they've worked with agencies that delivered a website that looked fine but never actually generated leads. We build specifically to close that gap — modern, professional design that's still fundamentally structured around conversion and search visibility.\n\nWe operate remote-first, keeping our pricing more accessible than many agencies carrying city-centre overhead, while still giving every Birmingham client direct access to senior designers and developers rather than a rotating account management team.\n\nWe're also straightforward about timelines and pricing from the first conversation — particularly valuable as Birmingham's business landscape becomes more competitive amid the current wave of investment and relocation into the city.",

'section3_heading' => "Supporting Birmingham's Manufacturing, Logistics & Professional Services Economy",
'section3_content' => "Birmingham's manufacturing and automotive supply chain sector — historically one of the largest in the country — often relies on referrals and long-standing trade relationships, but increasingly needs a credible website to support due diligence from new corporate partners doing research before ever placing a call.\n\nThe city's position at the centre of the UK's motorway network has made it a natural logistics and distribution hub, and we build websites for this sector that make service areas, capacity, and capabilities immediately clear to prospective partners.\n\nBirmingham's growing financial and professional services sector — bolstered by major relocations such as HSBC UK's headquarters — needs polished, credibility-first websites that reflect the scale and seriousness of an increasingly significant regional financial centre.",

'faq1_q' => 'Do you build websites for manufacturing and automotive supply chain companies?', 'faq1_a' => "Yes — this is one of our most common project types for Birmingham clients. We focus on credibility-driven websites with clear capability statements and case studies that support long B2B sales and procurement cycles.",
'faq2_q' => 'Can you help a Birmingham logistics business communicate its service areas clearly?', 'faq2_a' => "Yes — clear, easy-to-navigate service area and capability information is central to how we build websites for logistics and distribution businesses based in and around Birmingham.",
'faq3_q' => 'Is Birmingham\'s market becoming more competitive for web design?', 'faq3_a' => "Yes, noticeably — the investment following the Commonwealth Games and HS2 has brought more businesses and competition into the city. We help clients build a stronger digital foundation to stay ahead of that shift.",
'faq4_q' => 'Do you help with local SEO across the West Midlands?', 'faq4_a' => "Yes — local SEO targeting Birmingham and the wider West Midlands, including areas like Solihull, Wolverhampton, and Coventry, is a core part of what we offer.",
'faq5_q' => 'How do you price projects for Birmingham businesses?', 'faq5_a' => "Transparently, from the first conversation — we'll scope your project based on actual requirements and give you a clear quote. Get in touch for a free consultation.",

'cta_heading' => 'Ready to Grow Your Birmingham Business Alongside the City?',
'cta_text' => "Get a free consultation and see exactly how we'd help you capture your share of Birmingham's growing market.",
'sort_order' => 3,
],

[
'city' => 'Edinburgh', 'region' => 'Scotland', 'country' => 'United Kingdom', 'slug' => 'edinburgh',
'seo_title' => 'Web Design & Digital Marketing Edinburgh | Creative Elements',
'meta_description' => "Creative Elements builds professional websites for Edinburgh businesses across finance, tourism, and fintech. Web design, SEO & branding for Scotland's capital.",
'focus_keyphrase' => 'web design Edinburgh',
'h1' => 'Web Design & Digital Marketing in Edinburgh, Scotland',
'intro' => "Edinburgh is Scotland's capital and one of the UK's most important financial centres outside London, alongside a thriving tourism and festival economy that swells the city's population every August. Creative Elements helps Edinburgh businesses build websites that work hard across both of those very different audiences.",

'section1_heading' => 'Web Design & Development for Edinburgh Businesses',
'section1_content' => "Edinburgh's business community spans everything from centuries-old financial institutions to fast-growing fintech startups, plus a tourism sector that needs to convert visitors booking months — or minutes — in advance. That range means a generic, one-size-fits-all website rarely performs well here.\n\nWe build fast, mobile-first websites tailored to that range: credibility-first design for financial and professional services firms in the New Town and West End, visually engaging booking-friendly sites for hospitality and tourism businesses across the Old Town and Royal Mile, and lean, modern sites for Edinburgh's growing fintech and startup scene.\n\nEvery Edinburgh project is built with SEO fundamentals from day one, since local search matters enormously both for year-round local customers and for the seasonal surge of visitors during the Festival and Hogmanay.",

'section2_heading' => 'Why Edinburgh Businesses Choose Creative Elements',
'section2_content' => "Edinburgh agency rates reflect the city's status as a major financial centre, which can price smaller and growing businesses out of the design quality they actually need. We built our studio to close that gap — the same calibre of design and strategy, delivered remotely, without New Town office overheads baked into every invoice.\n\nEvery Edinburgh client works directly with our senior team rather than a rotating cast of account managers, which matters in a city where reputation and word of mouth still carry real weight across a relatively close-knit business community.\n\nWe're also upfront about pricing and timelines from the first conversation, which matters for tourism and hospitality clients in particular, who often need to plan digital investment around a clearly defined seasonal calendar.",

'section3_heading' => "Supporting Edinburgh's Finance, Tourism & Fintech Economy",
'section3_content' => "Edinburgh's financial services sector — one of Europe's largest outside London — needs polished, credibility-first websites that reassure institutional and corporate clients making decisions based on trust as much as service. We build with that scrutiny in mind.\n\nThe city's tourism and festival economy, driven by the Fringe, Hogmanay, and a steady year-round flow of visitors, needs visually rich, booking-friendly websites that convert quickly — since a slow or confusing booking flow loses a visitor to the next search result just as easily as a bad review does.\n\nAnd Edinburgh's growing fintech and startup scene, benefiting from the city's financial expertise and university talent pipeline, needs modern, credible websites that can compete for attention and investment against better-known competitors in London and elsewhere.",

'faq1_q' => 'Do you build websites for financial services firms in Edinburgh?', 'faq1_a' => "Yes — this is one of our most common Edinburgh project types. We focus on polished, credibility-first design that reassures institutional and corporate clients evaluating a firm before making contact.",
'faq2_q' => 'Can you help my Edinburgh tourism business handle seasonal demand?', 'faq2_a' => "Yes — we build booking-friendly websites designed to convert well both during quieter months and the seasonal surge around the Fringe Festival and Hogmanay, with fast, frictionless enquiry and booking flows.",
'faq3_q' => 'Do you work with fintech and startup businesses in Edinburgh?', 'faq3_a' => "Yes — we build modern, lean websites for Edinburgh's growing fintech and startup scene, designed to help smaller companies compete for attention against larger, better-funded rivals.",
'faq4_q' => 'Is your pricing more accessible than typical Edinburgh agencies?', 'faq4_a' => "Generally, yes — operating remotely without New Town office overheads lets us offer the same design and development calibre at a more accessible price point.",
'faq5_q' => 'Do you work with businesses across Scotland, not just Edinburgh?', 'faq5_a' => "Yes — we work with clients throughout Scotland entirely remotely via video calls and email, with Edinburgh and Glasgow being our two most common Scottish markets.",

'cta_heading' => 'Ready to Grow Your Edinburgh Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help you convert both local customers and visiting audiences.",
'sort_order' => 4,
],

[
'city' => 'Glasgow', 'region' => 'Scotland', 'country' => 'United Kingdom', 'slug' => 'glasgow',
'seo_title' => 'Web Design & Digital Marketing Glasgow | Creative Elements',
'meta_description' => "Creative Elements builds modern websites for Glasgow businesses across media, retail, and creative industries. Web design, SEO & branding for Scotland's largest city.",
'focus_keyphrase' => 'web design Glasgow',
'h1' => 'Web Design & Digital Marketing in Glasgow, Scotland',
'intro' => "Glasgow is Scotland's largest city — a former industrial and shipbuilding powerhouse that has transformed into a major hub for media, retail, and creative industries, anchored by BBC Scotland and STV. Creative Elements helps Glasgow businesses build a digital presence that reflects that transformation.",

'section1_heading' => 'Web Design & Development for Glasgow Businesses',
'section1_content' => "Glasgow's economy has shifted decisively toward services, media, and retail over the past few decades, and local competition for customers has grown alongside it — a dated or slow website stands out for the wrong reasons in a city known for strong design and creative standards.\n\nWe build fast, mobile-first websites for Glasgow businesses across that range: portfolio-driven sites for media and broadcasting-adjacent companies, visually confident sites for retail and hospitality businesses in the Merchant City and West End, and credibility-first sites for professional services firms in the city centre.\n\nWe also build every Glasgow project with SEO fundamentals from day one, since the city's dense retail and hospitality competition means local search visibility can make or break a smaller business.",

'section2_heading' => 'Why Glasgow Businesses Choose Creative Elements',
'section2_content' => "Glasgow has a genuine creative and design culture, and local businesses are increasingly expected to meet that visual standard — but agency pricing for that quality of work isn't always accessible to smaller and growing companies. We built our studio to close that gap: the same creative calibre, delivered remotely, without city-centre studio overheads.\n\nEvery Glasgow client works directly with our senior design and development team rather than a rotating account management structure, and we're upfront about pricing and scope from the very first conversation.\n\nWe also understand Glasgow's business culture values directness and genuine value for money, and we build our entire process — from quoting to delivery — around exactly that.",

'section3_heading' => "Supporting Glasgow's Media, Retail & Events Economy",
'section3_content' => "Glasgow's media and broadcasting sector, anchored by BBC Scotland and STV, needs professional, portfolio-focused websites that hold up visually while remaining genuinely structured for search performance — not just a pretty showreel.\n\nThe city's strong retail and hospitality scene, concentrated around the Merchant City, West End, and Buchanan Street, needs conversion-focused, mobile-first websites and strong local SEO to stand out in one of Scotland's busiest retail environments.\n\nAnd Glasgow's substantial events and conference economy, built around venues like the SEC (Scottish Event Campus), supports a wide ecosystem of hospitality, logistics, and service businesses that all depend on being easy to find and easy to book online.",

'faq1_q' => 'Do you build websites for media and broadcasting-related businesses in Glasgow?', 'faq1_a' => "Yes — we build portfolio-driven, professional websites for media and creative businesses, balancing strong visual presentation with genuine search performance.",
'faq2_q' => 'Can you help my Glasgow retail or hospitality business stand out locally?', 'faq2_a' => "Yes — we focus on conversion-friendly, mobile-first design paired with strong local SEO, since so much of Glasgow's retail and hospitality competition is decided by local search visibility.",
'faq3_q' => 'Is Glasgow\'s creative standard something you design around specifically?', 'faq3_a' => "Yes — Glasgow has a genuinely strong design culture, and we bring extra attention to visual polish for Glasgow clients while making sure the site still performs commercially.",
'faq4_q' => 'Do you work with businesses connected to Glasgow\'s events and conference economy?', 'faq4_a' => "Yes — hospitality, logistics, and service businesses supporting events around venues like the SEC are a common client type, and we focus on easy-to-find, easy-to-book website design for this sector.",
'faq5_q' => 'How does your pricing compare to Glasgow-based agencies?', 'faq5_a' => "Generally more accessible, since we operate remotely without city-centre studio overheads, while still delivering the same senior-level design and development quality.",

'cta_heading' => 'Ready to Grow Your Glasgow Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business stand out in Scotland's largest city.",
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

echo "<h2>UK location seeding complete</h2>";
echo "<p>Inserted: <strong>$inserted</strong> &nbsp; Skipped (already existed): <strong>$skipped</strong></p>";
echo "<p><a href='/admin/locations.php'>Go to Admin → GEO Locations</a></p>";
echo "<p style='color:red;font-weight:bold'>Delete this file (admin/seed-locations-uk.php) from the server right now.</p>";
