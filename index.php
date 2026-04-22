<?php
include 'config/config.php';
include 'libs/App.php';

$blogResp  = tuqio_api('/api/public/blog');
$blogPosts = array_slice($blogResp['data'] ?? [], 0, 3);

// ── All Baraka events — driven by client param, no hardcoding ──────────
$_evListResp  = tuqio_api('/api/public/events?client=baraka-awards');
$_allDfaEvs   = $_evListResp['data'] ?? [];
// Sort by start_date ascending (soonest first)
usort($_allDfaEvs, fn($a,$b) => strcmp($a['start_date'] ?? '9999-12-31', $b['start_date'] ?? '9999-12-31'));

// Spotlight = first event with a banner_image; fallback = latest start_date
$featuredEvent = null;
foreach ($_allDfaEvs as $_e) {
    if (!$featuredEvent && !empty($_e['banner_image'])) { $featuredEvent = $_e; }
}
if (!$featuredEvent) { $featuredEvent = !empty($_allDfaEvs) ? end($_allDfaEvs) : []; }
$featuredSlug = $featuredEvent['slug'] ?? '';

// Other events = everything except the spotlight, soonest first
$otherEvents = array_values(array_filter($_allDfaEvs, fn($e) => ($e['slug'] ?? '') !== $featuredSlug));

// Full spotlight details: schedule, sponsors, tickets
$galaResp     = $featuredSlug ? tuqio_api('/api/public/events/' . $featuredSlug) : [];
$galaEvent    = $galaResp['event'] ?? [];
$scheduleDays = $galaResp['schedule_days'] ?? [];
$galaSponsors = $galaResp['sponsors'] ?? [];
$ticketTypes  = $galaResp['ticket_types'] ?? [];
$_ticketsAvailable = count(array_filter($ticketTypes, fn($t) => !empty($t['is_available']))) > 0;

// ── Per-event nominees data for category tabs ────────────────────────
$barakaEventsData = [];
$allCategories = [];   // kept for spotlight hero nomination-date display
foreach ($_allDfaEvs as $_ev) {
    $_slug = $_ev['slug'] ?? '';
    if (!$_slug) continue;
    $_nr   = tuqio_api('/api/public/events/' . $_slug . '/nominees');
    $_cats = $_nr['categories'] ?? [];

    // Group lookup: category id → group name (works when group_id is set)
    $_gl = [];
    foreach ($_nr['groups'] ?? [] as $_g) {
        foreach ($_g['categories'] ?? [] as $_gc) {
            $_gl[$_gc['id']] = $_g['name'];
        }
    }

    // Groups with their category slugs (for pill drill-down)
    $_groups = array_values(array_map(fn($g) => [
        'name'  => $g['name'],
        'slugs' => array_column($g['categories'] ?? [], 'slug'),
    ], $_nr['groups'] ?? []));

    // Category rows for JS
    $_catsArr = array_map(fn($c) => [
        'id'    => $c['id'] ?? '',
        'name'  => $c['name'] ?? '',
        'slug'  => $c['slug'] ?? '',
        'desc'  => $c['description'] ?? '',
        'image' => !empty($c['image'])
                    ? (str_starts_with($c['image'], 'http') ? $c['image'] : API_STORAGE . $c['image'])
                    : '',
        'status'=> $c['nomination_status'] ?? 'collecting',
        'count' => $c['total_count'] ?? count($c['nominees'] ?? []),
        'group' => $_gl[$c['id']] ?? '',
    ], $_cats);

    $_promoted   = count(array_filter($_cats, fn($c) => ($c['nomination_status'] ?? '') === 'promoted'));
    $_collecting = count(array_filter($_cats, fn($c) => ($c['nomination_status'] ?? '') === 'collecting'));

    $barakaEventsData[] = [
        'slug'             => $_slug,
        'name'             => $_ev['name'] ?? '',
        'tagline'          => $_ev['tagline'] ?? '',
        'voting_is_open'   => !empty($_ev['voting_is_open']),
        'voting_closes'    => $_ev['voting_closes_at'] ?? '',
        'start_date'       => $_ev['start_date'] ?? '',
        'total_cats'       => count($_cats),
        'promoted_count'   => $_promoted,
        'collecting_count' => $_collecting,
        'cats'             => $_catsArr,
        'groups'           => $_groups,
    ];

    // Keep allCategories from the spotlight event for hero date display
    if ($_slug === $featuredSlug) { $allCategories = $_cats; }
}
$_barakaEventsJson = json_encode($barakaEventsData);

// Spotlight phase helpers (used by section 2)
$_galaPhase  = $galaEvent['current_phase'] ?? '';
$_vI         = !empty($galaEvent['voting_is_open']);
$_vO         = !empty($galaEvent['voting_opens_at'])  ? strtotime($galaEvent['voting_opens_at'])  : 0;
$_vC         = !empty($galaEvent['voting_closes_at']) ? strtotime($galaEvent['voting_closes_at']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>Baraka Awards Kenya — Recognising Online Excellence</title>
<meta name="description" content="The Baraka Awards Kenya recognises and rewards companies, businesses, individuals, icons, experts, organisations and brands that have a good online presence and create an online impact. Celebrating entertainment excellence across Business of the Year, Person of the Year, and more.">
<meta name="keywords" content="Baraka Awards Kenya, entertainment and cultural awards, online presence awards, business of the year, person of the year, community impact awards Kenya, online awards 2026, social media awards, website awards Africa">
<meta name="author" content="Baraka Awards Kenya">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= SITE_URL ?>/">

<!-- Schema.org microdata -->
<meta itemprop="name" content="Baraka Awards Kenya">
<meta itemprop="description" content="The Baraka Awards Kenya recognises and rewards companies, businesses, individuals, icons, experts, organisations and brands that have a good online presence and create an online impact.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="Baraka Awards Kenya — Recognising Online Excellence">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= SITE_URL ?>/">
<meta property="og:description" content="The Baraka Awards Kenya recognises and rewards companies, businesses, individuals, icons, experts, organisations and brands that have a good online presence and create an online impact.">
<meta property="og:site_name" content="Baraka Awards Kenya">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@barakaawards">
<meta name="twitter:title" content="Baraka Awards Kenya — Recognising Online Excellence">
<meta name="twitter:description" content="The Baraka Awards Kenya recognises and rewards companies, businesses, individuals, icons, experts, organisations and brands that have a good online presence and create an online impact.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>","description":"The Baraka Awards Kenya recognises and rewards companies, businesses, individuals, icons, experts, organisations and brands that have a good online presence and create an online impact.","contactPoint":{"@type":"ContactPoint","telephone":"+254710388288","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support"},"sameAs":["https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://www.facebook.com/share/p/1DJyLwtvqf/","https://twitter.com/barakaawards","https://www.tiktok.com/@barakaawardske"]}
</script>

<!-- JSON-LD: WebSite -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebSite","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>","description":"The Baraka Awards Kenya recognises and rewards companies, businesses, individuals, icons, experts, organisations and brands that have a good online presence and create an online impact.","potentialAction":{"@type":"SearchAction","target":"<?= SITE_URL ?>/events.php?q={search_term_string}","query-input":"required name=search_term_string"}}
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
<link href="<?= SITE_URL ?>/assets/css/color-switcher-design.css" rel="stylesheet">
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


    <!-- Banner Section -->

    <section class="banner-section">

        <div class="banner-carousel owl-carousel owl-theme">

            <!-- Slide 1: Baraka Awards Kenya 2026 Gala -->

            <div class="slide-item slide-bg-1">

                <div class="auto-container">

                    <div class="content-box">

                        <span class="title animate-1">Baraka Awards Kenya 2026 — Southfield Mall, Mombasa Road</span>

                        <h2 class="animate-2">Celebrating Talent, <br>Culture &amp; Excellence</h2>

                        <div class="text animate-3">Baraka Awards Kenya — honouring the best in entertainment, media, events &amp; humanitarian service across 21 award categories</div>

                        <div class="btn-box animate-5">
                            <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-two"><span class="btn-title">View Nominees</span></a>
                            <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-one"><span class="btn-title">Vote Now</span></a>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Slide 2: Nominate -->

            <div class="slide-item slide-bg-2">

                <div class="auto-container">

                    <div class="content-box">

                        <span class="title animate-1">Nominations Open</span>

                        <h2 class="animate-2">Your Vote <br>Decides the Winner</h2>

                        <div class="text animate-3">Voting is open. 21 categories celebrating Kenya's finest artists, MCs, DJs, producers, photographers &amp; more — vote now</div>

                        <div class="btn-box animate-5">
                            <a href="<?= SITE_URL ?>/nominate" class="theme-btn btn-style-two"><span class="btn-title">Nominate Now</span></a>
                            <a href="<?= SITE_URL ?>/about" class="theme-btn btn-style-one"><span class="btn-title">Browse Categories</span></a>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Slide 3: Vote & Tickets -->

            <div class="slide-item slide-bg-3">

                <div class="auto-container">

                    <div class="content-box">

                        <span class="title animate-1">The Stage is Set</span>

                        <h2 class="animate-2">The Stage is Set. <br>The Vote is Yours.</h2>

                        <div class="text animate-3">30th May 2026 — Baraka Awards Kenya Gala Night at Southfield Mall, Mombasa Road. Tickets from KES 500. Book yours now</div>

                        <div class="btn-box animate-5">
                            <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-two"><span class="btn-title">Vote Now</span></a>
                            <a href="<?= SITE_URL ?>/tickets" class="theme-btn btn-style-one"><span class="btn-title">Get Tickets</span></a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!--End Banner Section -->


