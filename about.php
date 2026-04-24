<?php
include 'config/config.php';
include 'libs/App.php';
$galaAbout     = tuqio_api('/api/public/events/baraka-awards-2026');
$aboutSponsors = $galaAbout['sponsors'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>About Baraka Awards Kenya | East Africa's Entertainment & Cultural Awards</title>
<meta name="description" content="Learn about Baraka Awards Kenya — Kenya's premier entertainment and cultural awards ceremony, celebrating outstanding artists, producers, directors, and photographers.">
<meta name="keywords" content="about Baraka Awards Kenya, Kenya entertainment awards ceremony, East Africa cultural awards, Nairobi awards gala, Baraka Awards history, online excellence recognition Kenya, award ceremony Nairobi, who is Baraka Awards, Kenya brand awards organiser, Baraka Awards team, awards platform East Africa, Nairobi gala 2026">
<meta name="author" content="Baraka Awards Kenya">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://barakaawards.tuqiohub.africa/about">

<!-- Schema.org microdata -->
<meta itemprop="name" content="About Baraka Awards Kenya">
<meta itemprop="description" content="Kenya's premier entertainment and cultural awards — celebrating outstanding brands, individuals, and organisations in the region's entertainment landscape.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="About Baraka Awards Kenya | East Africa's Entertainment & Cultural Awards">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="https://barakaawards.tuqiohub.africa/about">
<meta property="og:description" content="Kenya's premier entertainment and cultural awards — celebrating outstanding brands, individuals, and organisations in the region's entertainment landscape.">
<meta property="og:site_name" content="Baraka Awards Kenya">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@barakaawards">
<meta name="twitter:title" content="About Baraka Awards Kenya | East Africa's Entertainment & Cultural Awards">
<meta name="twitter:description" content="Kenya's premier entertainment and cultural awards — celebrating outstanding brands, individuals, and organisations in the region's entertainment landscape.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","@id":"https://barakaawards.tuqiohub.africa/#organization","logo":"https://barakaawards.tuqiohub.africa/assets/images/favicon/favicon-96x96.png","name":"Baraka Awards Kenya","url":"https://barakaawards.tuqiohub.africa","description":"Kenya's premier entertainment and cultural awards platform — organised by the Baraka Awards Kenya team.","contactPoint":{"@type":"ContactPoint","telephone":"+254710388288","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support","availableLanguage":"en-US"},"foundingDate":"2024","foundingLocation":{"@type":"Place","address":{"@type":"PostalAddress","streetAddress":"Southfield Mall, Mombasa Road","addressLocality":"Nairobi","addressRegion":"Nairobi","addressCountry":"Kenya"}},"sameAs":["https://www.instagram.com/barakaawardske/","https://www.facebook.com/barakaawards","https://twitter.com/barakaawards","https://www.tiktok.com/@barakaawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://barakaawards.tuqiohub.africa/"},{"@type":"ListItem","position":2,"name":"About","item":"https://barakaawards.tuqiohub.africa/about"}]}
</script>

