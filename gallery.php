<?php
include 'config/config.php';
include 'libs/App.php';

$resp   = tuqio_api('/api/public/events/dfa-gala-2026/gallery');
$photos = $resp['photos'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>Photo Gallery | Digitally Fit Awards</title>
<meta name="description" content="Browse photos and highlights from Digitally Fit Awards events across Kenya — awards ceremonies, conferences, summits, and community moments.">
<meta name="keywords" content="event photos Kenya, Digitally Fit Awards gallery, awards ceremony photos, Kenya events highlights, event photography Nairobi">
<meta name="author" content="Digitally Fit Awards">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://dfa.tuqiohub.africa/gallery.php">

<!-- Schema.org microdata -->
<meta itemprop="name" content="Photo Gallery | Digitally Fit Awards">
<meta itemprop="description" content="Browse photos and highlights from Digitally Fit Awards events across Kenya.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="Photo Gallery | Digitally Fit Awards">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="https://dfa.tuqiohub.africa/gallery.php">
<meta property="og:description" content="Browse photos and highlights from Digitally Fit Awards events across Kenya.">
<meta property="og:site_name" content="Digitally Fit Awards">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@digitallyfitawards">
<meta name="twitter:title" content="Photo Gallery | Digitally Fit Awards">
<meta name="twitter:description" content="Browse photos and highlights from Digitally Fit Awards events across Kenya.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","name":"Digitally Fit Awards","url":"https://dfa.tuqiohub.africa","contactPoint":{"@type":"ContactPoint","telephone":"+254757140682","email":"info@dfa.tuqiohub.africa","contactType":"customer support"},"sameAs":["https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://www.facebook.com/share/p/1DJyLwtvqf/","https://twitter.com/digitallyfitawards","https://www.tiktok.com/@digitallyfitawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://dfa.tuqiohub.africa/"},{"@type":"ListItem","position":2,"name":"Gallery","item":"https://dfa.tuqiohub.africa/gallery.php"}]}
</script>

<!-- JSON-LD: WebPage -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Photo Gallery | Digitally Fit Awards","url":"https://dfa.tuqiohub.africa/gallery.php","description":"Browse photos and highlights from Digitally Fit Awards events."}
</script>
<link href="<?= SITE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/responsive.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/custom.css" rel="stylesheet">
<link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/svg+xml" href="<?= SITE_URL ?>/assets/images/favicon/favicon.svg">
<link rel="shortcut icon" href="<?= SITE_URL ?>/assets/images/favicon/favicon.ico">
<meta name="apple-mobile-web-app-title" content="Digitally Fit Awards">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
/* ── Filter buttons ── */
.gallery-filters {
    display: flex; flex-wrap: wrap; gap: 8px;
    margin-bottom: 36px; justify-content: center;
}
.gf-btn {
    padding: 9px 22px; border-radius: 6px;
    font-size: .84rem; font-weight: 700;
    border: 2px solid #eee; background: #fff;
    color: #555; cursor: pointer; text-decoration: none;
    transition: all .2s; letter-spacing: .3px;
}
.gf-btn:hover, .gf-btn.active {
    background: #be9b3f; border-color: #be9b3f;
    color: #fff; text-decoration: none;
}

/* ── Isotope grid ── */
.gallery-items .gallery-block {
    margin-bottom: 20px;
    transition: opacity .35s ease, transform .35s ease;
}
.gallery-items .gallery-block.hidden-item {
    opacity: 0; pointer-events: none;
    transform: scale(0.95);
}

/* ── Override gallery-block image height for uniform grid ── */
.gallery-items .gallery-block .image-box { height: 240px; }
.gallery-items .gallery-block .image     { height: 100%; }
.gallery-items .gallery-block .image img { height: 100%; object-fit: cover; }
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

<section class="page-title has-bg-image" style="background-image:url(<?= SITE_URL ?>/assets/images/digitaly-fit-gallery/nairobi-event-view.png);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>DFA Gallery</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>Gallery</li>
            </ul>
        </div>
    </div>
</section>