<!-- ══ 2. SPOTLIGHT — featured event ══════════════ -->
<?php
$_galaBanner  = !empty($galaEvent['banner_image'])
              ? (str_starts_with($galaEvent['banner_image'], 'http') ? $galaEvent['banner_image'] : API_STORAGE . $galaEvent['banner_image'])
              : (!empty($galaEvent['thumbnail_image'])
                  ? (str_starts_with($galaEvent['thumbnail_image'], 'http') ? $galaEvent['thumbnail_image'] : API_STORAGE . $galaEvent['thumbnail_image'])
                  : '');
$_galaDate    = !empty($galaEvent['start_date']) ? date('d M Y', strtotime($galaEvent['start_date'])) : null;
$_phaseLabels = ['voting'=>'Voting Open','on_sale'=>'Tickets On Sale','nomination'=>'Nominations Open','upcoming'=>'Coming Soon'];
$_phaseLabel  = $_phaseLabels[$_galaPhase] ?? 'Coming Soon';
$_venueStr    = trim(implode(', ', array_filter([$galaEvent['venue_name'] ?? '', $galaEvent['venue_city'] ?? '']))) ?: 'Nairobi, Kenya';
// Ticket helpers
$__ftk     = array_filter($ticketTypes, fn($t)=>!empty($t['sale_starts_at']) && strtotime($t['sale_starts_at'])>time());
usort($__ftk, fn($a,$b)=>strtotime($a['sale_starts_at'])<=>strtotime($b['sale_starts_at']));
$__nextTkt = array_values($__ftk)[0] ?? null;
$__hasTkt  = count(array_filter($ticketTypes, fn($t)=>!empty($t['is_available']))) > 0;
// Nomination window from categories
$_nS=$_nE=null;
foreach($allCategories as $_cc){ if(!empty($_cc['nomination_starts_at'])){ $_nS=$_cc['nomination_starts_at']; $_nE=$_cc['nomination_ends_at']??null; break; } }
// Spotlight cat count
$_spotCatCount = count($allCategories);
?>
<section style="padding:80px 0;background:#fff;">
    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">The Flagship Event</span>
            <h2><?= htmlspecialchars($galaEvent['name'] ?? 'Baraka Awards Kenya') ?></h2>
            <span class="divider"></span>
        </div>
        <div class="row align-items-center" style="margin-top:20px;">
            <!-- Left: Event Banner -->
            <div class="col-lg-6 col-md-12 wow fadeInLeft" style="margin-bottom:30px;">
                <div style="position:relative;border-radius:14px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,0.15);">
                    <?php if ($_galaBanner): ?>
                    <img src="<?= htmlspecialchars($_galaBanner) ?>"
                         alt="<?= htmlspecialchars($galaEvent['name'] ?? '') ?>"
                         style="width:100%;height:auto;display:block;"
                         onerror="this.parentElement.style.background='linear-gradient(135deg,#0a0a0a,#1a1a1a)';this.style.display='none'">
                    <?php else: ?>
                    <div style="height:420px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);display:flex;align-items:center;justify-content:center;">
                        <i class="flaticon-trophy-1" style="font-size:5rem;color:rgba(190,155,63,0.5);"></i>
                    </div>
                    <?php endif; ?>
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.72) 0%,transparent 55%);"></div>
                    <div style="position:absolute;bottom:20px;left:20px;">
                        <div style="background:#be9b3f;color:#fff;font-size:.75rem;font-weight:800;padding:4px 14px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;display:inline-block;"><?= htmlspecialchars($_phaseLabel) ?></div>
                        <?php if ($_galaDate): ?>
                        <div style="color:#fff;font-size:1.05rem;font-weight:700;text-shadow:0 2px 8px rgba(0,0,0,0.5);">
                            <i class="fas fa-calendar-alt" style="color:#be9b3f;margin-right:6px;"></i><?= $_galaDate ?>
                        </div>
                        <?php endif; ?>
                        <div style="color:rgba(255,255,255,0.85);font-size:.9rem;">
                            <i class="fas fa-map-marker-alt" style="color:#be9b3f;margin-right:6px;"></i><?= htmlspecialchars($_venueStr) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right: Event Details -->
            <div class="col-lg-6 col-md-12 wow fadeInRight" style="padding-left:40px;margin-bottom:30px;">
                <?php if ($_venueStr || $_galaDate): ?>
                <span class="sub-title" style="font-size:.78rem;color:#be9b3f;font-weight:700;text-transform:uppercase;letter-spacing:2px;"><?= htmlspecialchars(implode(' · ', array_filter([$_venueStr, $_galaDate]))) ?></span>
                <?php endif; ?>
                <h3 style="font-size:2rem;font-weight:800;color:#0d0d0d;margin:10px 0;"><?= htmlspecialchars($galaEvent['name'] ?? 'Baraka Awards Kenya') ?></h3>
                <?php if (!empty($galaEvent['tagline'])): ?>
                <p style="font-size:1.05rem;color:#be9b3f;font-style:italic;margin-bottom:14px;"><?= htmlspecialchars($galaEvent['tagline']) ?></p>
                <?php endif; ?>
                <p style="color:#555;line-height:1.8;margin-bottom:20px;"><?= htmlspecialchars(mb_substr($galaEvent['short_description'] ?? 'An extraordinary evening celebrating cultural and entertainment excellence in Kenya.', 0, 220)) ?>...</p>
                <!-- Info badges -->
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
                    <?php if ($_spotCatCount > 0): ?>
                    <span style="background:#f5f5f5;color:#0d0d0d;border:1px solid #e0e0e0;font-size:.78rem;padding:4px 14px;border-radius:20px;font-weight:600;"><i class="fas fa-trophy" style="color:#be9b3f;margin-right:5px;"></i><?= $_spotCatCount ?> Categories</span>
                    <?php endif; ?>
                    <?php if ($_venueStr): ?>
                    <span style="background:#f5f5f5;color:#0d0d0d;border:1px solid #e0e0e0;font-size:.78rem;padding:4px 14px;border-radius:20px;font-weight:600;"><i class="fas fa-map-marker-alt" style="color:#be9b3f;margin-right:5px;"></i><?= htmlspecialchars($galaEvent['venue_city'] ?? 'Nairobi') ?>, Kenya</span>
                    <?php endif; ?>
                    <?php if ($__hasTkt): ?>
                    <span style="background:#fdf8ee;color:#7a6020;border:1px solid #e8d8a0;font-size:.78rem;padding:4px 14px;border-radius:20px;font-weight:600;"><i class="fas fa-ticket-alt" style="color:#be9b3f;margin-right:5px;"></i>Tickets Available</span>
                    <?php endif; ?>
                </div>
                <!-- Key dates -->
                <div style="margin-bottom:20px;display:flex;flex-direction:column;gap:6px;">
                    <?php if ($__hasTkt): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-ticket-alt" style="color:#be9b3f;width:18px;"></i> <strong>Tickets:</strong> On sale now</div>
                    <?php elseif ($__nextTkt): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-ticket-alt" style="color:#be9b3f;width:18px;"></i> <strong>Tickets on sale:</strong> <?= date('d M Y', strtotime($__nextTkt['sale_starts_at'])) ?></div>
                    <?php endif; ?>
                    <?php if ($_vI): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-vote-yea" style="color:#be9b3f;width:18px;"></i> <strong>Voting:</strong> Open now<?= $_vC ? ' — closes '.date('d M Y',$_vC) : '' ?></div>
                    <?php elseif ($_vO): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-vote-yea" style="color:#be9b3f;width:18px;"></i> <strong>Voting opens:</strong> <?= date('d M Y',$_vO) ?><?= $_vC ? ' — closes '.date('d M Y',$_vC) : '' ?></div>
                    <?php endif; ?>
                    <?php if ($_nS): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-pen-nib" style="color:#be9b3f;width:18px;"></i> <strong>Nominations:</strong> <?= date('d M Y',strtotime($_nS)) ?><?= $_nE ? ' – '.date('d M Y',strtotime($_nE)) : '' ?></div>
                    <?php endif; ?>
                </div>
                <!-- CTAs — driven by event phase -->
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <?php if ($_vI): ?>
                    <a href="<?= SITE_URL ?>/nominees?event=<?= urlencode($featuredSlug) ?>" class="theme-btn btn-style-one" style="background:#be9b3f;border-color:#be9b3f;"><span class="btn-title">Vote Now →</span></a>
                    <?php elseif ($_galaPhase === 'nomination'): ?>
                    <a href="<?= SITE_URL ?>/nominate?event=<?= urlencode($featuredSlug) ?>" class="theme-btn btn-style-one"><span class="btn-title">Nominate Now</span></a>
                    <?php elseif ($__hasTkt): ?>
                    <a href="<?= SITE_URL ?>/tickets" class="theme-btn btn-style-one"><span class="btn-title">Get Tickets</span></a>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/event-detail?slug=<?= urlencode($featuredSlug) ?>" class="theme-btn btn-style-two"><span class="btn-title">Full Event Details →</span></a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ══ 2b. OTHER ACTIVE EVENTS ══════════════════ -->