<!-- JSON-LD: AboutPage -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"AboutPage","name":"About Baraka Awards Kenya","url":"https://barakaawards.tuqiohub.africa/about","description":"Kenya's premier entertainment and cultural awards — celebrating outstanding brands, individuals, and organisations in the region's entertainment landscape."}
</script>
<link href="<?= SITE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/responsive.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/custom.css" rel="stylesheet">
<link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/svg+xml" href="<?= SITE_URL ?>/assets/images/favicon/favicon.svg">
<link rel="shortcut icon" href="<?= SITE_URL ?>/assets/images/favicon/favicon.ico">
<meta name="apple-mobile-web-app-title" content="Baraka Awards Kenya">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
/* ── Section spacing ── */
.about-section   { padding: 80px 0; background: #fff; }
.about-section-alt { padding: 80px 0; background: #f9fafb; }
.about-cta       { padding: 0 0 80px; background: #f9fafb; }

/* ── Pre-tag label ── */
.pre-tag {
    display: inline-block;
    background: rgba(191,158,68,0.1);
    color: #be9b3f;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    padding: 4px 14px;
    border-radius: 20px;
    margin-bottom: 14px;
}

/* ── Mission split ── */
.about-split { display: flex; min-height: 520px; }
.about-split .split-img {
    flex: 0 0 50%; width: 50%;
    background-size: cover;
    background-position: center;
}
.about-split .split-content {
    flex: 0 0 50%; width: 50%;
    padding: 80px 60px;
    background: #fff;
    display: flex; align-items: center;
}
@media (max-width: 991px) {
    .about-split { flex-direction: column; }
    .about-split .split-img  { width: 100%; min-height: 320px; }
    .about-split .split-content { width: 100%; padding: 50px 28px; }
}
.split-label {
    font-size: .72rem; text-transform: uppercase;
    letter-spacing: 2px; color: #be9b3f;
    font-weight: 700; margin-bottom: 16px;
}
.split-heading {
    font-size: 2rem; font-weight: 900;
    color: #0a0a0a; line-height: 1.25; margin-bottom: 20px;
}
.split-text { font-size: .95rem; color: #666; line-height: 1.85; margin-bottom: 14px; }

/* ── Stats ── */
.about-counter {
    padding: 90px 0;
    background-size: cover;
    background-position: center;
    position: relative;
}
.about-counter::before {
    content: '';
    position: absolute; inset: 0;
    background: rgba(21,16,46,0.78);
}
.about-counter .auto-container { position: relative; z-index: 1; }
.counter-col { text-align: center; padding: 20px 10px; }
.counter-col .count-text {
    display: block;
    font-size: 3.2rem; font-weight: 900;
    color: #fff; line-height: 1;
    margin-bottom: 8px;
}
.counter-col .counter-title {
    font-size: .78rem; text-transform: uppercase;
    letter-spacing: 2px; color: rgba(255,255,255,.6);
    display: block;
}
.counter-col .counter-icon {
    font-size: 2rem; color: #be9b3f;
    display: block; margin-bottom: 14px;
}
.counter-divider {
    width: 1px; background: rgba(255,255,255,.15);
    align-self: stretch; margin: 0 auto;
}

/* ── Section headings ── */
.section-heading { font-size: 2rem; font-weight: 800; color: #0a0a0a; margin-bottom: 10px; }
.section-sub     { font-size: .95rem; color: #777; max-width: 520px; margin: 0 auto; }


/* ── How It Works ── */
.step-wrap { text-align: center; padding: 10px; }
.step-num {
    width: 64px; height: 64px; border-radius: 50%;
    background: #be9b3f; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 900;
    margin: 0 auto 20px;
}
.step-wrap h5 { font-weight: 700; color: #0a0a0a; margin-bottom: 8px; }
.step-wrap p  { font-size: .88rem; color: #777; line-height: 1.7; }
.step-connector {
    display: flex; align-items: center; justify-content: center;
    padding-top: 20px; color: #ddd; font-size: 1.4rem;
}

/* ── Values ── */
.value-item { display: flex; gap: 16px; margin-bottom: 28px; align-items: flex-start; }
.value-icon {
    width: 46px; height: 46px; border-radius: 10px;
    background: rgba(191,158,68,0.1);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1.1rem; color: #be9b3f;
}
.value-item h6 { font-weight: 700; color: #0a0a0a; margin-bottom: 4px; }
.value-item p  { font-size: .85rem; color: #777; margin: 0; line-height: 1.6; }

/* ── CTA box ── */
.cta-box {
    background: linear-gradient(135deg, #be9b3f, #a0822f);
    border-radius: 16px; padding: 48px 36px;
    color: #fff; text-align: center;
}
.cta-box h3 { font-weight: 900; margin-bottom: 14px; }
.cta-box p  { opacity: .88; font-size: .95rem; margin-bottom: 24px; line-height: 1.7; }
.cta-box .theme-btn { background: #fff; color: #be9b3f; border-color: #fff; display: block; text-align: center; }
</style>
</head>
<body>
<div class="page-wrapper">

<?php include 'includes/loader.php'; ?>
<header class="main-header header-style-two">
    <?php include 'includes/header-top.php'; ?>
    <?php include 'includes/nav.php'; ?>
    <?php include 'includes/sticky-header.php'; ?>
    <?php include 'includes/mobile-header.php'; ?>
    <?php include 'includes/search.php'; ?>
</header>
<div class="form-back-drop"></div>
<?php include 'includes/hidden-bar.php'; ?>

<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/images/digitaly-fit-gallery/awards.jpg);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>About Baraka Awards Kenya</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>About</li>
            </ul>
        </div>
    </div>
</section>

<!-- ── Mission ─────────────────────────────────────────────── -->
<div class="about-split wow fadeIn">
    <div class="split-img" style="background-image:url(<?= SITE_URL ?>/assets/images/digitaly-fit-gallery/about-nairobi.jpeg);"></div>
    <div class="split-content">
        <div>
            <div class="split-label">Who We Are</div>
            <h2 class="split-heading">Celebrating Talent, Culture &amp; Excellence in Kenya</h2>
            <p class="split-text">The Baraka Awards Kenya is a community-driven awards platform that recognises and celebrates outstanding talent across humanitarian service, entertainment, events, and media. We believe every champion deserves a stage — and the fans decide the winner.</p>
            <p class="split-text">Born from a passion for uplifting Kenyan creatives and changemakers, the Baraka Awards brings artists, producers, MCs, DJs, photographers, videographers, and humanitarian leaders under one roof — to be recognised, celebrated, and rewarded.</p>
            <p class="split-text">Our inaugural 2026 gala on 30th May at Southfield Mall, Mombasa Road, Nairobi, marks the beginning of an annual tradition of excellence and community celebration.</p>
            <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-one" style="margin-top:8px;">
                <span class="btn-title">View All Awards →</span>
            </a>
        </div>
    </div>
</div>

<!-- ── Stats ────────────────────────────────────────────────── -->
<section class="about-counter" style="background-image:url(<?= SITE_URL ?>/assets/images/digitaly-fit-gallery/nairobi-event-view.png);">
    <div class="auto-container">
        <div class="row">
            <?php
            $statsArr = [
                ['1st',   'Inaugural Edition',  'fa-trophy'],
                ['21',    'Award Categories',   'fa-award'],
                ['89',    'Nominated Talents',  'fa-users'],
                ['30 May','Gala Night 2026',    'fa-calendar-alt'],
            ];
            foreach ($statsArr as $idx => [$n,$l,$i]): ?>
            <div class="col-md-3 col-sm-6 counter-col wow zoomIn" data-wow-delay="<?= $idx * 150 ?>ms">
                <i class="fas <?= $i ?> counter-icon"></i>
                <span class="count-text"><?= $n ?></span>
                <span class="counter-title"><?= $l ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── How It Works ─────────────────────────────────────────── -->
<section class="about-section">
    <div class="auto-container">
        <div class="text-center mb-5">
            <span class="pre-tag">Your Journey</span>
            <h2 class="section-heading">How It Works</h2>
            <p class="section-sub">A simple three-step journey from nomination to the gala stage.</p>
        </div>
        <div class="row align-items-start">
            <div class="col-md-4 wow fadeInUp">
                <div class="step-wrap">
                    <div class="step-num">1</div>
                    <h5>Nominations</h5>
                    <p>Artists, producers, MCs, DJs, photographers, and humanitarians are nominated across 21 categories by the organising team and the public.</p>
                </div>
            </div>
            <div class="col-md-4 wow fadeInUp" data-wow-delay="0.15s">
                <div class="step-wrap">
                    <div class="step-num">2</div>
                    <h5>Public Voting</h5>
                    <p>Fans cast their votes for their favourite nominees from 24th April to 24th May 2026 through our secure online platform. Every vote counts.</p>
                </div>
            </div>
            <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="step-wrap">
                    <div class="step-num">3</div>
                    <h5>Gala Night</h5>
                    <p>Winners are announced live on 30th May 2026 at Southfield Mall, Mombasa Road — a night of glam, music, and celebration of Kenya's finest talent.</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-one">
                <span class="btn-title">View All Nominees</span>
            </a>
        </div>
    </div>
</section>

<!-- ── Sponsors ───────────────────────────────────────────── -->
<?php if (!empty($aboutSponsors)): ?>
<section class="clients-section-two">
    <div class="auto-container">
        <div class="text-center mb-4">
            <span class="pre-tag">Sponsors &amp; Partners</span>
            <h2 class="section-heading">Proudly Supported By</h2>
        </div>
        <div class="sponsors-outer">
            <ul class="clients-carousel owl-carousel owl-theme default-nav">
                <?php foreach ($aboutSponsors as $sp): ?>
                <li class="slide-item">
                    <a href="<?= htmlspecialchars($sp['website'] ?? '#') ?>"
                       target="<?= !empty($sp['website']) ? '_blank' : '_self' ?>"
                       rel="noopener"
                       style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;text-decoration:none;padding:12px 8px;">
                        <?php if (!empty($sp['logo'])): ?>
                        <img src="<?= htmlspecialchars($sp['logo']) ?>"
                             alt="<?= htmlspecialchars($sp['name'] ?? '') ?>"
                             style="height:64px;max-width:140px;object-fit:contain;filter:grayscale(20%);transition:filter .3s;"
                             onmouseover="this.style.filter='grayscale(0)'"
                             onmouseout="this.style.filter='grayscale(20%)'"
                             onerror="this.style.display='none'">
                        <?php endif; ?>
                        <span style="font-size:.74rem;font-weight:700;color:#0a0a0a;letter-spacing:.3px;text-align:center;">
                            <?= htmlspecialchars($sp['name'] ?? '') ?>
                        </span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── Values + CTA ─────────────────────────────────────────── -->
<section class="about-section-alt">
    <div class="auto-container">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-5 mb-lg-0 wow fadeInLeft">
                <span class="pre-tag">Our Values</span>
                <h2 class="section-heading mb-4">What Baraka Awards Stands For</h2>
                <?php
                $values = [
                    ['fa-shield-alt', 'Integrity',     'Every vote, nomination, and result is handled with full transparency. Baraka Awards stands for honest, verifiable recognition.'],
                    ['fa-eye',        'Transparency',  'Public voting, real-time vote counts, and open nomination processes ensure every participant and fan can trust the outcome.'],
                    ['fa-star',       'Excellence',    'We celebrate the very best — artists, producers, MCs, DJs, humanitarians, and media personalities who have raised the bar in Kenya.'],
                    ['fa-heart',      'Community',     'We are built by and for the Kenyan creative community — uniting performers, fans, and supporters around a shared love for culture and art.'],
                ];
                foreach ($values as [$icon,$title,$desc]): ?>
                <div class="value-item">
                    <div class="value-icon"><i class="fas <?= $icon ?>"></i></div>
                    <div>
                        <h6><?= $title ?></h6>
                        <p><?= $desc ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-6 wow fadeInRight">
                <div class="cta-box">
                    <i class="fas fa-microphone mb-4" style="font-size:3rem;opacity:.8;display:block;"></i>
                    <h3>Vote for Your Favourite Today</h3>
                    <p>Voting is open from 24th April to 24th May 2026. Cast your votes for Kenya's finest artists, MCs, DJs, humanitarians and more — every vote makes a difference.</p>
                    <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-two cta-box-btn">
                        <span class="btn-title">Vote Now →</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</div>
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>
</body>
</html>
