<?php
// ⚠️ ONE-TIME USE ONLY — delete this file from the server immediately after running it once.
// Seeds the initial 10 GEO landing pages (5 Australia + 5 USA). Safe to re-run: it skips
// any city slug that already exists, so it won't create duplicates.
require_once __DIR__ . '/../includes/config.php';
requireLogin();
requireAdmin();
$db = getDB();

$locations = [

// ============================================================
// AUSTRALIA
// ============================================================

[
'city' => 'Sydney', 'region' => 'New South Wales', 'country' => 'Australia', 'slug' => 'sydney',
'seo_title' => 'Web Design & Digital Marketing Sydney | Creative Elements',
'meta_description' => "Creative Elements helps Sydney businesses grow online with expert web design, SEO, and branding. Trusted across NSW. Get a free consultation today.",
'focus_keyphrase' => 'web design Sydney',
'h1' => 'Web Design & Digital Marketing in Sydney, NSW',
'intro' => "Sydney is Australia's largest and most competitive business market, home to everything from ASX-listed enterprises in the CBD to independent retailers in Bondi, Parramatta, and the Inner West. Creative Elements helps Sydney businesses build websites and digital marketing campaigns that don't just look good — they convert visitors into paying customers in one of the country's toughest markets to stand out in.",

'section1_heading' => 'Web Design & Development for Sydney Businesses',
'section1_content' => "Standing out in Sydney means competing with thousands of businesses fighting for the same Google real estate — from Barangaroo law firms to Newtown cafés. A generic, slow-loading website simply won't cut it here.\n\nWe build fast, mobile-first websites for Sydney businesses that are engineered around two things: how your Sydney customers actually search, and what makes them click \"Contact\" or \"Buy Now\" instead of bouncing to a competitor. That means clean, modern design, load times under two seconds, and calls-to-action placed exactly where your visitors are ready to act.\n\nWhether you need a corporate site for a professional services firm in the CBD, a booking-friendly site for a hospitality business near Circular Quay, or a full Shopify store for an e-commerce brand shipping out of Alexandria, we build it to the same global design standard — and back it with technical SEO from day one so Sydney customers can actually find you.",

'section2_heading' => 'Why Sydney Businesses Choose Creative Elements',
'section2_content' => "Sydney agency rates are among the highest in the country, largely because of CBD office overheads that get passed straight onto clients. We run a remote-first studio, which means you get the same (or better) calibre of design and strategy without paying for someone else's Barangaroo lease.\n\nEvery Sydney client works directly with our senior team — not a junior account manager relaying messages back and forth. That means faster turnarounds, fewer miscommunications, and a website that actually reflects what you asked for.\n\nWe're also transparent about pricing from the first conversation. No vague \"it depends\" quotes — you'll know exactly what you're paying for and why, whether that's a new website, an SEO campaign, or ongoing digital marketing support for your Sydney business.",

'section3_heading' => "Helping Sydney's Diverse Industries Grow Online",
'section3_content' => "Sydney's economy is unusually diverse for a single city, and that shapes how we approach every project. Financial and professional services firms in the CBD typically need a polished, credibility-first website that reassures corporate clients before the first phone call. Hospitality and tourism businesses around the Rocks, Bondi, and Manly need visually rich sites with fast, frictionless booking flows — because a slow reservation form loses a table just as easily as a bad review does.\n\nTrades and local service businesses across Western Sydney and the Hills District tend to convert best on lead-generation-focused sites paired with local SEO, since most of their customers are searching \"near me\" on a phone. And Sydney's growing e-commerce scene — from Alexandria warehouses to home-based Shopify brands — needs conversion-optimised stores that can handle real transaction volume without breaking under load.\n\nWhatever corner of Sydney's economy you operate in, we build around how your specific customers actually behave, not a one-size-fits-all template.",

'faq1_q' => 'How much does a website cost in Sydney?', 'faq1_a' => "Pricing depends on scope, but we keep it transparent from the first conversation — no vague estimates. Because we run remote-first without Sydney CBD overheads, our packages are typically more accessible than comparable local agencies while matching (or exceeding) the design quality. Get in touch for a free, no-obligation quote based on what your business actually needs.",
'faq2_q' => 'Do you work with businesses outside the Sydney CBD?', 'faq2_a' => "Yes — a large share of our Sydney clients are based in Western Sydney, the Inner West, the Northern Beaches, and the Hills District. Since we work remotely with clients across NSW, your suburb doesn't affect the quality or speed of the work.",
'faq3_q' => 'How long does it take to build a website?', 'faq3_a' => "Most Sydney business websites are completed within 3-6 weeks from kickoff, depending on how much custom content and functionality is involved. E-commerce builds and larger corporate sites can take longer. We'll give you a firm timeline before any work begins.",
'faq4_q' => 'Can you help my Sydney business rank higher on Google?', 'faq4_a' => "Yes. Every website we build includes technical SEO foundations, and we also offer ongoing SEO and digital marketing campaigns specifically targeted at Sydney and NSW search terms, so you're competing for the searches that actually bring in customers.",
'faq5_q' => 'Do I need to be based in Sydney to work with you?', 'faq5_a' => "No. We work with Sydney businesses entirely remotely via video calls, email, and project updates — the same process we use for clients across Australia. Many Sydney clients tell us this is actually faster than dealing with a local agency's office hours.",

'cta_heading' => 'Ready to Grow Your Sydney Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business stand out in one of Australia's most competitive markets.",
'sort_order' => 1,
],

[
'city' => 'Melbourne', 'region' => 'Victoria', 'country' => 'Australia', 'slug' => 'melbourne',
'seo_title' => 'Web Design & Digital Marketing Melbourne | Creative Elements',
'meta_description' => "Creative Elements builds beautiful, high-converting websites for Melbourne businesses — web design, branding, and SEO with a creative-first approach.",
'focus_keyphrase' => 'web design Melbourne',
'h1' => 'Web Design & Digital Marketing in Melbourne, VIC',
'intro' => "Melbourne has a reputation as Australia's creative and design capital — and its businesses are held to a higher visual standard because of it. Creative Elements builds websites and brand identities for Melbourne businesses that meet that bar, from Fitzroy design studios to Docklands corporates, without the inflated agency price tag that usually comes with \"Melbourne creative.\"",

'section1_heading' => 'Web Design Built for a Design-Literate City',
'section1_content' => "Melbourne customers notice bad design faster than almost anywhere else in Australia — this is a city built around cafés, laneway retail, and independent brands that live or die on aesthetic and experience. A clunky, outdated website doesn't just underperform here; it actively damages credibility.\n\nWe design and build websites with that expectation in mind: clean typography, considered layout, and a visual identity that feels intentional rather than templated. But we never let design outrun function — every Melbourne site we build is fast, mobile-optimised, and structured around clear conversion paths, whether that's a booking, an enquiry form, or a checkout.\n\nFrom hospitality venues in the CBD laneways to retail brands in Chapel Street and Fitzroy, we build sites that hold up to Melbourne's design scrutiny while still doing the commercial job they're there for.",

'section2_heading' => 'Why Melbourne Businesses Choose Creative Elements',
'section2_content' => "Melbourne's creative agency scene is excellent — and expensive. Small and independent businesses are often priced out of the design quality they actually want. We built Creative Elements to close that gap: global design standards, delivered by a senior creative team, at a price that doesn't assume you have an enterprise marketing budget.\n\nBecause we work remotely, you're not paying for a Collins Street studio lease baked into your quote. And because you work directly with the people doing the actual design and development — not an account manager passing briefs along — the final result matches what you asked for the first time, not the third.\n\nWe also build every Melbourne project with SEO in mind from day one, so the design doesn't just look good — it's structured to actually rank and convert.",

'section3_heading' => 'Supporting Melbourne\'s Creative, Retail & Education Sectors',
'section3_content' => "Melbourne's economy leans heavily on small and independent retail, hospitality, arts and education — sectors where brand feel matters as much as functionality. For independent retailers and hospitality venues, we focus on visual storytelling paired with frictionless booking or ordering flows, since Melbourne customers expect both style and speed.\n\nEducation providers and training institutes across Melbourne need something different: clear information architecture, credibility signals, and enquiry forms that convert prospective students without feeling like a hard sell. We build for that balance specifically.\n\nAnd for Melbourne's growing base of solo creatives, consultants, and small agencies, we offer lean, fast-turnaround websites that punch above their price point — because we know not every great Melbourne business has an enterprise budget to match its ambition.",

'faq1_q' => 'Does Melbourne get a different design approach than other cities?', 'faq1_a' => "We tailor the visual direction to your brand and audience rather than a fixed \"Melbourne style,\" but we do bring extra attention to design polish for Melbourne clients, since the local market is unusually design-literate and quick to judge a weak visual identity.",
'faq2_q' => 'Can you help with branding as well as web design?', 'faq2_a' => "Yes — we offer full branding and graphic design services alongside web development, so Melbourne businesses can get a consistent visual identity across their website, social media, and print materials.",
'faq3_q' => 'Do you work with small independent Melbourne businesses, or only larger companies?', 'faq3_a' => "Both. A large share of our Melbourne clients are independent retailers, hospitality venues, and solo consultants. We scope every project to the business's actual size and budget rather than a one-size-fits-all package.",
'faq4_q' => 'How do you handle SEO for a competitive city like Melbourne?', 'faq4_a' => "We start with technical SEO foundations built into every site (speed, structure, mobile-friendliness), then layer on content and local SEO strategy targeted at the specific Melbourne suburbs and search terms your customers actually use.",
'faq5_q' => 'Can I see examples of Melbourne-style design work you\'ve done?', 'faq5_a' => "Absolutely — check out our Our Work page for recent project examples, or get in touch and we'll walk you through relevant case studies for your industry.",

'cta_heading' => 'Ready to Give Your Melbourne Business a Website It Deserves?',
'cta_text' => "Get a free consultation and see exactly how we'd bring your brand to life for Melbourne's discerning audience.",
'sort_order' => 2,
],

[
'city' => 'Brisbane', 'region' => 'Queensland', 'country' => 'Australia', 'slug' => 'brisbane',
'seo_title' => 'Web Design & Digital Marketing Brisbane | Creative Elements',
'meta_description' => "Fast, modern websites and digital marketing for Brisbane's fastest-growing businesses. Creative Elements — web design, SEO & branding for QLD companies.",
'focus_keyphrase' => 'web design Brisbane',
'h1' => 'Web Design & Digital Marketing in Brisbane, QLD',
'intro' => "Brisbane is one of the fastest-growing cities in Australia, with new businesses launching across the CBD, Fortitude Valley, and the outer suburbs every month — and competition for local customers growing right along with it. Creative Elements helps Brisbane businesses build a digital presence that keeps pace with that growth, from new-build web design to ongoing SEO.",

'section1_heading' => 'Web Design for a Fast-Growing City',
'section1_content' => "Brisbane's population and business growth has been outpacing most of the country, driven partly by interstate migration and partly by momentum building toward the 2032 Olympics. That growth is good news for local businesses — but it also means more competitors are entering the same Google search results every month.\n\nWe build websites for Brisbane businesses that are ready for that competition from launch: fast load times, mobile-first layouts, and clear conversion paths, whether you're a construction or property business in the inner suburbs, a hospitality venue in Fortitude Valley or West End, or a professional services firm in the CBD.\n\nWe also build with future growth in mind — a site that can scale as your Brisbane business expands into new suburbs or service areas, rather than needing a full rebuild a year later.",

'section2_heading' => 'Why Brisbane Businesses Choose Creative Elements',
'section2_content' => "Brisbane's business community still has a close-knit, word-of-mouth feel compared to Sydney or Melbourne — which means your website and online reputation carry outsized weight. A dated or slow site doesn't just cost conversions; it costs referrals too.\n\nWe give every Brisbane client direct access to our senior design and development team, transparent pricing, and a build process that keeps you informed at every stage — because in a market this relationship-driven, trust matters as much as the finished product.\n\nAnd because we work remotely without Brisbane CBD office overheads, you get agency-calibre work without the agency-calibre price tag that's increasingly common as the city's cost of business rises alongside its growth.",

'section3_heading' => "Built for Brisbane's Booming Industries",
'section3_content' => "Brisbane's growth is being driven hard by construction, property, and infrastructure — sectors where a credible, fast-loading website with strong local SEO can be the difference between winning a tender or contract and losing it to a competitor with better online visibility. We build sites for this sector that project scale and reliability from the first impression.\n\nBrisbane's tourism and hospitality scene, stretching from the CBD riverfront down toward the Gold Coast and up to the Sunshine Coast, needs something different again: visually engaging sites with strong booking and enquiry flows built to convert visitors who are often planning trips well in advance.\n\nAnd Brisbane's emerging tech and startup scene — smaller than Sydney's or Melbourne's but growing quickly — needs lean, modern websites that can compete for attention and investment without an enterprise marketing budget. We build for all three, tailored to what actually converts in each.",

'faq1_q' => 'Is Brisbane\'s market different from Sydney or Melbourne for web design?', 'faq1_a' => "In some ways, yes — Brisbane's business community is more relationship-driven and word-of-mouth focused, so we put extra emphasis on trust signals (testimonials, clear contact information, professional design) that reassure a more close-knit customer base.",
'faq2_q' => 'Do you help with local SEO for Brisbane suburbs?', 'faq2_a' => "Yes. We build local SEO into every Brisbane project, targeting the specific suburbs and service areas your business actually operates in — from the inner city out to growth corridors like Ipswich and Logan.",
'faq3_q' => 'Can you build a website for a construction or trades business in Brisbane?', 'faq3_a' => "Definitely — this is one of our most common project types in Brisbane. We focus on fast-loading, mobile-first sites with clear quote-request or contact flows, since most trades customers search and enquire from their phone.",
'faq4_q' => 'How quickly can you launch a website for my Brisbane business?', 'faq4_a' => "Most projects launch within 3-6 weeks depending on scope. Given how fast Brisbane's market is moving, we can also discuss an expedited timeline if you need to launch ahead of a specific date or campaign.",
'faq5_q' => 'Do you offer ongoing digital marketing support after launch?', 'faq5_a' => "Yes — many Brisbane clients continue with ongoing SEO, content, or social media support after their website launches, especially given how quickly the local competitive landscape is shifting.",

'cta_heading' => "Ready to Grow Your Brisbane Business Alongside the City?",
'cta_text' => "Get a free consultation and see exactly how we'd help you capture your share of Brisbane's fast-growing market.",
'sort_order' => 3,
],

[
'city' => 'Perth', 'region' => 'Western Australia', 'country' => 'Australia', 'slug' => 'perth',
'seo_title' => 'Web Design & Digital Marketing Perth | Creative Elements',
'meta_description' => "Creative Elements delivers professional web design, SEO, and branding for Perth businesses across WA's resources, trades, and local service sectors.",
'focus_keyphrase' => 'web design Perth',
'h1' => 'Web Design & Digital Marketing in Perth, WA',
'intro' => "Perth is one of the most isolated major cities in the world — and that geography shapes its business landscape in ways the eastern states don't experience. Creative Elements helps Perth businesses build a digital presence that works hard locally, whether you're serving the resources sector, FIFO workforce, or Perth's growing base of local trades and service businesses.",

'section1_heading' => 'Web Design Built for Perth\'s Unique Market',
'section1_content' => "Perth's distance from the eastern states means local businesses often rely more heavily on digital channels to reach customers, rather than assuming foot traffic or referrals from a densely packed CBD. A strong, fast-loading website matters more here, not less.\n\nWe build websites for Perth businesses that are optimised for exactly that: mobile-first design (since a large share of WA searches happen on-site or on the road), clear service area information, and conversion paths built around how Perth customers actually contact local businesses — phone calls and quote requests, not just email forms.\n\nWhether you're supporting the resources and energy sector out of West Perth, running a trades business across the northern or southern suburbs, or building an e-commerce brand shipping from Perth, we build to the same standard: fast, clean, and structured to convert.",

'section2_heading' => 'Why Perth Businesses Choose Creative Elements',
'section2_content' => "Being geographically distant from the major eastern agencies hasn't stopped Perth businesses from expecting the same quality of work — it's just made it harder to access at a reasonable price, since many WA businesses end up choosing between expensive local agencies or overseas freelancers with no real understanding of the Australian market.\n\nWe sit in between: a remote-first studio that understands the Australian business landscape (including WA's specific market dynamics) without the overhead of a Perth CBD office. You get direct access to senior designers and developers, transparent pricing, and a process that doesn't require you to be in the same timezone to get fast turnarounds.\n\nMany of our Perth clients tell us the biggest difference is simply being kept in the loop — clear updates, no disappearing for weeks at a time, and a finished product that matches what was actually agreed.",

'section3_heading' => "Supporting Perth's Resources, Trades & Local Business Economy",
'section3_content' => "Perth's economy still leans heavily on mining, energy, and resources — directly and through the vast network of contractors, suppliers, and service businesses that support it. For this sector, we build professional, credibility-first websites that reassure larger corporate clients and make it easy to submit enquiries or request quotes.\n\nPerth's trades and local services economy — from the northern suburbs to Rockingham and Mandurah — depends heavily on local search visibility, since most customers search \"near me\" and choose from the first few results. We build lead-generation-focused sites with strong local SEO specifically for this.\n\nAnd Perth's smaller but resilient retail and e-commerce scene benefits from websites built to compensate for the tyranny of distance — fast, mobile-optimised stores that convert customers who might not have a comparable physical store nearby.",

'faq1_q' => 'Does Perth\'s distance from other cities affect how you work with clients?', 'faq1_a' => "Not at all — we work with all our clients remotely regardless of location, using video calls, email, and project updates. Perth clients get the exact same process, timelines, and access to our senior team as clients anywhere else in Australia.",
'faq2_q' => 'Do you build websites for resources and mining-adjacent businesses?', 'faq2_a' => "Yes, this is a common project type for our Perth clients — from contractors and suppliers to professional services firms serving the resources sector. We focus on credibility-first design that reassures larger corporate clients.",
'faq3_q' => 'Can you help my Perth trades business get found on Google?', 'faq3_a' => "Yes — local SEO is one of our core services for Perth trades and service businesses, targeting the specific suburbs and service areas you actually cover, from the northern corridor down to Rockingham and Mandurah.",
'faq4_q' => 'How much does a website cost for a Perth small business?', 'faq4_a' => "It depends on scope, but our remote-first model means Perth businesses typically get better value than comparable local agency quotes. Get in touch for a free, transparent quote based on what you actually need.",
'faq5_q' => 'How long does a typical Perth project take?', 'faq5_a' => "Most websites launch within 3-6 weeks of kickoff. We'll confirm a firm timeline before starting any work, and keep you updated throughout — no disappearing acts.",

'cta_heading' => 'Ready to Grow Your Perth Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business reach more customers across WA.",
'sort_order' => 4,
],

[
'city' => 'Adelaide', 'region' => 'South Australia', 'country' => 'Australia', 'slug' => 'adelaide',
'seo_title' => 'Web Design & Digital Marketing Adelaide | Creative Elements',
'meta_description' => "Creative Elements builds affordable, high-quality websites and digital marketing campaigns for Adelaide businesses across SA's wine, food & SME sectors.",
'focus_keyphrase' => 'web design Adelaide',
'h1' => 'Web Design & Digital Marketing in Adelaide, SA',
'intro' => "Adelaide's business community is smaller and tighter-knit than the eastern capitals — which means reputation, referrals, and a genuinely professional online presence carry real weight. Creative Elements helps Adelaide businesses, from Barossa wineries to CBD professional services firms, build websites that punch above their weight without an eastern-states price tag.",

'section1_heading' => 'Web Design for Adelaide\'s Close-Knit Market',
'section1_content' => "In a smaller market like Adelaide, word travels fast — both good and bad. A slow, outdated, or confusing website doesn't just lose you a sale; it can shape how a fairly small, connected business community perceives your brand more broadly.\n\nWe build clean, fast, mobile-first websites for Adelaide businesses that hold up to that scrutiny — whether it's a wine label website that needs to convey quality and story, a professional services site in the CBD that needs to build instant credibility, or a local retailer needing a straightforward, easy-to-navigate online store.\n\nWe also make sure every Adelaide site is built with SEO fundamentals from day one, since local search matters even more in a market where customers often already have a personal connection to competing businesses.",

'section2_heading' => 'Why Adelaide Businesses Choose Creative Elements',
'section2_content' => "Adelaide businesses are often more price-sensitive than their eastern-state counterparts, and rightly so — the cost of comparable agency work in Sydney or Melbourne rarely makes sense for Adelaide's market size. We built our studio to solve exactly that: agency-quality design and development, delivered remotely, at a price built for Adelaide's actual market conditions.\n\nEvery Adelaide client works directly with senior designers and developers rather than being routed through account managers, which matters even more in a relationship-driven market like this one — you want the same person understanding your brand from the first call through to launch.\n\nWe're upfront about pricing and timelines from day one, because in a market where reputation spreads quickly, we'd rather earn a referral than promise something we can't deliver.",

'section3_heading' => "Serving Adelaide's Wine, Food & Small Business Community",
'section3_content' => "South Australia's wine and food industry — from the Barossa Valley to McLaren Vale — depends on websites that do more than list products; they need to tell a story that justifies a premium price point to customers who are often buying based on brand and provenance as much as taste. We build with that storytelling-first approach for wine and food producers.\n\nAdelaide's defence and advanced manufacturing sector, growing steadily around the Osborne naval shipbuilding precinct and beyond, needs a different kind of website — credibility-first, professional, and built to reassure government and corporate clients during tender and procurement processes.\n\nAnd Adelaide's broad base of independent small businesses and local retailers — the backbone of the SA economy — need affordable, easy-to-manage websites that don't require an in-house marketing team to keep updated. We build with that simplicity in mind, without compromising on design quality.",

'faq1_q' => 'Are your services more affordable for Adelaide businesses than eastern-state agencies?', 'faq1_a' => "Generally, yes. Because we work remotely without capital-city office overheads, our pricing tends to be more accessible than comparable agency quotes from Sydney or Melbourne, while matching the same design and development standard.",
'faq2_q' => 'Do you build websites for wineries and food producers?', 'faq2_a' => "Yes — this is a specialty area for our South Australian clients. We focus on storytelling-driven design that conveys quality and provenance, which matters enormously for wine and premium food brands.",
'faq3_q' => 'Can you help a small Adelaide business manage its own website after launch?', 'faq3_a' => "Yes. We build sites with straightforward content management so you (or your team) can update text, images, and products yourselves without needing ongoing developer support for every small change.",
'faq4_q' => 'Do you work with businesses in regional South Australia, not just Adelaide city?', 'faq4_a' => "Absolutely — we work with clients throughout South Australia, including the Barossa Valley, McLaren Vale, and regional centres, entirely remotely via video calls and email.",
'faq5_q' => 'What industries do you have the most experience with in Adelaide?', 'faq5_a' => "We work across wine and food, professional services, defence-adjacent manufacturing, and general small business/retail — tailoring the approach to whichever industry and audience your business serves.",

'cta_heading' => 'Ready to Grow Your Adelaide Business Online?',
'cta_text' => "Get a free consultation and see exactly how we'd help you build a stronger online presence across South Australia.",
'sort_order' => 5,
],

// ============================================================
// UNITED STATES
// ============================================================

[
'city' => 'New York', 'region' => 'New York', 'country' => 'United States', 'slug' => 'new-york',
'seo_title' => 'Web Design & Digital Marketing New York | Creative Elements',
'meta_description' => "Creative Elements builds high-performance websites and digital marketing campaigns for New York businesses competing in the world's toughest market.",
'focus_keyphrase' => 'web design New York',
'h1' => 'Web Design & Digital Marketing in New York City',
'intro' => "New York is arguably the most competitive digital market on the planet — every industry, every keyword, and every customer's attention is being fought over by thousands of businesses at once. Creative Elements helps New York businesses build websites and digital marketing that can actually compete in that environment, without the Manhattan agency price tag.",

'section1_heading' => 'Web Design for the World\'s Most Competitive Market',
'section1_content' => "In New York, a merely \"good\" website isn't good enough — your competitors down the block, across the borough, or three floors up in the same building are all fighting for the same search terms and the same customer attention span. We build websites engineered specifically to compete in that environment: sub-two-second load times, sharp and modern design, and conversion paths refined for an audience that decides in seconds whether to stay or leave.\n\nFrom finance and professional services firms in Manhattan to retail and hospitality brands across Brooklyn and Queens, we build sites that hold their own visually while being ruthlessly optimised for performance and search visibility — because in New York, there's no such thing as a captive audience.\n\nWe also build every New York project with scalability in mind, since growth here tends to happen fast, and a website that can't handle a traffic spike or a new service line becomes a liability quickly.",

'section2_heading' => 'Why New York Businesses Choose Creative Elements',
'section2_content' => "New York agency rates reflect New York rent — which means small and mid-sized businesses are often priced out of the design quality their bigger competitors can afford. We run a remote-first studio specifically to remove that barrier: the same calibre of strategy and design, without a Fifth Avenue office passed onto your invoice.\n\nEvery New York client works directly with our senior creative and development team — not a rotating cast of account managers — which matters in a market where speed and precision are everything. Briefs don't get lost in translation, and revisions don't take a week to action.\n\nWe're also transparent about scope and pricing from the very first call, because in a market this saturated with agencies overpromising and underdelivering, a straight answer is worth more than a flashy pitch deck.",

'section3_heading' => "Built for New York's Finance, Media & Retail Economy",
'section3_content' => "New York's finance and professional services sector demands websites that project instant credibility and trust — often the deciding factor before a prospective client ever picks up the phone. We build with that first-impression pressure in mind: polished, fast, and unambiguous about what your firm does and why it's trustworthy.\n\nThe city's massive media, fashion, and creative industries need something different — visually bold, brand-forward websites that can stand out in an oversaturated content environment, while still converting on e-commerce or booking flows underneath the design.\n\nAnd New York's independent retail and hospitality scene, spread across five boroughs' worth of hyper-local neighborhoods, needs strong local SEO and mobile-first design, since most customers are searching from their phone a few blocks away and deciding in seconds where to go.",

'faq1_q' => 'Can a website really make a difference in a market as saturated as New York?', 'faq1_a' => "Yes — in fact it matters more here, not less. With so many competitors, a fast, well-structured, SEO-optimized site is often the deciding factor in whether a customer chooses you or the business two search results down. We build specifically to win that margin.",
'faq2_q' => 'Do you work with businesses across all five boroughs?', 'faq2_a' => "Yes — Manhattan, Brooklyn, Queens, the Bronx, and Staten Island. Since we work remotely, your specific borough or neighborhood doesn't affect turnaround time or access to our team.",
'faq3_q' => 'How do you keep pricing competitive in such an expensive market?', 'faq3_a' => "By operating remote-first, without a physical New York office to fund through client invoices. That lets us offer agency-calibre design and strategy at a more accessible price point than comparable Manhattan-based agencies.",
'faq4_q' => 'Can you help my New York business rank against much bigger competitors?', 'faq4_a' => "We build strong technical SEO foundations into every site and can run targeted local and national SEO campaigns to help you compete for the specific searches your customers use — even against larger, better-funded competitors.",
'faq5_q' => 'How fast can you turn around a project for a New York business?', 'faq5_a' => "Most websites launch within 3-6 weeks depending on scope. We understand New York businesses often move fast, and we can discuss expedited timelines for time-sensitive launches or campaigns.",

'cta_heading' => 'Ready to Compete in the Toughest Market in the World?',
'cta_text' => "Get a free consultation and see exactly how we'd help your New York business stand out and convert.",
'sort_order' => 6,
],

[
'city' => 'Los Angeles', 'region' => 'California', 'country' => 'United States', 'slug' => 'los-angeles',
'seo_title' => 'Web Design & Digital Marketing Los Angeles | Creative Elements',
'meta_description' => "Creative Elements builds visually striking, high-converting websites for LA brands across entertainment, e-commerce, and creative industries.",
'focus_keyphrase' => 'web design Los Angeles',
'h1' => 'Web Design & Digital Marketing in Los Angeles, CA',
'intro' => "Los Angeles is a city built on image, storytelling, and brand — from entertainment and media to the influencer-driven e-commerce brands that have made LA a global center for direct-to-consumer retail. Creative Elements builds websites for LA businesses that meet that visual bar while converting just as hard as they look good.",

'section1_heading' => 'Web Design for a Brand-Obsessed City',
'section1_content' => "In Los Angeles, brand perception moves fast — a business's website is often the first (and sometimes only) chance to make an impression before a customer scrolls past to the next tab, the next Instagram ad, or the next competitor's site. We design and build with that reality in mind: visually striking, on-brand websites that don't sacrifice speed or usability for style.\n\nFor entertainment, media, and creative-industry clients, we build sites that function as a portfolio and a brand statement at once — polished enough to hold up next to major studio and agency websites, but fast and structured enough to actually rank on Google.\n\nFor LA's booming e-commerce and DTC brand scene, we build conversion-focused Shopify stores designed around how LA's social-media-native customers actually shop — mobile-first, visually driven, and built to convert impulse traffic from paid ads and influencer partnerships into completed sales.",

'section2_heading' => 'Why Los Angeles Businesses Choose Creative Elements',
'section2_content' => "LA's creative and agency market is enormous — and expensive, often billing premium rates for design work that prioritizes portfolio prestige over actual commercial performance. We take a different approach: brand-forward, genuinely striking design, built by a senior team who also understands conversion, SEO, and the mechanics of a site that actually performs.\n\nBecause we operate remotely, LA clients aren't paying for a Sunset Boulevard studio lease baked into every invoice — you get the same creative caliber without the premium markup that comes with LA's agency scene.\n\nWe also keep every LA client in direct contact with the people actually designing and building their site, which matters in a city where creative vision can get diluted fast when it passes through too many hands.",

'section3_heading' => "Supporting LA's Entertainment, E-Commerce & Creative Economy",
'section3_content' => "Los Angeles's entertainment and media industry needs websites that double as a credibility statement — whether that's a production company, talent representation, or creative studio. We build portfolio-driven sites that showcase work beautifully while still being structured for search visibility, something a lot of \"pretty\" entertainment-industry websites completely ignore.\n\nLA's massive e-commerce and influencer-driven brand economy — concentrated heavily around Silicon Beach and the broader DTC scene — needs Shopify builds that can handle serious paid traffic and convert social-native shoppers who are used to a frictionless, mobile-first experience.\n\nAnd LA's wider creative and consulting economy — photographers, designers, marketing consultants, and boutique agencies — needs lean, fast, visually confident websites that reflect their own brand standards, since in this city, your website is often judged as harshly as the work you're trying to sell.",

'faq1_q' => 'Can you match the visual quality of LA\'s top creative agencies?', 'faq1_a' => "Yes — our senior design team builds brand-forward, visually striking websites specifically for LA's creative and entertainment industries, while making sure the site actually performs commercially, not just visually.",
'faq2_q' => 'Do you build Shopify stores for e-commerce and DTC brands?', 'faq2_a' => "Yes, this is one of our core services for LA clients — conversion-optimized Shopify builds designed around mobile-first, social-media-driven shopping behavior.",
'faq3_q' => 'How do your prices compare to typical LA creative agencies?', 'faq3_a' => "Generally more accessible. Because we work remotely without an LA studio lease, we can offer the same design caliber at a more reasonable price point than many comparable Los Angeles agencies.",
'faq4_q' => 'Do you work with entertainment and media companies specifically?', 'faq4_a' => "Yes — we've built portfolio and brand websites for production companies, creative studios, and media businesses, balancing visual impact with genuine search performance.",
'faq5_q' => 'Can you help drive traffic to my LA business, not just build the website?', 'faq5_a' => "Yes — we offer ongoing SEO and digital marketing support alongside web design, so your new site has a plan to actually attract visitors, not just sit online looking good.",

'cta_heading' => 'Ready to Build a Website That Matches Your LA Brand?',
'cta_text' => "Get a free consultation and see exactly how we'd bring your brand to life — and make it convert.",
'sort_order' => 7,
],

[
'city' => 'Chicago', 'region' => 'Illinois', 'country' => 'United States', 'slug' => 'chicago',
'seo_title' => 'Web Design & Digital Marketing Chicago | Creative Elements',
'meta_description' => "Creative Elements builds practical, high-performing websites for Chicago businesses across logistics, manufacturing, and professional services.",
'focus_keyphrase' => 'web design Chicago',
'h1' => 'Web Design & Digital Marketing in Chicago, IL',
'intro' => "Chicago's business culture is famously practical — Midwest businesses want a website that works, generates leads, and doesn't waste time on flash for its own sake. Creative Elements builds exactly that for Chicago companies, from logistics and manufacturing firms to Loop-based professional services, without sacrificing modern design quality.",

'section1_heading' => 'Web Design Built for Chicago\'s No-Nonsense Business Culture',
'section1_content' => "Chicago businesses tend to value substance over spectacle — a website needs to load fast, explain what you do clearly, and make it easy for a prospective customer or partner to get in touch. We build with that priority front and center: clean, modern design that never gets in the way of function.\n\nFor B2B and logistics companies — an enormous part of Chicago's economy given its position as a national transportation and distribution hub — we build websites that communicate scale and reliability clearly, with straightforward navigation for prospective partners trying to evaluate your capabilities quickly.\n\nFor professional services and corporate headquarters based in the Loop and River North, we build polished, credibility-first sites that reflect Chicago's reputation as a serious, established business city — without the unnecessary flash that can undercut trust in more conservative B2B industries.",

'section2_heading' => 'Why Chicago Businesses Choose Creative Elements',
'section2_content' => "Chicago companies often tell us the same thing: they've worked with agencies that delivered a beautiful site that didn't actually generate leads, or a functional site that looked a decade out of date. We build to close that gap specifically — modern, fast, well-designed websites that are still fundamentally built around lead generation and conversion.\n\nWe operate remote-first, which keeps our pricing more accessible than many Chicago agencies carrying Loop office overhead, while still giving every client direct access to senior designers and developers rather than a rotating account management team.\n\nWe're also straightforward about timelines and pricing from the first conversation — a value that tends to land well with Chicago's practical, no-nonsense business culture.",

'section3_heading' => "Supporting Chicago's Logistics, Manufacturing & B2B Economy",
'section3_content' => "Chicago's position at the center of national rail, trucking, and distribution networks means logistics and supply chain businesses make up a huge share of the local economy. We build websites for this sector that make service areas, capabilities, and contact processes immediately clear to prospective partners doing due diligence.\n\nChicago's manufacturing sector — still one of the largest in the country — often relies on referrals and trade relationships more than consumer-style marketing, so we focus on credibility-driven websites with clear case studies, certifications, and capability statements that support a longer B2B sales cycle.\n\nAnd Chicago's dense professional services and corporate sector needs polished, fast corporate websites that reflect the scale and seriousness of the businesses behind them — the kind of site that holds up when a Fortune 500 procurement team is doing their own research before a call.",

'faq1_q' => 'Do you build websites specifically for B2B and logistics companies?', 'faq1_a' => "Yes — this is one of our most common project types for Chicago clients, given the city's role as a major logistics and distribution hub. We focus on clear service information and lead-generation-focused design for long B2B sales cycles.",
'faq2_q' => 'Is your design style too flashy for a conservative Chicago business?', 'faq2_a' => "Not at all — we tailor the visual direction to your industry and audience. For B2B, manufacturing, and professional services clients, we lean toward clean, credibility-first design rather than unnecessary flash.",
'faq3_q' => 'Can you help generate leads, not just build a nice-looking site?', 'faq3_a' => "Yes — every website we build is structured around a clear conversion goal, whether that's a quote request, a contact form, or a phone call, and we can pair it with ongoing SEO or digital marketing to keep leads coming in.",
'faq4_q' => 'Do you work with manufacturing companies that rely mostly on referrals?', 'faq4_a' => "Yes. Even referral-driven manufacturing businesses benefit from a credible website, since prospective partners increasingly research a company online before or after a referral before picking up the phone.",
'faq5_q' => 'How do you price projects for Chicago businesses?', 'faq5_a' => "Transparently, from the first conversation — we'll scope your project based on actual requirements and give you a clear quote, not a vague range. Get in touch for a free consultation.",

'cta_heading' => 'Ready for a Website That Actually Generates Leads?',
'cta_text' => "Get a free consultation and see exactly how we'd help your Chicago business turn website visitors into real business.",
'sort_order' => 8,
],

[
'city' => 'Miami', 'region' => 'Florida', 'country' => 'United States', 'slug' => 'miami',
'seo_title' => 'Web Design & Digital Marketing Miami | Creative Elements',
'meta_description' => "Creative Elements builds bold, bilingual-ready websites for Miami businesses across hospitality, real estate, and Latin America-facing brands.",
'focus_keyphrase' => 'web design Miami',
'h1' => 'Web Design & Digital Marketing in Miami, FL',
'intro' => "Miami sits at the crossroads of the US and Latin American markets — a bilingual, fast-growing city built on hospitality, real estate, and an increasingly diverse business economy. Creative Elements helps Miami businesses build websites that reflect that energy while converting across both English and Spanish-speaking audiences.",

'section1_heading' => 'Web Design for a Bilingual, Fast-Moving Market',
'section1_content' => "Miami's customer base is genuinely bilingual, and businesses that only design for English-speaking visitors are leaving a significant share of the local market on the table. We build websites for Miami businesses with bilingual content structures in mind from the start — not a bolted-on translation, but a site genuinely built to serve both audiences well.\n\nMiami's visual culture — vibrant, design-forward, and unapologetically bold — also shapes how we approach design here. We build sites with strong visual energy that still load fast and convert clearly, whether that's a hospitality brand near Brickell and South Beach or a real estate firm marketing to both domestic and international buyers.\n\nWe also build with Miami's fast-moving business environment in mind — this is a city where new brands, restaurants, and developments launch constantly, and a website needs to be able to go live quickly without sacrificing quality.",

'section2_heading' => 'Why Miami Businesses Choose Creative Elements',
'section2_content' => "Miami's rapid growth — accelerated by an influx of relocating companies and remote-first businesses over the past several years — has made the local agency market more competitive and, in some cases, more expensive than it used to be. We offer an alternative: the same design caliber, delivered remotely, without Miami's rising commercial real estate costs baked into the invoice.\n\nEvery Miami client works directly with our senior team, and we're comfortable building bilingual content and structure into a project from day one, rather than treating it as an afterthought — something that's still surprisingly rare among agencies serving this market.\n\nWe're also transparent about pricing and timelines up front, which matters in a market moving as quickly as Miami's currently is.",

'section3_heading' => "Serving Miami's Hospitality, Real Estate & International Business Economy",
'section3_content' => "Miami's hospitality, tourism, and nightlife economy depends on visually compelling websites with frictionless booking and reservation flows — customers here are often comparing several options at once on their phone, and a slow or clunky site loses that comparison instantly. We build with that competitive, mobile-first reality in mind.\n\nMiami's real estate market, uniquely positioned as a gateway for both domestic and Latin American investment, needs websites that can present listings clearly to international buyers, often across language and currency considerations — something we build into these projects specifically.\n\nAnd Miami's growing base of relocated tech and professional services companies, drawn by Florida's business-friendly tax environment, need modern, credible websites that establish trust quickly in a market where they may not yet have the local reputation of longer-established competitors.",

'faq1_q' => 'Can you build a bilingual (English/Spanish) website for my Miami business?', 'faq1_a' => "Yes — bilingual website structure is something we build in from the start for Miami clients, not as an afterthought. We'll discuss the best approach for your specific audience during the initial consultation.",
'faq2_q' => 'Do you work with real estate businesses targeting international buyers?', 'faq2_a' => "Yes, this is a common project type for our Miami clients, particularly firms marketing to Latin American investors. We build listing-focused sites designed to present clearly across language and cultural context.",
'faq3_q' => 'Can you build a website quickly for a new Miami restaurant or hospitality launch?', 'faq3_a' => "Yes — we understand Miami's hospitality scene moves fast, and we can work to an expedited timeline for a launch-ready website with booking or reservation integration.",
'faq4_q' => 'Do you help companies that have relocated to Miami establish credibility online?', 'faq4_a' => "Yes. For companies newly established in Miami, we focus on building trust signals into the website quickly — clear service information, credentials, and professional design that doesn't rely on years of local reputation to earn trust.",
'faq5_q' => 'What industries do you work with most in Miami?', 'faq5_a' => "Hospitality and tourism, real estate, and relocated tech/professional services companies are our most common Miami client types, though we work across most industries.",

'cta_heading' => 'Ready to Grow Your Miami Business Across Every Market?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business convert both English and Spanish-speaking customers.",
'sort_order' => 9,
],

[
'city' => 'Dallas', 'region' => 'Texas', 'country' => 'United States', 'slug' => 'dallas',
'seo_title' => 'Web Design & Digital Marketing Dallas | Creative Elements',
'meta_description' => "Creative Elements builds professional, high-converting websites for Dallas businesses across energy, tech, and corporate services sectors.",
'focus_keyphrase' => 'web design Dallas',
'h1' => 'Web Design & Digital Marketing in Dallas, TX',
'intro' => "Dallas has become one of the fastest-growing corporate relocation destinations in the country, drawing energy, tech, and finance companies away from higher-cost states — and bringing an increasingly competitive local business market with them. Creative Elements helps Dallas businesses build websites ready to compete in that growing market.",

'section1_heading' => 'Web Design for a Rapidly Growing Corporate Hub',
'section1_content' => "Dallas-Fort Worth has absorbed a wave of corporate headquarters and relocating businesses in recent years, drawn by Texas's business-friendly environment. That growth means more competition for local customers and talent — and a website that looked competitive five years ago may no longer hold up.\n\nWe build modern, fast, mobile-first websites for Dallas businesses that meet that raised bar: clean corporate design for professional services and finance firms, and lead-generation-focused sites for the trades, real estate, and local service businesses serving Dallas's rapidly expanding suburbs.\n\nWe also build with scalability in mind, since Dallas businesses in growth mode often need a website that can expand — new locations, new service lines, new team pages — without a costly rebuild every time the business grows.",

'section2_heading' => 'Why Dallas Businesses Choose Creative Elements',
'section2_content' => "As more companies relocate to Dallas, the local agency market has grown more competitive — and, in some cases, priced accordingly. We offer an alternative: the same design and development caliber major Dallas agencies provide, delivered remotely without the overhead of a Uptown or downtown office.\n\nEvery Dallas client works directly with senior designers and developers, and we're upfront about pricing and scope from the very first conversation — something that resonates well with Dallas's straightforward, business-first culture.\n\nWe also understand that many Dallas businesses are competing with both established local players and newly relocated national brands, and we build websites specifically designed to help you hold your ground in that increasingly crowded market.",

'section3_heading' => "Supporting Dallas's Energy, Tech & Corporate Services Economy",
'section3_content' => "Dallas's energy sector — encompassing everything from traditional oil and gas to renewable energy firms — needs professional, credibility-first websites that can withstand scrutiny from corporate and institutional clients during procurement and partnership evaluations. We build with that level of polish and clarity in mind.\n\nThe city's fast-growing tech and startup scene, boosted significantly by companies relocating from higher-cost coastal markets, needs modern, fast websites that can compete for talent and investment attention against better-known competitors — often on a leaner budget than their coastal counterparts.\n\nAnd Dallas's rapidly expanding suburbs — from Plano to Frisco to Fort Worth — are full of trades, real estate, and local service businesses that depend heavily on local SEO, since so much of the area's population growth means new residents actively searching for local providers for the first time.",

'faq1_q' => 'Does Dallas\'s rapid growth change how you approach web design here?', 'faq1_a' => "Yes — we build with scalability in mind, since Dallas businesses in growth mode often need to add locations, services, or team members to their site quickly as the business (and the city) expands.",
'faq2_q' => 'Do you work with energy sector companies in Dallas-Fort Worth?', 'faq2_a' => "Yes — we build professional, credibility-focused websites for energy and related B2B companies, designed to hold up during corporate procurement and partnership evaluations.",
'faq3_q' => 'Can you help a relocated company establish itself in the Dallas market?', 'faq3_a' => "Absolutely. For newly relocated businesses, we focus on building strong trust signals into the website from day one, since you may not yet have the years of local reputation that established Dallas competitors have.",
'faq4_q' => 'Do you help with local SEO for the Dallas suburbs like Plano and Frisco?', 'faq4_a' => "Yes — local SEO targeting specific Dallas-Fort Worth suburbs is a core part of what we offer, especially for trades and local service businesses serving the area's rapidly growing population.",
'faq5_q' => 'How does your pricing compare to established Dallas agencies?', 'faq5_a' => "Generally more accessible, since we operate remotely without the overhead of a Dallas office, while still delivering the same senior-level design and development quality.",

'cta_heading' => 'Ready to Compete in the Dallas-Fort Worth Growth Boom?',
'cta_text' => "Get a free consultation and see exactly how we'd help your business stand out as Dallas keeps growing.",
'sort_order' => 10,
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

echo "<h2>Location seeding complete</h2>";
echo "<p>Inserted: <strong>$inserted</strong> &nbsp; Skipped (already existed): <strong>$skipped</strong></p>";
echo "<p><a href='/admin/locations.php'>Go to Admin → GEO Locations</a></p>";
echo "<p style='color:red;font-weight:bold'>Delete this file (admin/seed-locations.php) from the server right now.</p>";
