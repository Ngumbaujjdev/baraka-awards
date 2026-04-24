<?php
include 'config/config.php';
include 'libs/App.php';

// Use the same featured-event logic as the homepage
$_evListResp = tuqio_api('/api/public/events?client=baraka-awards');
$_allEvs     = $_evListResp['data'] ?? [];
usort($_allEvs, fn($a,$b) => strcmp($a['start_date'] ?? '9999-12-31', $b['start_date'] ?? '9999-12-31'));
$_featSlug   = null;
foreach ($_allEvs as $_e) { if (!$_featSlug && !empty($_e['banner_image'])) { $_featSlug = $_e['slug']; break; } }
if (!$_featSlug && !empty($_allEvs)) { $_featSlug = end($_allEvs)['slug'] ?? null; }

$galaResp  = $_featSlug ? tuqio_api('/api/public/events/' . $_featSlug) : [];
$galaEvent = $galaResp['event'] ?? [];
$tickets   = $galaResp['ticket_types'] ?? [];

// Separate individual seats from group tables
$individual = array_filter($tickets, fn($t) => !str_contains(strtolower($t['name']), 'table'));
$tables     = array_filter($tickets, fn($t) =>  str_contains(strtolower($t['name']), 'table'));

function fmt_price($n) {
    return 'KES ' . number_format($n, 0);
}

function is_early_bird($name) {
    return stripos($name, 'early bird') !== false;
}

// Determine sale status label
function ticket_status($t) {
    if ($t['is_sold_out']) return 'sold_out';
    if (!$t['is_available']) return 'coming_soon';
    return 'available';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<!-- SEO -->
<title>Get Tickets | Baraka Awards Kenya Gala 2026</title>
<meta name="description" content="Buy tickets to the Baraka Awards Kenya Gala 2026 — Kenya's premier entertainment and cultural awards. Individual seats and group tables available.">
<meta name="keywords" content="Baraka Awards ticket options, Kenya event tickets, Nairobi award ceremony seats, gala dinner tickets 2026, Baraka Awards Kenya tickets, buy tickets Kenya, East Africa awards gala, VIP tickets Nairobi, entertainment awards tickets, cultural awards tickets Kenya">
<meta name="author" content="Baraka Awards Kenya">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= SITE_URL ?>/tickets">

<!-- Open Graph -->
<meta property="og:title" content="Get Tickets | Baraka Awards Kenya Gala 2026">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= SITE_URL ?>/tickets">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:description" content="Buy tickets to the Baraka Awards Kenya Gala 2026 — Kenya's premier entertainment and cultural awards. Individual seats and group tables available.">
<meta property="og:site_name" content="Baraka Awards Kenya">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@barakaawards">
<meta name="twitter:title" content="Get Tickets | Baraka Awards Kenya Gala 2026">
<meta name="twitter:description" content="Buy tickets to the Baraka Awards Kenya Gala 2026 — individual seats and group tables available.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","@id":"https://barakaawards.tuqiohub.africa/#organization","logo":"https://barakaawards.tuqiohub.africa/assets/images/favicon/favicon-96x96.png","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>","description":"Baraka Awards Kenya recognises and rewards companies, businesses, individuals, and brands that demonstrate exceptional online presence and impact across East Africa.","foundingDate":"2024","foundingLocation":{"@type":"Place","address":{"@type":"PostalAddress","streetAddress":"Southfield Mall, Mombasa Road","addressLocality":"Nairobi","addressRegion":"Nairobi","addressCountry":"Kenya"}},"contactPoint":{"@type":"ContactPoint","telephone":"+254710388288","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support","availableLanguage":"en-US"},"sameAs":["https://www.instagram.com/barakaawardske/","https://www.facebook.com/barakaawards","https://twitter.com/barakaawards","https://www.tiktok.com/@barakaawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?= SITE_URL ?>/"},{"@type":"ListItem","position":2,"name":"Tickets","item":"<?= SITE_URL ?>/tickets"}]}
</script>