<section class="gallery-section" style="padding:60px 0;">
    <div class="auto-container">

        <?php if (!empty($photos)): ?>

        <!-- Gallery grid -->
        <div class="row gallery-items">
            <?php foreach ($photos as $photo): ?>
            <div class="gallery-block col-lg-3 col-md-4 col-sm-6">
                <div class="image-box">
                    <figure class="image">
                        <a href="<?= htmlspecialchars($photo['photo']) ?>"
                           data-fancybox="gallery"
                           data-caption="<?= htmlspecialchars($photo['title'] ?? $photo['caption'] ?? '') ?>">
                            <img src="<?= htmlspecialchars($photo['photo']) ?>"
                                 alt="<?= htmlspecialchars($photo['alt'] ?? $photo['title'] ?? '') ?>"
                                 onerror="this.closest('.gallery-block').style.display='none'">
                        </a>
                    </figure>
                    <div class="overlay-box">
                        <div class="icon"><span class="flaticon-zoom-1"></span></div>
                        <?php if (!empty($photo['title'])): ?>
                        <h3><a href="<?= htmlspecialchars($photo['photo']) ?>"
                               data-fancybox="gallery"><?= htmlspecialchars($photo['title']) ?></a></h3>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <!-- 2025 DFA Highlights — static local images -->
        <?php endif; ?>

        <!-- ── 2025 Gala Highlights ─────────────────────────────────── -->
        <div style="margin-top:<?= !empty($photos) ? '60px' : '0' ?>;">
            <div class="sec-title text-center" style="margin-bottom:36px;">
                <span class="title">Behind the Lens</span>
                <h2>2025 DFA Highlights</h2>
                <div class="separator"><span></span></div>
                <div class="text" style="max-width:580px;margin:0 auto;color:#666;">
                    A glimpse into the moments that made the Digitally Fit Awards 2025 an unforgettable celebration of Kenya's digital excellence.
                </div>
            </div>
            <?php
            $highlights = [
                ['alphonce-ceo.webp',    'CEO Alphonce Mbola — Oracom Group',          'Oracom Group CEO addresses Kenya\'s digital leaders at the 2025 DFA event.'],
                ['award-2.webp',         'Award Presentation Moment',                   'A proud moment as a DFA winner receives their award on stage.'],
                ['awarding-time.webp',   'Awarding the Digital Champions',              'Award presentations during the Digitally Fit Awards 2025 gala evening.'],
                ['ceo-awarding.webp',    'CEO Presenting an Award',                     'The CEO of Oracom Group presents an award to a deserving digital champion.'],
                ['ceo-waving.webp',      'DFA Leadership',                              'DFA leadership acknowledges the audience during the gala ceremony.'],
                ['event-lookup.webp',    'A Night to Remember',                         'Guests enjoying the Digitally Fit Awards gala at the venue.'],
                ['gala-moment.webp',     'Gala Highlights',                             'A memorable moment captured at the 2025 DFA Gala evening.'],
                ['speaker.webp',         'Keynote Speaker',                             'An inspiring keynote address at the Digitally Fit Awards 2025.'],
                ['team.webp',            'The DFA Team',                                'The Digitally Fit Awards team — the people behind the celebrations.'],
                ['event-overview.webp',  'Full House at DFA 2025',                      'An overview of the Digitally Fit Awards 2025 venue packed with Kenya\'s digital leaders.'],
            ];
            ?>
            <div class="row gallery-items">
                <?php foreach ($highlights as [$file, $title, $caption]): ?>
                <div class="gallery-block col-lg-3 col-md-4 col-sm-6">
                    <div class="image-box">
                        <figure class="image">
                            <a href="<?= SITE_URL ?>/2025-gallery-digitaly-event/<?= $file ?>"
                               data-fancybox="highlights-2025"
                               data-caption="<?= htmlspecialchars($caption) ?>">
                                <img src="<?= SITE_URL ?>/2025-gallery-digitaly-event/<?= $file ?>"
                                     alt="<?= htmlspecialchars($title) ?>"
                                     loading="lazy"
                                     onerror="this.closest('.gallery-block').style.display='none'">
                            </a>
                        </figure>
                        <div class="overlay-box">
                            <div class="icon"><span class="flaticon-zoom-1"></span></div>
                            <h3><a href="<?= SITE_URL ?>/2025-gallery-digitaly-event/<?= $file ?>"
                                   data-fancybox="highlights-2025"><?= htmlspecialchars($title) ?></a></h3>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
</div>
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>

<script>
/* FancyBox 3 auto-initialises from data-fancybox attributes.
   Override defaults once the lib is ready. */
document.addEventListener('DOMContentLoaded', function () {
    if (typeof $.fancybox !== 'undefined') {
        $.fancybox.defaults.buttons = ['slideShow', 'fullScreen', 'thumbs', 'close'];
    }
});
</script>
</body>
</html>