<?php
$_oePerPage  = 4;  // cards per page — increase if needed
$_oePage     = max(1, (int)($_GET['ep'] ?? 1));
$_oeTotal    = count($otherEvents);
$_oePages    = (int)ceil($_oeTotal / $_oePerPage);
$_oeSlice    = array_slice($otherEvents, ($_oePage-1)*$_oePerPage, $_oePerPage);
?>
<?php if (!empty($otherEvents)): ?>
<section style="padding:70px 0 60px;background:#f4f4f4;border-top:1px solid #e8e8e8;">
    <div class="auto-container">
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <span class="sub-title">More Awards</span>
            <h2 style="font-size:2rem;">Also Open Now</h2>
            <span class="divider"></span>
            <p style="color:#777;max-width:480px;margin:10px auto 0;font-size:.92rem;">More Baraka Awards Kenya are live — cast your vote or submit a nomination.</p>
        </div>

        <div class="row justify-content-center">
        <?php foreach ($_oeSlice as $_i => $_oe):
            $_oeSlug      = $_oe['slug'] ?? '';
            $_oeVoting    = !empty($_oe['voting_is_open']);
            $_oePhase     = $_oe['current_phase'] ?? 'upcoming';
            $_oeCloses    = !empty($_oe['voting_closes_at']) ? date('d M Y', strtotime($_oe['voting_closes_at'])) : null;
            $_oeEventDt   = !empty($_oe['start_date'])       ? date('d M Y', strtotime($_oe['start_date']))       : null;
            $_oeHasBanner = !empty($_oe['banner_image']);
            $_oeBannerUrl = $_oeHasBanner
                ? (str_starts_with($_oe['banner_image'], 'http') ? $_oe['banner_image'] : API_STORAGE . $_oe['banner_image'])
                : '';
            $_oeHasThumb  = !empty($_oe['thumbnail_image']);
            $_oeThumbUrl  = $_oeHasThumb
                ? (str_starts_with($_oe['thumbnail_image'], 'http') ? $_oe['thumbnail_image'] : API_STORAGE . $_oe['thumbnail_image'])
                : '';
            $_oeImgUrl    = $_oeBannerUrl ?: $_oeThumbUrl;
            // Stats from pre-built nominees data
            $_oeEntry     = array_values(array_filter($barakaEventsData, fn($d) => $d['slug'] === $_oeSlug))[0] ?? [];
            $_oePromoted  = $_oeEntry['promoted_count']   ?? 0;
            $_oeTotalCats = $_oeEntry['total_cats']        ?? 0;
            $_oeCollect   = $_oeEntry['collecting_count']  ?? 0;
            // Decorative gradient accent (cycles through variations)
            $_oeAccents   = ['135deg,#0a0a0a 0%,#1e1e1e 55%,#2b1d00 100%', '135deg,#0a0a0a 0%,#1a1a2e 55%,#0f3460 100%', '135deg,#0a0a0a 0%,#1e1a0a 55%,#3a2e00 100%', '135deg,#0d0d0d 0%,#1a0a0a 55%,#2e0f0f 100%'];
            $_oeGrad      = $_oeAccents[$_i % count($_oeAccents)];
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" style="margin-bottom:28px;" data-wow-delay="<?= $_i * 100 ?>ms">
            <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 6px 32px rgba(0,0,0,.09);height:100%;display:flex;flex-direction:column;transition:transform .25s,box-shadow .25s;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 14px 40px rgba(0,0,0,.14)';"
                 onmouseout="this.style.transform='';this.style.boxShadow='0 6px 32px rgba(0,0,0,.09)';">

                <!-- Card header — clickable, links to event detail -->
                <a href="<?= SITE_URL ?>/event-detail?slug=<?= urlencode($_oeSlug) ?>" style="display:block;text-decoration:none;">
                <?php if ($_oeImgUrl): ?>
                <div style="position:relative;height:180px;overflow:hidden;">
                    <img src="<?= htmlspecialchars($_oeImgUrl) ?>"
                         alt="<?= htmlspecialchars($_oe['name'] ?? '') ?>"
                         style="width:100%;height:100%;object-fit:cover;display:block;"
                         onerror="this.parentElement.style.background='linear-gradient(<?= $_oeGrad ?>)';this.style.display='none';">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.72) 0%,transparent 55%);"></div>
                    <!-- Phase badge -->
                    <span style="position:absolute;top:14px;left:14px;font-size:.62rem;font-weight:800;background:<?= $_oeVoting ? '#be9b3f' : '#22c55e' ?>;color:#fff;padding:3px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:.6px;">
                        <?= $_oeVoting ? 'Voting Open' : ($_oePhase === 'nomination' ? 'Nominations Open' : 'Coming Soon') ?>
                    </span>
                    <!-- Event name overlay -->
                    <div style="position:absolute;bottom:16px;left:16px;right:16px;">
                        <h4 style="color:#fff;font-weight:800;font-size:1.05rem;margin:0;line-height:1.3;text-shadow:0 2px 8px rgba(0,0,0,.5);"><?= htmlspecialchars($_oe['name'] ?? '') ?></h4>
                    </div>
                </div>
                <?php else: ?>
                <!-- No image — decorative gradient header -->
                <div style="background:linear-gradient(<?= $_oeGrad ?>);padding:30px 24px 24px;position:relative;overflow:hidden;min-height:170px;">
                    <!-- Decorative rings -->
                    <div style="position:absolute;right:-24px;top:-24px;width:130px;height:130px;border-radius:50%;border:28px solid rgba(190,155,63,.07);pointer-events:none;"></div>
                    <div style="position:absolute;right:20px;bottom:-20px;width:80px;height:80px;border-radius:50%;border:16px solid rgba(190,155,63,.05);pointer-events:none;"></div>
                    <!-- Watermark trophy -->
                    <i class="flaticon-trophy-1" style="position:absolute;right:18px;top:50%;transform:translateY(-50%);font-size:5rem;color:rgba(190,155,63,.1);pointer-events:none;"></i>
                    <!-- Phase badge -->
                    <span style="display:inline-block;font-size:.62rem;font-weight:800;background:<?= $_oeVoting ? '#be9b3f' : '#22c55e' ?>;color:#fff;padding:3px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:.6px;margin-bottom:14px;">
                        <?= $_oeVoting ? 'Voting Open' : ($_oePhase === 'nomination' ? 'Nominations Open' : 'Coming Soon') ?>
                    </span>
                    <h4 style="color:#fff;font-weight:800;font-size:1.15rem;margin:0 0 8px;line-height:1.3;max-width:220px;"><?= htmlspecialchars($_oe['name'] ?? '') ?></h4>
                    <?php if (!empty($_oe['tagline'])): ?>
                    <p style="color:rgba(255,255,255,.48);font-size:.78rem;margin:0;line-height:1.45;max-width:200px;"><?= htmlspecialchars($_oe['tagline']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                </a>

                <!-- Card body -->
                <div style="padding:20px 22px;flex:1;display:flex;flex-direction:column;">
                    <?php if ($_oeImgUrl && !empty($_oe['tagline'])): ?>
                    <p style="font-size:.82rem;color:#666;margin:0 0 14px;line-height:1.5;"><?= htmlspecialchars($_oe['tagline']) ?></p>
                    <?php endif; ?>

                    <!-- Stats chips -->
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
                        <?php if ($_oeVoting && $_oeCloses): ?>
                        <span style="font-size:.75rem;background:#fff8ee;color:#8a6220;border:1px solid #e8d8a0;padding:3px 10px;border-radius:20px;font-weight:600;"><i class="fas fa-clock" style="margin-right:4px;"></i>Closes <?= $_oeCloses ?></span>
                        <?php elseif (!$_oeVoting && $_oeEventDt): ?>
                        <span style="font-size:.75rem;background:#f5f5f5;color:#555;border:1px solid #e5e5e5;padding:3px 10px;border-radius:20px;font-weight:600;"><i class="fas fa-calendar" style="margin-right:4px;"></i><?= $_oeEventDt ?></span>
                        <?php endif; ?>
                        <?php if ($_oePromoted > 0): ?>
                        <span style="font-size:.75rem;background:#fff8ee;color:#8a6220;border:1px solid #e8d8a0;padding:3px 10px;border-radius:20px;font-weight:600;"><i class="fas fa-vote-yea" style="margin-right:4px;color:#be9b3f;"></i><?= $_oePromoted ?> in voting</span>
                        <?php endif; ?>
                        <?php if ($_oeTotalCats > 0): ?>
                        <span style="font-size:.75rem;background:#f5f5f5;color:#555;border:1px solid #e5e5e5;padding:3px 10px;border-radius:20px;font-weight:600;"><i class="fas fa-trophy" style="margin-right:4px;color:#be9b3f;"></i><?= $_oeTotalCats ?> categories</span>
                        <?php endif; ?>
                    </div>

                    <!-- CTAs -->
                    <div style="margin-top:auto;display:flex;gap:8px;">
                        <?php if ($_oeVoting): ?>
                        <a href="<?= SITE_URL ?>/nominees?event=<?= urlencode($_oeSlug) ?>"
                           style="flex:1;display:block;text-align:center;background:#be9b3f;color:#fff;font-weight:700;font-size:.85rem;padding:11px 14px;border-radius:10px;text-decoration:none;transition:background .2s;"
                           onmouseover="this.style.background='#a8883a';" onmouseout="this.style.background='#be9b3f';">
                            Vote Now →
                        </a>
                        <?php elseif ($_oePhase === 'nomination'): ?>
                        <a href="<?= SITE_URL ?>/nominate?event=<?= urlencode($_oeSlug) ?>"
                           style="flex:1;display:block;text-align:center;background:#0a0a0a;color:#fff;font-weight:700;font-size:.85rem;padding:11px 14px;border-radius:10px;text-decoration:none;">
                            Nominate Now
                        </a>
                        <?php endif; ?>
                        <a href="<?= SITE_URL ?>/event-detail?slug=<?= urlencode($_oeSlug) ?>"
                           style="flex:1;display:block;text-align:center;background:#f5f5f5;color:#0a0a0a;font-weight:700;font-size:.85rem;padding:11px 14px;border-radius:10px;text-decoration:none;border:1px solid #e8e8e8;transition:background .2s;"
                           onmouseover="this.style.background='#ececec';" onmouseout="this.style.background='#f5f5f5';">
                            Event Details →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>

        <?php if ($_oePages > 1): ?>
        <!-- Pagination -->
        <div style="display:flex;justify-content:center;align-items:center;gap:8px;margin-top:20px;">
            <?php if ($_oePage > 1): ?>
            <a href="?ep=<?= $_oePage-1 ?>#more-events" style="display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:2px solid rgba(190,155,63,.4);background:transparent;color:#be9b3f;font-weight:700;text-decoration:none;transition:all .18s;">&lsaquo;</a>
            <?php endif; ?>
            <?php for ($__p = 1; $__p <= $_oePages; $__p++): ?>
            <a href="?ep=<?= $__p ?>#more-events"
               style="display:flex;align-items:center;justify-content:center;min-width:38px;height:38px;border-radius:8px;border:2px solid <?= $__p===$_oePage ? '#be9b3f' : 'rgba(190,155,63,.3)' ?>;background:<?= $__p===$_oePage ? '#be9b3f' : 'transparent' ?>;color:<?= $__p===$_oePage ? '#fff' : '#be9b3f' ?>;font-weight:700;text-decoration:none;font-size:.85rem;padding:0 10px;">
                <?= $__p ?>
            </a>
            <?php endfor; ?>
            <?php if ($_oePage < $_oePages): ?>
            <a href="?ep=<?= $_oePage+1 ?>#more-events" style="display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:2px solid rgba(190,155,63,.4);background:transparent;color:#be9b3f;font-weight:700;text-decoration:none;">&rsaquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
<div id="more-events"></div>
<?php endif; ?>



<!-- ══ 3. AWARD CATEGORIES — multi-event tabs ════════ -->
<section style="padding:80px 0;background:linear-gradient(160deg,#0a0a0a,#0a0a0a);">
    <div class="auto-container">

        <!-- Event tab switcher -->
        <div id="barakaEventTabs" style="display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-bottom:30px;"></div>

        <div class="sec-title text-center" style="margin-bottom:24px;">
            <span class="divider" style="margin-bottom:0;"></span>
            <!-- Dynamic status line -->
            <p id="catStatusText" style="color:rgba(255,255,255,0.7);max-width:580px;margin:16px auto 0;font-size:.95rem;"></p>
        </div>

        <!-- Search -->
        <div style="max-width:420px;margin:0 auto 18px;position:relative;">
            <input type="text" id="catSearch" placeholder="Search categories…" autocomplete="off"
                   style="width:100%;padding:12px 44px 12px 18px;border:none;border-radius:30px;font-size:.9rem;color:#333;box-shadow:0 4px 20px rgba(0,0,0,.2);outline:none;">
            <i class="fas fa-search" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);color:#aaa;pointer-events:none;"></i>
        </div>

        <!-- Group filter pills (hidden for events with no group data) -->
        <div id="catGroupPills" style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin-bottom:10px;"></div>
        <!-- Category sub-pills (shown after a group is selected) -->
        <div id="catCatPills" style="display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin-bottom:20px;"></div>

        <!-- Category grid -->
        <div class="row" id="catGrid" style="min-height:220px;"></div>

        <!-- No results -->
        <div id="catNoResults" style="display:none;text-align:center;padding:40px 0;">
            <i class="fas fa-search" style="font-size:2.5rem;color:rgba(255,255,255,.2);"></i>
            <p style="color:rgba(255,255,255,.5);margin-top:12px;">No categories match your search.</p>
        </div>

        <!-- Pagination -->
        <div id="catPagination" style="display:flex;justify-content:center;gap:8px;margin-top:28px;flex-wrap:wrap;"></div>

        <!-- Footer CTAs — JS rendered -->
        <div id="catCtaBar" style="margin-top:28px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;"></div>

    </div>