<!-- JSON-LD: WebPage -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Get Tickets | Baraka Awards Kenya","url":"<?= SITE_URL ?>/tickets","description":"Buy tickets to the Baraka Awards Kenya Gala 2026 — individual seats and group tables available."}
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

<!-- Page Title -->
<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/images/digitaly-fit-gallery/awards.jpg);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>Get Your Tickets</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>Tickets</li>
            </ul>
        </div>
    </div>
</section>

<section style="padding:70px 0;background:#f9fafb;">
    <div class="auto-container">

        <?php
        $tixDateStr = !empty($galaEvent['start_date']) ? date('d M Y', strtotime($galaEvent['start_date'])) : 'Coming Soon';
        if (!empty($galaEvent['end_date']) && $galaEvent['end_date'] !== $galaEvent['start_date'])
            $tixDateStr .= ' – ' . date('d M Y', strtotime($galaEvent['end_date']));
        $tixVenue = implode(', ', array_filter([$galaEvent['venue_name'] ?? '', $galaEvent['venue_city'] ?? ''])) ?: 'To be announced';
        $tixCity  = $galaEvent['venue_city'] ?? 'Kenya';
        ?>
        <!-- Event Intro Banner -->
        <div class="tix-intro">
            <h2><?= htmlspecialchars($galaEvent['name'] ?? 'Baraka Awards Kenya Gala') ?> &mdash; Join the Celebration</h2>
            <p><?= htmlspecialchars($galaEvent['description'] ?? "Experience Kenya's premier entertainment and cultural awards night.") ?></p>
            <div class="tix-meta">
                <span><i class="flaticon-calendar"></i> <?= htmlspecialchars($tixDateStr) ?></span>
                <span><i class="flaticon-clock-1"></i> <?= htmlspecialchars($tixVenue) ?></span>
                <span><i class="flaticon-location"></i> <?= htmlspecialchars($tixCity) ?></span>
            </div>
        </div>

        <?php if (empty($tickets)): ?>
        <div class="text-center" style="padding:60px 0;color:#aaa;">
            <i class="flaticon-ticket" style="font-size:3rem;margin-bottom:16px;display:block;"></i>
            <p style="font-size:1rem;">Ticket details coming soon. <a href="<?= SITE_URL ?>/contact" style="color:#be9b3f;font-weight:600;">Contact us</a> for more information.</p>
        </div>
        <?php else: ?>

        <!-- ── Individual Seats ── -->
        <?php if (!empty($individual)): ?>
        <div style="margin-bottom:60px;">
            <span class="tix-section-heading">Individual Seats</span>
            <div class="row">
                <?php foreach ($individual as $t):
                    $status    = ticket_status($t);
                    $earlyBird = is_early_bird($t['name']);
                    $saleDate  = !empty($t['sale_starts_at']) ? date('d M Y', strtotime($t['sale_starts_at'])) : null;
                    $saleEnd   = !empty($t['sale_ends_at'])   ? date('d M Y', strtotime($t['sale_ends_at']))   : null;
                    $benefits  = $t['benefits'] ?? [];
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="ticket-card <?= $status !== 'available' ? 'is-unavailable' : '' ?>">
                        <div class="tc-header">
                            <?php if ($status === 'sold_out'): ?>
                                <span class="tc-badge badge-sold">Sold Out</span>
                            <?php elseif ($status === 'coming_soon'): ?>
                                <span class="tc-badge badge-coming">Coming Soon</span>
                            <?php elseif ($earlyBird): ?>
                                <span class="tc-badge badge-early">Early Bird</span>
                            <?php else: ?>
                                <span class="tc-badge badge-avail">On Sale</span>
                            <?php endif; ?>
                            <div class="tc-name"><?= htmlspecialchars($t['name']) ?></div>
                            <div>
                                <span class="tc-price"><?= fmt_price($t['price']) ?></span>
                                <?php if (!empty($t['original_price']) && $t['original_price'] > $t['price']): ?>
                                <span class="tc-original"><?= fmt_price($t['original_price']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tc-body">
                            <div class="tc-remaining"><?= number_format($t['remaining']) ?> seats remaining</div>
                            <?php if (!empty($benefits)): ?>
                            <ul class="tc-benefits">
                                <?php foreach ($benefits as $b): ?>
                                <li><?= htmlspecialchars($b) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                            <?php if ($saleDate && $status === 'coming_soon'): ?>
                            <div class="tc-sale-info">Sales open: <span><?= $saleDate ?></span></div>
                            <?php elseif ($saleEnd && $status === 'available'): ?>
                            <div class="tc-sale-info">Offer ends: <span><?= $saleEnd ?></span></div>
                            <?php endif; ?>
                            <?php if ($status === 'available'): ?>
                            <a href="<?= SITE_URL ?>/checkout?slug=<?= htmlspecialchars($_featSlug ?? '') ?>" class="btn-buy">Buy Ticket →</a>
                            <?php elseif ($status === 'sold_out'): ?>
                            <span class="btn-buy btn-sold">Sold Out</span>
                            <?php else: ?>
                            <span class="btn-buy btn-coming">Coming Soon</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Group Tables ── -->
        <?php if (!empty($tables)): ?>
        <div style="margin-bottom:60px;">
            <span class="tix-section-heading">Group Tables</span>
            <div class="row">
                <?php foreach ($tables as $t):
                    $status   = ticket_status($t);
                    $saleDate = !empty($t['sale_starts_at']) ? date('d M Y', strtotime($t['sale_starts_at'])) : null;
                    $benefits = $t['benefits'] ?? [];
                ?>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="table-card <?= $status !== 'available' ? 'is-unavailable' : '' ?>">
                        <div class="tc-icon"><i class="flaticon-group"></i></div>
                        <div class="tc-info">
                            <h4><?= htmlspecialchars($t['name']) ?>
                                <?php if ($status === 'sold_out'): ?>
                                    <span class="tc-badge badge-sold" style="position:static;margin-left:8px;font-size:.6rem;">Sold Out</span>
                                <?php elseif ($status === 'coming_soon'): ?>
                                    <span class="tc-badge badge-coming" style="position:static;margin-left:8px;font-size:.6rem;background:#ccc;color:#555;">Coming Soon</span>
                                <?php endif; ?>
                            </h4>
                            <p>
                                <?php if (!empty($benefits)): ?>
                                    <?= implode(' &bull; ', array_map('htmlspecialchars', $benefits)) ?>
                                <?php endif; ?>
                                <?php if ($saleDate && $status === 'coming_soon'): ?>
                                    &bull; Sales open <?= $saleDate ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="tc-price-block">
                            <div class="price"><?= fmt_price($t['price']) ?></div>
                            <div class="per">per table</div>
                            <div style="margin-top:12px;">
                                <?php if ($status === 'available'): ?>
                                <a href="<?= SITE_URL ?>/checkout?slug=<?= htmlspecialchars($_featSlug ?? '') ?>" class="btn-buy" style="white-space:nowrap;display:inline-block;padding:10px 20px;">Book Table →</a>
                                <?php elseif ($status === 'sold_out'): ?>
                                <span class="btn-buy btn-sold" style="display:inline-block;padding:10px 20px;">Sold Out</span>
                                <?php else: ?>
                                <span class="btn-buy btn-coming" style="display:inline-block;padding:10px 20px;">Coming Soon</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php endif; ?>

        <!-- CTA -->
        <div style="background:linear-gradient(135deg,#0a0a0a,#1a1a1a);border-radius:16px;padding:48px;text-align:center;color:#fff;margin-top:10px;">
            <h3 style="font-weight:800;margin-bottom:10px;">Questions About Tickets?</h3>
            <p style="color:rgba(255,255,255,.8);max-width:520px;margin:0 auto 24px;">Need group booking assistance, accessibility accommodations, or have a general enquiry? We're happy to help.</p>
            <a href="<?= SITE_URL ?>/contact" class="theme-btn btn-style-one" style="background:#be9b3f;border-color:#be9b3f;">
                <span class="btn-title">Contact Us →</span>
            </a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
</div>

<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>
</body>
</html>