</section>

<script>
(function(){
    var SITE     = '<?= SITE_URL ?>';
    var EVENTS   = <?= $_barakaEventsJson ?>;
    var PAGE_SIZE= 6;

    // Default to first voting event, else first event
    var activeEventIdx = 0;
    for (var _i = 0; _i < EVENTS.length; _i++) {
        if (EVENTS[_i].voting_is_open) { activeEventIdx = _i; break; }
    }

    var activeGroup  = '';
    var activeCat    = '';
    var searchQ      = '';
    var currentPage  = 1;
    var filtered     = [];

    var statusColors = {collecting:'#22c55e', promoted:'#be9b3f', closed:'#ef4444', completed:'#6366f1'};
    var statusLabels = {collecting:'Accepting Nominations', promoted:'Voting Open', closed:'Closed', completed:'Done'};

    var tabStyle      = 'font-size:.82rem;font-weight:700;padding:8px 18px;border-radius:24px;border:2px solid rgba(190,155,63,.4);background:transparent;color:rgba(255,255,255,.65);cursor:pointer;transition:all .2s;white-space:nowrap;';
    var tabActiveStyle= 'font-size:.82rem;font-weight:700;padding:8px 18px;border-radius:24px;border:2px solid #be9b3f;background:#be9b3f;color:#fff;cursor:pointer;transition:all .2s;white-space:nowrap;';
    var pillStyle     = 'font-size:.75rem;font-weight:700;padding:5px 14px;border-radius:20px;border:1.5px solid rgba(190,155,63,.5);background:transparent;color:rgba(255,255,255,.7);cursor:pointer;transition:all .18s;white-space:nowrap;';
    var pillActiveStyle='font-size:.75rem;font-weight:700;padding:5px 14px;border-radius:20px;border:1.5px solid #be9b3f;background:#be9b3f;color:#fff;cursor:pointer;transition:all .18s;white-space:nowrap;';

    function ev()   { return EVENTS[activeEventIdx]; }
    function cats() { return ev().cats; }
    function groups(){ return ev().groups; }
    function hasGroupData() {
        return groups().some(function(g){ return g.slugs && g.slugs.length > 0; });
    }

    // ── Render event tabs ───────────────────────────────────────────
    function renderTabs() {
        var el = document.getElementById('barakaEventTabs');
        if (!el) return;
        var html = '';
        EVENTS.forEach(function(e, i) {
            var isActive = i === activeEventIdx;
            var badge = e.voting_is_open
                ? '<span style="font-size:.58rem;font-weight:800;background:rgba(255,255,255,.25);color:#fff;padding:1px 7px;border-radius:20px;margin-left:6px;text-transform:uppercase;letter-spacing:.4px;">Voting</span>'
                : '';
            html += '<button data-tab="'+i+'" style="'+(isActive?tabActiveStyle:tabStyle)+'">'+e.name+badge+'</button>';
        });
        el.innerHTML = html;
    }

    // ── Status text ─────────────────────────────────────────────────
    function renderStatusText() {
        var el = document.getElementById('catStatusText');
        if (!el) return;
        var e = ev();
        var txt = '';
        if (e.promoted_count > 0 && e.collecting_count > 0) {
            txt = e.promoted_count+' '+(e.promoted_count===1?'category':'categories')+' now in voting — cast your vote! '+e.collecting_count+' still open for nominations.';
        } else if (e.promoted_count > 0) {
            txt = 'All categories are in voting — cast your vote!';
        } else if (e.collecting_count > 0) {
            txt = 'Nominate your favourites. '+e.total_cats+' '+(e.total_cats===1?'category':'categories')+' open for public nomination.';
        } else {
            txt = e.total_cats+' award '+(e.total_cats===1?'category':'categories')+'.';
        }
        el.textContent = txt;
    }

    // ── Footer CTA bar ───────────────────────────────────────────────
    function renderCtaBar() {
        var el = document.getElementById('catCtaBar');
        if (!el) return;
        var e   = ev();
        var html = '';
        if (e.voting_is_open || e.promoted_count > 0) {
            html += '<a href="'+SITE+'/nominees?event='+encodeURIComponent(e.slug)+'" class="theme-btn btn-style-one" style="background:#be9b3f;border-color:#be9b3f;"><span class="btn-title">Vote Now &rarr;</span></a>';
        }
        if (e.collecting_count > 0) {
            var nomStyle = (e.voting_is_open || e.promoted_count > 0) ? 'class="theme-btn btn-style-two btn-ghost"' : 'class="theme-btn btn-style-one" style="background:#be9b3f;border-color:#be9b3f;"';
            html += '<a href="'+SITE+'/nominate?event='+encodeURIComponent(e.slug)+'" '+nomStyle+'><span class="btn-title">Submit a Nomination</span></a>';
        }
        html += '<a href="'+SITE+'/categories?event='+encodeURIComponent(e.slug)+'" class="theme-btn btn-style-two btn-ghost"><span class="btn-title">Browse All '+e.total_cats+' Categories &rarr;</span></a>';
        el.innerHTML = html;
    }

    // ── Group & category pills ───────────────────────────────────────
    function renderGroupPills() {
        var el = document.getElementById('catGroupPills');
        if (!el) return;
        if (!hasGroupData()) { el.innerHTML = ''; return; }
        var html = '<button data-grp="" style="'+(activeGroup===''?pillActiveStyle:pillStyle)+'">All</button>';
        groups().forEach(function(g) {
            if (!g.slugs || !g.slugs.length) return;
            var esc = g.name.replace(/&/g,'&amp;').replace(/"/g,'&quot;');
            html += '<button data-grp="'+esc+'" style="'+(activeGroup===g.name?pillActiveStyle:pillStyle)+'">'+g.name+'</button>';
        });
        el.innerHTML = html;
    }

    function renderCatPills() {
        var el = document.getElementById('catCatPills');
        if (!el) return;
        if (!activeGroup) { el.innerHTML = ''; return; }
        var grp = groups().filter(function(g){ return g.name === activeGroup; })[0];
        if (!grp || !grp.slugs || !grp.slugs.length) { el.innerHTML = ''; return; }
        var groupCats = cats().filter(function(c){ return grp.slugs.indexOf(c.slug) !== -1; });
        if (!groupCats.length) { el.innerHTML = ''; return; }
        var html = '<button data-cat="" style="'+(activeCat===''?pillActiveStyle:pillStyle)+'">All</button>';
        groupCats.forEach(function(c) {
            html += '<button data-cat="'+c.slug.replace(/&/g,'&amp;').replace(/"/g,'&quot;')+'" style="'+(activeCat===c.slug?pillActiveStyle:pillStyle)+'">'+c.name.replace(/&/g,'&amp;')+'</button>';
        });
        el.innerHTML = html;
    }

    // ── Card builder ────────────────────────────────────────────────
    function buildCard(cat) {
        var e = ev();
        var nomUrl      = SITE+'/nominate?event='+encodeURIComponent(e.slug);
        var nomineesUrl = SITE+'/nominees?event='+encodeURIComponent(e.slug);
        var groupBadge  = cat.group
            ? '<span style="position:absolute;top:10px;left:10px;font-size:.6rem;font-weight:700;background:rgba(0,0,0,.55);color:#be9b3f;padding:2px 9px;border-radius:20px;letter-spacing:.5px;backdrop-filter:blur(4px);">'+cat.group+'</span>'
            : '';
        var imgHtml = cat.image
            ? '<div style="position:relative;height:120px;overflow:hidden;"><img src="'+cat.image+'" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.parentElement.style.background=\'linear-gradient(135deg,#1a1a1a,#000000)\';this.style.display=\'none\';"><div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(190,155,63,.65));"></div>'+groupBadge+'</div>'
            : '<div style="position:relative;height:120px;background:linear-gradient(135deg,#000000,#1a1a1a);display:flex;align-items:center;justify-content:center;"><i class="flaticon-trophy-1" style="font-size:2.2rem;color:rgba(190,155,63,.4);"></i>'+groupBadge+'</div>';
        var color  = statusColors[cat.status] || '#888';
        var label  = statusLabels[cat.status] || cat.status;
        var ctaHtml = (cat.status==='promoted')
            ? '<a href="'+nomineesUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:700;text-decoration:none;">Vote Now \u2192</a>'
            : (!e.voting_is_open && cat.status==='collecting')
                ? '<a href="'+nomUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:700;text-decoration:none;">Nominate \u2192</a>'
                : '<a href="'+nomineesUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:700;text-decoration:none;">View Nominees \u2192</a>';
        var countTxt = cat.count > 0
            ? '<span style="font-size:.71rem;color:rgba(255,255,255,.45);"><i class="fas fa-users" style="margin-right:3px;"></i>'+cat.count+' nominated</span>'
            : '<span style="font-size:.71rem;color:rgba(255,255,255,.3);">No nominees yet</span>';
        return '<div class="col-lg-4 col-md-6 col-sm-12" style="margin-bottom:20px;">'
            +'<div onclick="openCatModal('+JSON.stringify(cat)+')" style="background:rgba(255,255,255,.05);border:1px solid rgba(190,155,63,.18);border-radius:12px;overflow:hidden;cursor:pointer;height:100%;transition:all .22s;"'
            +' onmouseover="this.style.borderColor=\'rgba(190,155,63,.55)\';this.style.background=\'rgba(190,155,63,.09)\';this.style.transform=\'translateY(-2px)\';"'
            +' onmouseout="this.style.borderColor=\'rgba(190,155,63,.18)\';this.style.background=\'rgba(255,255,255,.05)\';this.style.transform=\'\';">'
            +imgHtml
            +'<div style="padding:14px;">'
            +'<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:7px;">'
            +'<h6 style="color:#fff;font-weight:700;font-size:.88rem;margin:0;line-height:1.35;flex:1;">'+cat.name+'</h6>'
            +'<span style="font-size:.63rem;font-weight:700;background:rgba(0,0,0,.22);color:'+color+';padding:2px 8px;border-radius:20px;white-space:nowrap;margin-left:8px;margin-top:2px;">'+label+'</span>'
            +'</div>'
            +(cat.desc?'<p style="color:rgba(255,255,255,.52);font-size:.77rem;line-height:1.5;margin-bottom:9px;">'+cat.desc.substring(0,80)+(cat.desc.length>80?'\u2026':'')+'</p>':'')
            +'<div style="display:flex;justify-content:space-between;align-items:center;">'+countTxt+ctaHtml+'</div>'
            +'</div></div></div>';
    }

    // ── Grid & pagination ────────────────────────────────────────────
    function renderGrid() {
        var grid  = document.getElementById('catGrid');
        var noRes = document.getElementById('catNoResults');
        if (!grid) return;
        if (filtered.length === 0) { grid.innerHTML=''; noRes.style.display='block'; renderPagination(); return; }
        noRes.style.display = 'none';
        var start = (currentPage-1)*PAGE_SIZE;
        grid.innerHTML = filtered.slice(start, start+PAGE_SIZE).map(buildCard).join('');
        renderPagination();
    }

    function renderPagination() {
        var pag   = document.getElementById('catPagination');
        if (!pag) return;
        var total = Math.ceil(filtered.length / PAGE_SIZE);
        if (total <= 1) { pag.innerHTML=''; return; }
        var html = '';
        for (var i=1; i<=total; i++) {
            var act = i === currentPage;
            html += '<button onclick="goCatPage('+i+')" style="min-width:34px;height:34px;border-radius:8px;border:2px solid '+(act?'#be9b3f':'rgba(190,155,63,.3)')+';background:'+(act?'#be9b3f':'transparent')+';color:'+(act?'#fff':'#be9b3f')+';font-weight:700;font-size:.8rem;cursor:pointer;padding:0 10px;transition:all .18s;">'+i+'</button>';
        }
        pag.innerHTML = html;
    }

    window.goCatPage = function(p) {
        currentPage = p; renderGrid();
        var g = document.getElementById('catGrid');
        if (g) window.scrollTo({top:g.getBoundingClientRect().top+window.scrollY-80, behavior:'smooth'});
    };

    // ── Filter ───────────────────────────────────────────────────────
    function applyFilter() {
        filtered = cats().filter(function(c) {
            var grpOk = !activeGroup || c.group === activeGroup;
            var catOk = !activeCat   || c.slug  === activeCat;
            var qOk   = !searchQ     || c.name.toLowerCase().indexOf(searchQ) !== -1;
            return grpOk && catOk && qOk;
        });
        currentPage = 1;
        renderGrid();
    }

    // ── Switch event tab ─────────────────────────────────────────────
    function switchEvent(idx) {
        activeEventIdx = idx;
        activeGroup    = '';
        activeCat      = '';
        searchQ        = '';
        currentPage    = 1;
        var inp = document.getElementById('catSearch');
        if (inp) inp.value = '';
        renderTabs();
        renderStatusText();
        renderGroupPills();
        renderCatPills();
        renderCtaBar();
        applyFilter();
    }

    // ── Modal ────────────────────────────────────────────────────────
    window.openCatModal = function(cat) {
        var e           = ev();
        var nomUrl      = SITE+'/nominate?event='+encodeURIComponent(e.slug);
        var nomineesUrl = SITE+'/nominees?event='+encodeURIComponent(e.slug);
        var color = statusColors[cat.status]||'#888';
        var label = statusLabels[cat.status]||cat.status;
        document.getElementById('catModalName').textContent  = cat.name;
        document.getElementById('catModalDesc').textContent  = cat.desc||'No description available.';
        document.getElementById('catModalBadge').textContent = label;
        document.getElementById('catModalBadge').style.cssText = 'font-size:.7rem;font-weight:700;padding:3px 12px;border-radius:20px;white-space:nowrap;margin-left:12px;margin-top:3px;background:rgba(0,0,0,.06);color:'+color;
        document.getElementById('catModalCount').textContent = cat.count>0?cat.count+' nominee'+(cat.count!==1?'s':'')+' so far':'No nominees yet';
        var cta = document.getElementById('catModalCta');
        if (cat.status==='promoted') {
            cta.href=nomineesUrl+'&category='+cat.slug; cta.querySelector('.btn-title').textContent='Vote Now \u2192';
            cta.style.background='#be9b3f'; cta.style.borderColor='#be9b3f';
        } else if (!e.voting_is_open && cat.status==='collecting') {
            cta.href=nomUrl+'&category='+cat.slug; cta.querySelector('.btn-title').textContent='Nominate for this category \u2192';
            cta.style.background=''; cta.style.borderColor='';
        } else {
            cta.href=nomineesUrl+'&category='+cat.slug; cta.querySelector('.btn-title').textContent='View Nominees \u2192';
            cta.style.background=''; cta.style.borderColor='';
        }
        var img  = document.getElementById('catModalImgEl');
        var icon = document.getElementById('catModalIcon');
        if (cat.image) { img.src=cat.image; img.style.display='block'; icon.style.display='none'; }
        else            { img.style.display='none'; icon.style.display='flex'; }
        if (typeof $!=='undefined') $('#catModal').modal('show');
    };

    // ── Event delegation ─────────────────────────────────────────────
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('#barakaEventTabs button');
        if (btn) { switchEvent(parseInt(btn.getAttribute('data-tab'), 10)||0); return; }

        btn = e.target.closest('#catGroupPills button');
        if (btn) {
            activeGroup = btn.getAttribute('data-grp') || '';
            activeCat   = '';
            renderGroupPills(); renderCatPills(); applyFilter();
            return;
        }
        btn = e.target.closest('#catCatPills button');
        if (btn) {
            activeCat = btn.getAttribute('data-cat') || '';
            renderCatPills(); applyFilter();
        }
    });

    document.getElementById('catSearch').addEventListener('input', function() {
        searchQ = this.value.trim().toLowerCase();
        applyFilter();
    });

    // ── Init ─────────────────────────────────────────────────────────
    renderTabs();
    renderStatusText();
    renderGroupPills();
    renderCtaBar();
    applyFilter();
}());
</script>


<?php if (!empty($ticketTypes)): ?>
<!-- ══ 4b. TICKETS SECTION ═══════════════════════════ -->
<section style="padding:80px 0;background:#fff;">
    <div class="auto-container">
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <span class="sub-title">Baraka Awards Kenya Gala 2026 &mdash; Nairobi</span>
            <h2>Secure Your Seat</h2>
            <span class="divider"></span>
            <p style="color:#666;max-width:520px;margin:10px auto 0;">Join us for an extraordinary evening celebrating entertainment excellence. Choose a ticket that fits your experience.</p>
        </div>
        <div class="row justify-content-center">
        <?php foreach (array_slice($ticketTypes, 0, 3) as $tkt):
            $tPrice     = (int)($tkt['price'] ?? 0);
            $tSoldOut   = !empty($tkt['is_sold_out']);
            $tAvail     = !$tSoldOut && !empty($tkt['is_available']);
            $tSaleStart = !empty($tkt['sale_starts_at']) ? strtotime($tkt['sale_starts_at']) : 0;
            $tSaleEnd   = !empty($tkt['sale_ends_at'])   ? date('d M Y', strtotime($tkt['sale_ends_at'])) : null;
            $tIsVIP     = stripos($tkt['name'],'vip')!==false || stripos($tkt['name'],'vvip')!==false;
            $tIsTable   = stripos($tkt['name'],'table')!==false;
            $tEarlyBird = stripos($tkt['name'],'early bird')!==false || stripos($tkt['name'],'early-bird')!==false;
            $tStatus    = $tSoldOut ? 'sold_out' : ($tAvail ? 'available' : 'coming_soon');
            $tIcon      = $tIsTable ? 'users' : ($tIsVIP ? 'star' : 'ticket-alt');
            $tFaded     = $tStatus !== 'available' ? 'opacity:.75;' : '';
            $tHeaderBg  = $tIsVIP
                ? 'linear-gradient(135deg,#8a6e2a,#be9b3f)'
                : 'linear-gradient(135deg,#0a0a0a,#1a1a1a)';
            $tBorder    = $tIsVIP ? '#be9b3f' : '#e5e7eb';
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" style="margin-bottom:28px;">
            <div style="background:#fff;border:1.5px solid <?= $tBorder ?>;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;height:100%;<?= $tFaded ?><?= $tIsVIP ? 'box-shadow:0 8px 32px rgba(190,155,63,.18);' : '' ?>">
                <!-- Card header -->
                <div style="background:<?= $tHeaderBg ?>;padding:22px 22px 18px;position:relative;">
                    <!-- Status badge -->
                    <?php if ($tStatus === 'sold_out'): ?>
                    <span style="position:absolute;top:14px;right:14px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;background:#c0392b;color:#fff;padding:2px 10px;border-radius:20px;">Sold Out</span>
                    <?php elseif ($tStatus === 'coming_soon'): ?>
                    <span style="position:absolute;top:14px;right:14px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;background:rgba(255,255,255,.22);color:#fff;padding:2px 10px;border-radius:20px;">Coming Soon</span>
                    <?php elseif ($tEarlyBird): ?>
                    <span style="position:absolute;top:14px;right:14px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;background:#be9b3f;color:#fff;padding:2px 10px;border-radius:20px;">Early Bird</span>
                    <?php else: ?>
                    <span style="position:absolute;top:14px;right:14px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;background:#16a34a;color:#fff;padding:2px 10px;border-radius:20px;">On Sale</span>
                    <?php endif; ?>
                    <i class="fas fa-<?= $tIcon ?>" style="color:rgba(255,255,255,.3);font-size:2rem;margin-bottom:8px;display:block;"></i>
                    <div style="font-size:1rem;font-weight:800;color:#fff;margin-bottom:6px;padding-right:80px;line-height:1.3;"><?= htmlspecialchars($tkt['name']) ?></div>
                    <div>
                        <span style="font-size:1.6rem;font-weight:900;color:#fff;"><?= isset($tkt['price']) ? 'KES '.number_format($tPrice) : 'TBA' ?></span>
                        <?php if (!empty($tkt['original_price']) && $tkt['original_price'] > $tPrice): ?>
                        <span style="font-size:.82rem;color:rgba(255,255,255,.5);text-decoration:line-through;margin-left:6px;">KES <?= number_format($tkt['original_price']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($tIsTable): ?><div style="font-size:.72rem;color:rgba(255,255,255,.6);margin-top:2px;">per table (10 seats)</div><?php endif; ?>
                </div>
                <!-- Card body -->
                <div style="padding:20px 22px;flex:1;display:flex;flex-direction:column;">
                    <?php if (!empty($tkt['description'])): ?>
                    <p style="font-size:.85rem;color:#666;line-height:1.6;margin-bottom:14px;flex:1;"><?= htmlspecialchars($tkt['description']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($tkt['benefits'])): ?>
                    <ul style="list-style:none;padding:0;margin:0 0 14px;flex:1;">
                        <?php foreach (array_slice($tkt['benefits'],0,4) as $tb): ?>
                        <li style="font-size:.85rem;color:#444;padding:5px 0;border-bottom:1px solid #f4f4f4;display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-check" style="color:#be9b3f;font-size:.7rem;flex-shrink:0;"></i><?= htmlspecialchars($tb) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($tStatus === 'coming_soon' && $tSaleStart && $tSaleStart > time()): ?>
                    <div style="font-size:.75rem;color:#999;margin-bottom:12px;">Sales open: <strong style="color:#0a0a0a;"><?= date('d M Y', $tSaleStart) ?></strong></div>
                    <?php elseif ($tStatus === 'available' && $tSaleEnd): ?>
                    <div style="font-size:.75rem;color:#999;margin-bottom:12px;">Offer ends: <strong style="color:#be9b3f;"><?= $tSaleEnd ?></strong></div>
                    <?php endif; ?>
                    <?php if ($tStatus === 'available'): ?>
                    <a href="<?= SITE_URL ?>/tickets"
                       style="display:block;text-align:center;background:<?= $tHeaderBg ?>;color:#fff;font-weight:700;font-size:.9rem;padding:12px 20px;border-radius:8px;text-decoration:none;">
                        <?= $tIsTable ? 'Book Table' : 'Buy Ticket' ?> &rarr;
                    </a>
                    <?php elseif ($tStatus === 'sold_out'): ?>
                    <span style="display:block;text-align:center;background:#c0392b;color:#fff;font-weight:700;font-size:.9rem;padding:12px 20px;border-radius:8px;opacity:.7;">Sold Out</span>
                    <?php else: ?>
                    <span style="display:block;text-align:center;background:#e5e7eb;color:#999;font-weight:700;font-size:.9rem;padding:12px 20px;border-radius:8px;cursor:default;">Coming Soon</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <div class="text-center" style="margin-top:24px;">
            <a href="<?= SITE_URL ?>/tickets" class="theme-btn btn-style-two">
                <span class="btn-title">View All Tickets &rarr;</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ══ 5. HOW BARAKA AWARDS WORKS ══════════════════════════════ -->
<section class="feature-section">
    <div class="anim-icons full-width">
        <span class="icon icon-circle-1 wow zoomIn"></span>
        <span class="icon icon-dotted-circle wow zoomIn" data-wow-delay="400ms"></span>
    </div>
    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">Simple Steps</span>
            <h2>How Baraka Awards Kenya Works</h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <div class="feature-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner-box">
                    <div class="icon-box">
                        <div class="icon"><span class="flaticon-calendar-1"></span></div>
                    </div>
                    <h4>1. Browse the Nominees</h4>
                    <p>Explore all categories and the talented artists shortlisted for Baraka Awards Kenya 2026. Find your favourites across 9 award categories.</p>
                    <a href="<?= SITE_URL ?>/nominees" class="read-more">View Nominees <span class="fa fa-arrow-right"></span></a>
                </div>
            </div>
            <div class="feature-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="300ms">
                <div class="inner-box">
                    <div class="icon-box">
                        <div class="icon"><span class="flaticon-ticket"></span></div>
                    </div>
                    <h4>2. Cast Your Vote</h4>
                    <p>Buy a vote bundle, pay via M-Pesa or card, and back your favourite artist. Every vote counts toward crowning the champion.</p>
                    <a href="<?= SITE_URL ?>/nominees" class="read-more">Vote Now <span class="fa fa-arrow-right"></span></a>
                </div>
            </div>
            <div class="feature-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="600ms">
                <div class="inner-box">
                    <div class="icon-box">
                        <div class="icon"><span class="flaticon-trophy-1"></span></div>
                    </div>
                    <h4>3. Celebrate the Winners</h4>
                    <p>Join us at the Baraka Awards Kenya Gala on 30th May 2026 at the Southfield Mall, Mombasa Road — a landmark celebration of innovation, creativity, and cultural and entertainment excellence in Kenya.</p>
                    <a href="<?= SITE_URL ?>/tickets" class="read-more">Get Tickets <span class="fa fa-arrow-right"></span></a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ══ 5b. AWARD CATEGORIES — removed; now handled by dynamic section above ══ -->


<?php if (!empty($scheduleDays)): ?>
<section class="schedule-section" style="background:#f9fafb;padding:80px 0;">
    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">30th May 2026 &mdash; Southfield Mall, Mombasa Road, Nairobi</span>
            <h2>Gala Evening Programme</h2>
            <span class="divider"></span>
        </div>
        <?php
        $typeIcons = ['ceremony'=>'fa-award','networking'=>'fa-handshake','session'=>'fa-microphone','performance'=>'fa-music','break'=>'fa-coffee','keynote'=>'fa-star'];
        foreach ($scheduleDays as $day):
        ?>
        <div class="schedule-tabs tabs-box">
            <div class="tabs-content">
                <div class="tab active-tab">
                    <?php if (!empty($day['date'])): ?>
                    <div style="margin-bottom:20px;">
                        <h6 style="color:#0d0d0d;"><i class="fas fa-calendar-day" style="color:#be9b3f;margin-right:8px;"></i><?= date('l, d F Y', strtotime($day['date'])) ?></h6>
                    </div>
                    <?php endif; ?>
                    <div class="schedule-timeline">
                        <?php foreach ($day['sessions'] ?? [] as $si => $session):
                            $icon   = $typeIcons[$session['type'] ?? ''] ?? 'fa-microphone';
                            $isEven = ($si % 2 === 1);
                            $timeStr = '';
                            if (!empty($session['start_time'])) {
                                $timeStr = date('g:i A', strtotime($session['start_time']));
                                if (!empty($session['end_time'])) $timeStr .= ' <br>' . date('g:i A', strtotime($session['end_time']));
                            }
                        ?>
                        <div class="schedule-block <?= $isEven ? 'even' : '' ?>">
                            <div class="inner-box">
                                <div class="inner">
                                    <div class="date">
                                        <span><?= $timeStr ?: 'TBD' ?></span>
                                    </div>
                                    <div class="speaker-info">
                                        <span class="icon fa <?= $icon ?>"></span>
                                        <?php if (!empty($session['speaker'])): ?>
                                        <h5 class="name"><?= htmlspecialchars($session['speaker']) ?></h5>
                                        <span class="designation"><?= htmlspecialchars($session['speaker_title'] ?? '') ?></span>
                                        <?php else: ?>
                                        <h5 class="name"><?= htmlspecialchars(ucfirst($session['type'] ?? 'Session')) ?></h5>
                                        <?php if (!empty($session['location'])): ?>
                                        <span class="designation"><i class="fas fa-map-marker-alt" style="margin-right:4px;"></i><?= htmlspecialchars($session['location']) ?></span>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <h4><a href="#"><?= htmlspecialchars($session['title']) ?><?= !empty($session['is_highlighted']) ? ' <i class="fas fa-star" style="color:#f59e0b;font-size:.75rem;"></i>' : '' ?></a></h4>
                                    <?php if (!empty($session['description'])): ?>
                                    <p style="font-size:.83rem;color:#aaa;margin:6px 0 0;line-height:1.6;"><?= htmlspecialchars($session['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="text-center" style="margin-top:32px;">
            <a href="<?= SITE_URL ?>/event-detail?slug=dfa-gala-2026" class="theme-btn btn-style-one">
                <span class="btn-title">Full Event Details</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ══ 6. RECENT BLOG POSTS ══════════════════════════ -->
<?php if (!empty($blogPosts)): ?>
<section class="news-section">
    <div class="auto-container">
        <div class="sec-title">
            <span class="sub-title">News &amp; Insights</span>
            <h2>Baraka Awards News &amp; Updates</h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <?php $delays = ['', '400ms', '800ms']; ?>
            <?php foreach ($blogPosts as $i => $post): ?>
            <div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInRight"
                 <?= !empty($delays[$i]) ? 'data-wow-delay="'.$delays[$i].'"' : '' ?>>
                <div class="inner-box">
                    <div class="image-box">
                        <?php if (!empty($post['category']['name'])): ?>
                        <span class="tag"><?= htmlspecialchars($post['category']['name']) ?></span>
                        <?php endif; ?>
                        <figure class="image">
                            <a href="<?= SITE_URL ?>/blog-single?slug=<?= urlencode($post['slug']) ?>">
                                <?php if (!empty($post['featured_image']) && $post['featured_image'] !== 'null'): ?>
                                <img src="<?= htmlspecialchars($post['featured_image']) ?>"
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     onerror="this.parentElement.innerHTML='<div class=\'placeholder-thumb\'><i class=\'fas fa-newspaper\'></i></div>'"
                                     class="news-thumb-img">
                                <?php else: ?>
                                <div class="placeholder-thumb"><i class="fas fa-newspaper"></i></div>
                                <?php endif; ?>
                            </a>
                        </figure>
                    </div>
                    <div class="lower-content">
                        <div class="author">
                            <figure class="thumb">
                                <img src="<?= SITE_URL ?>/assets/images/logo/dfa-logo.svg" alt="Baraka Awards Kenya" style="background:#be9b3f; object-fit:contain; border-radius:50%; padding:2px;">
                            </figure>
                            <h5 class="name">Baraka Awards</h5>
                        </div>
                        <h4><a href="<?= SITE_URL ?>/blog-single?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars(mb_strimwidth($post['title'], 0, 65, '…')) ?></a></h4>
                        <?php if (!empty($post['excerpt'])): ?>
                        <div class="text"><?= htmlspecialchars(mb_strimwidth($post['excerpt'], 0, 100, '…')) ?></div>
                        <?php endif; ?>
                        <ul class="post-info">
                            <?php if (!empty($post['published_at'])): ?>
                            <li><span class="far fa-calendar"></span> <?= date('d M Y', strtotime($post['published_at'])) ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="sec-bottom-text">
            <div class="text">Want to read more? <a href="<?= SITE_URL ?>/blog">View all articles →</a></div>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- Sponsors -->
<?php if (!empty($galaSponsors)): ?>
<section style="padding:70px 0 80px;background:#fff;border-top:1px solid #f0f0f0;">
    <div class="auto-container">
        <div class="sec-title text-center" style="margin-bottom:10px;">
            <span class="sub-title">Baraka Awards Kenya Gala 2026</span>
            <h2>Sponsors &amp; Partners</h2>
            <span class="divider"></span>
            <p style="color:#777;max-width:520px;margin:10px auto 0;">We are grateful to the organisations making Baraka Awards Kenya 2026 possible.</p>
        </div>

        <!-- Carousel strip -->
        <div style="background:#f8faf9;border:1px solid #e8f0ee;border-radius:16px;padding:36px 24px;margin-top:40px;">
            <ul class="clients-carousel owl-carousel owl-theme default-nav" style="margin:0;">
                <?php foreach ($galaSponsors as $sp): ?>
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
                        <span style="font-size:.74rem;font-weight:700;color:#0d0d0d;letter-spacing:.3px;text-align:center;line-height:1.3;">
                            <?= htmlspecialchars($sp['name'] ?? '') ?>
                        </span>
                        <?php
                        $tierBadgeColors = ['title'=>'#be9b3f','partner'=>'#0a0a0a','gold'=>'#a07c00','silver'=>'#666','bronze'=>'#7a4000'];
                        $tierBadgeLabel  = ['title'=>'Title Sponsor','partner'=>'Partner','gold'=>'Gold','silver'=>'Silver','bronze'=>'Bronze'];
                        $t = $sp['tier'] ?? '';
                        if ($t): ?>
                        <span style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:<?= $tierBadgeColors[$t] ?? '#888' ?>;background:<?= $tierBadgeColors[$t] ?? '#888' ?>18;padding:2px 8px;border-radius:20px;">
                            <?= $tierBadgeLabel[$t] ?? ucfirst($t) ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="text-center" style="margin-top:28px;">
            <a href="<?= SITE_URL ?>/sponsors"
               class="theme-btn btn-style-two"
               style="font-size:.85rem;padding:10px 24px;">
                <span class="btn-title">Meet All Our Sponsors &amp; Partners →</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<?php include 'includes/footer.php'; ?>
</div>

<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/welcome-modal.php'; ?>
<?php include 'includes/footer-links.php'; ?>

<script>
function tuqioImgErr(el) {
    el.onerror = null;
    el.parentElement.innerHTML = '<div style="height:100%;min-height:220px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);display:flex;align-items:center;justify-content:center;"><i class="fas fa-calendar-alt" style="font-size:3rem;color:rgba(255,255,255,0.2);"></i></div>';
}
</script>
</body>
</html>
