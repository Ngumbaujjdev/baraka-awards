<?php
include 'config/config.php';
include 'libs/App.php';

$eventsResp  = tuqio_api('/api/public/events');
$allEvents   = $eventsResp['data'] ?? [];
$upcoming    = array_values(array_filter($allEvents, fn($e) => ($e['status'] ?? '') !== 'past'));
usort($upcoming, fn($a, $b) =>
    (!empty($b['banner_image']) || !empty($b['thumbnail_image'])) <=> (!empty($a['banner_image']) || !empty($a['thumbnail_image'])));

$blogResp   = tuqio_api('/api/public/blog');
$blogPosts  = array_slice($blogResp['data'] ?? [], 0, 3);

// Digitally Fit Awards Gala 2026 — schedule + sponsors
$galaResp      = tuqio_api('/api/public/events/dfa-gala-2026');
$galaEvent     = $galaResp['event'] ?? [];          // API returns { event:{...}, schedule_days:[...] }
$scheduleDays  = $galaResp['schedule_days'] ?? [];  // at root, not inside event
$galaSponsors  = $galaResp['sponsors'] ?? [];

// Nominee categories (dynamic — scales to 100+)
$nomResp       = tuqio_api('/api/public/events/dfa-gala-2026/nominees');
$allCategories = $nomResp['categories'] ?? [];
$displayCats   = array_slice($allCategories, 0, 10);  // show first 10 on homepage

// Dynamic CTA phase
$_today             = date('Y-m-d');
$_nominationsOpen   = ($_today >= '2026-04-16' && $_today <= '2026-07-15');
$_votingOpen        = ($_today >= '2026-08-01' && $_today <= '2026-09-14');
$ticketTypes        = $galaResp['ticket_types'] ?? [];
$_ticketsAvailable  = count(array_filter($ticketTypes, fn($t) => !empty($t['is_available']))) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>Home | Digitally Fit Awards</title>
<meta name="description" content="East Africa's premier digital excellence awards. Nominate, vote, and join us at the Digitally Fit Awards Gala 2026.">
<meta name="keywords" content="Digitally Fit Awards, digital excellence awards East Africa, vote digital awards 2026, Digitally Fit Awards Gala, nominate digital achiever">
<meta name="author" content="Digitally Fit Awards">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://dfa.tuqiohub.africa/">

<!-- Schema.org microdata -->
<meta itemprop="name" content="Digitally Fit Awards — Kenya's Premier Event Hub">
<meta itemprop="description" content="Kenya's premier event management platform powering nominations, voting, ticketing, and live events.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="Home | Digitally Fit Awards">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="https://dfa.tuqiohub.africa/">
<meta property="og:description" content="Kenya's premier event management platform powering nominations, voting, ticketing, and live events.">
<meta property="og:site_name" content="Digitally Fit Awards">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@digitallyfitawards">
<meta name="twitter:title" content="Home | Digitally Fit Awards">
<meta name="twitter:description" content="Kenya's premier event management platform powering nominations, voting, ticketing, and live events.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","name":"Digitally Fit Awards","url":"https://dfa.tuqiohub.africa","description":"East Africa's premier digital excellence awards platform.","contactPoint":{"@type":"ContactPoint","telephone":"+254757140682","email":"info@dfa.tuqiohub.africa","contactType":"customer support"},"sameAs":["https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://www.facebook.com/share/p/1DJyLwtvqf/","https://twitter.com/digitallyfitawards","https://www.tiktok.com/@digitallyfitawardske"]}
</script>

<!-- JSON-LD: WebSite -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebSite","name":"Digitally Fit Awards","url":"https://dfa.tuqiohub.africa","description":"Kenya's premier event management platform powering nominations, voting, ticketing, and live events.","potentialAction":{"@type":"SearchAction","target":"https://dfa.tuqiohub.africa/events.php?q={search_term_string}","query-input":"required name=search_term_string"}}
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

            <!-- Slide 1: Digitally Fit Awards 2026 Gala -->

            <div class="slide-item slide-bg-1">

                <div class="auto-container">

                    <div class="content-box">

                        <span class="title animate-1">Digitally Fit Awards 2026 — Nairobi</span>

                        <h2 class="animate-2">Celebrating Digital <br>Excellence in East Africa</h2>

                        <div class="text animate-3">Digitally Fit Awards — recognising outstanding achievements across 300+ digital categories, organised by KEOnline</div>

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

                        <h2 class="animate-2">Nominate a <br>Digital Champion</h2>

                        <div class="text animate-3">Nominations are open. 10 categories celebrating Kenya's digital leaders — nominate now, free of charge</div>

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

                        <div class="text animate-3">Voting opens October 20, 2026. December 5 — Digitally Fit Awards Gala Night, Villa Rosa Kempinski, Nairobi. Get your tickets now</div>

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


<!-- ══ 2. DFA 2026 SPOTLIGHT ════════════════════ -->
<?php
$_galaPhase  = $galaEvent['current_phase'] ?? '';
$_galaBanner = !empty($galaEvent['banner_image']) ? API_STORAGE . $galaEvent['banner_image']
             : (!empty($galaEvent['thumbnail_image']) ? API_STORAGE . $galaEvent['thumbnail_image'] : '');
$_galaDate   = !empty($galaEvent['start_date']) ? date('d M Y', strtotime($galaEvent['start_date'])) : 'December 5, 2026';
$_phaseLabels = ['voting'=>'Voting Open','on_sale'=>'Tickets On Sale','nomination'=>'Nominations Open','upcoming'=>'Coming Soon'];
$_phaseLabel  = $_phaseLabels[$_galaPhase] ?? 'Coming Soon';
?>
<section style="padding:80px 0;background:#fff;">
    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">The Event</span>
            <h2>Digitally Fit Awards Gala 2026</h2>
            <span class="divider"></span>
        </div>
        <div class="row align-items-center" style="margin-top:20px;">
            <!-- Left: Event Banner -->
            <div class="col-lg-6 col-md-12 wow fadeInLeft" style="margin-bottom:30px;">
                <div style="position:relative;border-radius:14px;overflow:hidden;box-shadow:0 12px 40px rgba(5,55,50,0.18);">
                    <?php if ($_galaBanner): ?>
                    <img src="<?= htmlspecialchars($_galaBanner) ?>"
                         alt="Digitally Fit Awards Gala 2026"
                         style="width:100%;height:auto;display:block;"
                         onerror="this.parentElement.style.background='linear-gradient(135deg,#0a0a0a,#1a1a1a)';this.style.display='none'">
                    <?php else: ?>
                    <div style="height:420px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);display:flex;align-items:center;justify-content:center;">
                        <i class="flaticon-trophy-1" style="font-size:5rem;color:rgba(190,155,63,0.5);"></i>
                    </div>
                    <?php endif; ?>
                    <!-- Overlay -->
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(5,55,50,0.75) 0%,transparent 55%);"></div>
                    <!-- Date badge -->
                    <div style="position:absolute;bottom:20px;left:20px;">
                        <div style="background:#be9b3f;color:#fff;font-size:.75rem;font-weight:800;padding:4px 14px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;display:inline-block;"><?= htmlspecialchars($_phaseLabel) ?></div>
                        <div style="color:#fff;font-size:1.1rem;font-weight:700;text-shadow:0 2px 8px rgba(0,0,0,0.5);">
                            <i class="fas fa-calendar-alt" style="color:#be9b3f;margin-right:6px;"></i><?= $_galaDate ?>
                        </div>
                        <div style="color:rgba(255,255,255,0.85);font-size:.9rem;">
                            <i class="fas fa-map-marker-alt" style="color:#be9b3f;margin-right:6px;"></i><?= htmlspecialchars($galaEvent['venue_name'] ?? 'Nairobi, Kenya') ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right: Event Details -->
            <div class="col-lg-6 col-md-12 wow fadeInRight" style="padding-left:40px;margin-bottom:30px;">
                <span class="sub-title" style="font-size:.78rem;color:#be9b3f;font-weight:700;text-transform:uppercase;letter-spacing:2px;">Villa Rosa Kempinski, Nairobi · December 5, 2026</span>
                <h3 style="font-size:2rem;font-weight:800;color:#0d0d0d;margin:10px 0;"><?= htmlspecialchars($galaEvent['name'] ?? 'Digitally Fit Awards Gala 2026') ?></h3>
                <?php if (!empty($galaEvent['tagline'])): ?>
                <p style="font-size:1.05rem;color:#be9b3f;font-style:italic;margin-bottom:14px;"><?= htmlspecialchars($galaEvent['tagline']) ?></p>
                <?php endif; ?>
                <p style="color:#555;line-height:1.8;margin-bottom:20px;"><?= htmlspecialchars(mb_substr($galaEvent['short_description'] ?? 'An extraordinary evening celebrating digital excellence across East Africa — 300+ categories and an unforgettable gala experience.', 0, 220)) ?>...</p>
                <!-- Phase badges -->
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
                    <span style="background:#f0faf8;color:#0d0d0d;border:1px solid #c0e8e0;font-size:.78rem;padding:4px 14px;border-radius:20px;font-weight:600;"><i class="fas fa-trophy" style="color:#be9b3f;margin-right:5px;"></i>9 Award Categories</span>
                    <span style="background:#f0faf8;color:#0d0d0d;border:1px solid #c0e8e0;font-size:.78rem;padding:4px 14px;border-radius:20px;font-weight:600;"><i class="fas fa-map-marker-alt" style="color:#be9b3f;margin-right:5px;"></i>Nairobi, Kenya</span>
                    <?php if (!empty($ticketTypes)): ?>
                    <span style="background:#fdf8ee;color:#7a6020;border:1px solid #e8d8a0;font-size:.78rem;padding:4px 14px;border-radius:20px;font-weight:600;"><i class="fas fa-ticket-alt" style="color:#be9b3f;margin-right:5px;"></i>Tickets Available</span>
                    <?php endif; ?>
                </div>
                <!-- Key dates strip -->
                <div style="margin-bottom:20px;display:flex;flex-direction:column;gap:6px;">
                    <?php
                    $_vI = !empty($galaEvent['voting_is_open']);
                    $_vO = !empty($galaEvent['voting_opens_at'])  ? strtotime($galaEvent['voting_opens_at'])  : 0;
                    $_vC = !empty($galaEvent['voting_closes_at']) ? strtotime($galaEvent['voting_closes_at']) : 0;
                    // Ticket sale start
                    $__nextTkt = null;
                    $__ftk = array_filter($ticketTypes, fn($t)=>!empty($t['sale_starts_at']) && strtotime($t['sale_starts_at'])>time());
                    usort($__ftk, fn($a,$b)=>strtotime($a['sale_starts_at'])<=>strtotime($b['sale_starts_at']));
                    $__nextTkt = array_values($__ftk)[0] ?? null;
                    $__hasTkt  = count(array_filter($ticketTypes, fn($t)=>!empty($t['is_available']))) > 0;
                    ?>
                    <?php if ($__hasTkt): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-ticket-alt" style="color:#be9b3f;width:18px;"></i> <strong>Tickets:</strong> On sale now</div>
                    <?php elseif ($__nextTkt): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-ticket-alt" style="color:#be9b3f;width:18px;"></i> <strong>Tickets on sale:</strong> <?= date('d M Y', strtotime($__nextTkt['sale_starts_at'])) ?></div>
                    <?php endif; ?>
                    <?php if ($_vI): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-vote-yea" style="color:#be9b3f;width:18px;"></i> <strong>Voting:</strong> Open now<?= $_vC ? ' — closes '.date('d M Y',$_vC) : '' ?></div>
                    <?php elseif ($_vO): ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-vote-yea" style="color:#be9b3f;width:18px;"></i> <strong>Voting opens:</strong> <?= date('d M Y', $_vO) ?><?= $_vC ? ' — closes '.date('d M Y',$_vC) : '' ?></div>
                    <?php endif; ?>
                    <?php
                    // Nomination window from categories
                    $_nS=$_nE=null;
                    foreach($allCategories as $_cc){ if(!empty($_cc['nomination_starts_at'])){ $_nS=$_cc['nomination_starts_at']; $_nE=$_cc['nomination_ends_at']??null; break; } }
                    if($_nS):
                    ?>
                    <div style="font-size:.82rem;color:#333;"><i class="fas fa-pen-nib" style="color:#be9b3f;width:18px;"></i> <strong>Nominations:</strong> <?= date('d M Y',strtotime($_nS)) ?><?= $_nE ? ' – '.date('d M Y',strtotime($_nE)) : '' ?></div>
                    <?php endif; ?>
                </div>
                <!-- CTAs -->
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <?php if ($_nominationsOpen): ?>
                    <a href="<?= SITE_URL ?>/nominate" class="theme-btn btn-style-one"><span class="btn-title">Nominate Now</span></a>
                    <?php elseif ($_votingOpen): ?>
                    <a href="<?= SITE_URL ?>/nominees" class="theme-btn btn-style-one" style="background:#BF9E44;border-color:#BF9E44;"><span class="btn-title">Vote Now</span></a>
                    <?php else: ?>
                    <a href="<?= SITE_URL ?>/tickets" class="theme-btn btn-style-one"><span class="btn-title">Get Tickets</span></a>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/event-detail?slug=dfa-gala-2026" class="theme-btn btn-style-two"><span class="btn-title">Full Event Details →</span></a>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- ══ 3. DYNAMIC AWARD CATEGORIES ═══════════════════ -->
<?php
$_totalCats     = count($allCategories);
$_promotedCount = count(array_filter($allCategories, fn($c) => ($c['nomination_status'] ?? '') === 'promoted'));
$_collectingCount = count(array_filter($allCategories, fn($c) => ($c['nomination_status'] ?? '') === 'collecting'));
$_nomStatColors = ['collecting' => '#22c55e', 'promoted' => '#be9b3f', 'closed' => '#ef4444', 'completed' => '#6366f1'];
$_votingIsOpen  = !empty($galaEvent['voting_is_open']);
// Pass all categories to JS as JSON for pagination
$_catsJson = json_encode(array_map(fn($c) => [
    'id'     => $c['id'] ?? '',
    'name'   => $c['name'] ?? '',
    'slug'   => $c['slug'] ?? '',
    'desc'   => $c['description'] ?? '',
    'image'  => !empty($c['image']) ? API_STORAGE . $c['image'] : '',
    'status' => $c['nomination_status'] ?? 'collecting',
    'count'  => count($c['nominees'] ?? []),
], $allCategories));
?>
<section style="padding:80px 0;background:linear-gradient(160deg,#0a0a0a 0%,#0a0a0a 100%);">
    <div class="auto-container">
        <div class="sec-title text-center" style="margin-bottom:32px;">
            <span class="sub-title" style="color:#be9b3f;">Digitally Fit Awards Gala 2026</span>
            <span class="divider"></span>
            <p style="color:rgba(255,255,255,0.7);max-width:580px;margin:0 auto 24px;">
                <?php if ($_promotedCount > 0 && $_collectingCount > 0): ?>
                    <?= $_promotedCount ?> <?= $_promotedCount === 1 ? 'category' : 'categories' ?> now in voting — cast your vote! <?= $_collectingCount ?> still open for nominations.
                <?php elseif ($_promotedCount > 0): ?>
                    All categories are now in voting phase — cast your vote for your favourite artists!
                <?php else: ?>
                    Nominate your favourite digital achievers. <?= $_totalCats ?> categories open for public nomination.
                <?php endif; ?>
            </p>
            <!-- Live search -->
            <div style="max-width:420px;margin:0 auto;position:relative;">
                <input type="text" id="catSearch" placeholder="Search categories…" autocomplete="off"
                       style="width:100%;padding:12px 44px 12px 18px;border:none;border-radius:30px;font-size:.9rem;color:#333;box-shadow:0 4px 20px rgba(0,0,0,.2);outline:none;">
                <i class="fas fa-search" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);color:#aaa;pointer-events:none;"></i>
            </div>
        </div>

        <!-- Category grid — rendered by JS (see script below) -->
        <div class="row" id="catGrid" style="min-height:220px;"></div>

        <!-- No results -->
        <div id="catNoResults" style="display:none;text-align:center;padding:40px 0;">
            <i class="fas fa-search" style="font-size:2.5rem;color:rgba(255,255,255,.2);"></i>
            <p style="color:rgba(255,255,255,.5);margin-top:12px;">No categories match your search.</p>
        </div>

        <!-- Pagination -->
        <div id="catPagination" style="display:flex;justify-content:center;gap:8px;margin-top:28px;flex-wrap:wrap;"></div>

        <!-- Footer CTAs -->
        <div class="text-center" style="margin-top:24px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <?php if ($_promotedCount > 0): ?>
            <a href="<?= SITE_URL ?>/nominees?event=dfa-gala-2026" class="theme-btn btn-style-one" style="background:#BF9E44;border-color:#BF9E44;">
                <span class="btn-title">Vote Now &rarr;</span>
            </a>
            <?php endif; ?>
            <?php if ($_collectingCount > 0): ?>
            <?php if ($_promotedCount > 0): ?>
            <a href="<?= SITE_URL ?>/nominate?event=dfa-gala-2026" class="theme-btn btn-style-two btn-ghost">
                <span class="btn-title">Submit a Nomination</span>
            </a>
            <?php else: ?>
            <a href="<?= SITE_URL ?>/nominate?event=dfa-gala-2026" class="theme-btn btn-style-one" style="background:#BF9E44;border-color:#BF9E44;">
                <span class="btn-title">Submit a Nomination</span>
            </a>
            <?php endif; ?>
            <?php endif; ?>
            <a href="<?= SITE_URL ?>/categories?event=dfa-gala-2026" class="theme-btn btn-style-two btn-ghost">
                <span class="btn-title">Browse All <?= $_totalCats ?> Categories &rarr;</span>
            </a>
        </div>
    </div>
</section>

<script>
(function(){
    var CATS       = <?= $_catsJson ?>;
    var PAGE_SIZE  = 5;
    var nomUrl     = '<?= SITE_URL ?>/nominate?event=dfa-gala-2026';
    var nomineesUrl= '<?= SITE_URL ?>/nominees?event=dfa-gala-2026';
    var isVoting   = <?= $_votingIsOpen ? 'true' : 'false' ?>;
    var filtered   = CATS.slice();
    var currentPage= 1;

    var statusColors = {collecting:'#22c55e', promoted:'#be9b3f', closed:'#ef4444', completed:'#6366f1'};
    var statusLabels = {collecting:'Accepting Nominations', promoted:'Voting Open', closed:'Closed', completed:'Done'};

    function buildCard(cat) {
        var imgHtml = cat.image
            ? '<div style="position:relative;height:120px;overflow:hidden;"><img src="'+cat.image+'" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.parentElement.style.background=\'linear-gradient(135deg,#1a1a1a,#0a0a0a)\';this.style.display=\'none\';"><div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(5,55,50,.65));"></div></div>'
            : '<div style="height:120px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);display:flex;align-items:center;justify-content:center;"><i class="flaticon-trophy-1" style="font-size:2.2rem;color:rgba(190,155,63,.4);"></i></div>';
        var color  = statusColors[cat.status] || '#888';
        var label  = statusLabels[cat.status] || cat.status;
        var isPromoted = cat.status === 'promoted';
        var ctaHtml= isPromoted
            ? '<a href="'+nomineesUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:700;text-decoration:none;">Vote Now \u2192</a>'
            : (!isVoting && cat.status==='collecting')
                ? '<a href="'+nomUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:700;text-decoration:none;">Nominate &rarr;</a>'
                : '<a href="'+nomineesUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:700;text-decoration:none;">View Nominees &rarr;</a>';
        var countTxt = cat.count > 0
            ? '<span style="font-size:.71rem;color:rgba(255,255,255,.45);"><i class="fas fa-users" style="margin-right:3px;"></i>'+cat.count+' nominated</span>'
            : '<span style="font-size:.71rem;color:rgba(255,255,255,.3);">No nominees yet</span>';
        return '<div class="col-lg-4 col-md-6 col-sm-12" style="margin-bottom:20px;">'+
            '<div onclick="openCatModal('+JSON.stringify(cat)+')" style="background:rgba(255,255,255,.05);border:1px solid rgba(190,155,63,.18);border-radius:12px;overflow:hidden;cursor:pointer;height:100%;transition:all .22s;" '+
            'onmouseover="this.style.borderColor=\'rgba(190,155,63,.55)\';this.style.background=\'rgba(190,155,63,.09)\';this.style.transform=\'translateY(-2px)\';" '+
            'onmouseout="this.style.borderColor=\'rgba(190,155,63,.18)\';this.style.background=\'rgba(255,255,255,.05)\';this.style.transform=\'\';">' +
            imgHtml +
            '<div style="padding:14px;">'+
            '<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:7px;">'+
            '<h6 style="color:#fff;font-weight:700;font-size:.88rem;margin:0;line-height:1.35;flex:1;">'+cat.name+'</h6>'+
            '<span style="font-size:.63rem;font-weight:700;background:rgba(0,0,0,.22);color:'+color+';padding:2px 8px;border-radius:20px;white-space:nowrap;margin-left:8px;margin-top:2px;">'+label+'</span>'+
            '</div>'+
            (cat.desc?'<p style="color:rgba(255,255,255,.52);font-size:.77rem;line-height:1.5;margin-bottom:9px;">'+cat.desc.substring(0,80)+(cat.desc.length>80?'\u2026':'')+'</p>':'')+
            '<div style="display:flex;justify-content:space-between;align-items:center;">'+countTxt+ctaHtml+'</div>'+
            '</div></div></div>';
    }

    function renderGrid() {
        var grid = document.getElementById('catGrid');
        var noRes= document.getElementById('catNoResults');
        if (!grid) return;
        if (filtered.length === 0) { grid.innerHTML=''; noRes.style.display='block'; renderPagination(); return; }
        noRes.style.display='none';
        var start=(currentPage-1)*PAGE_SIZE, end=start+PAGE_SIZE;
        grid.innerHTML = filtered.slice(start,end).map(buildCard).join('');
        renderPagination();
    }

    function renderPagination() {
        var pag=document.getElementById('catPagination');
        if (!pag) return;
        var total=Math.ceil(filtered.length/PAGE_SIZE);
        if (total<=1){ pag.innerHTML=''; return; }
        var html='';
        for(var i=1;i<=total;i++){
            var active=i===currentPage;
            html+='<button onclick="goCatPage('+i+')" style="min-width:34px;height:34px;border-radius:8px;border:2px solid '+(active?'#be9b3f':'rgba(190,155,63,.3)')+';background:'+(active?'#be9b3f':'transparent')+';color:'+(active?'#fff':'#be9b3f')+';font-weight:700;font-size:.8rem;cursor:pointer;padding:0 10px;transition:all .18s;">'+i+'</button>';
        }
        pag.innerHTML=html;
    }

    window.goCatPage=function(p){
        currentPage=p;
        renderGrid();
        var g=document.getElementById('catGrid');
        if(g) window.scrollTo({top:g.getBoundingClientRect().top+window.scrollY-80,behavior:'smooth'});
    };

    window.openCatModal=function(cat){
        var color  = statusColors[cat.status]||'#888';
        var label  = statusLabels[cat.status]||cat.status;
        document.getElementById('catModalName').textContent=cat.name;
        document.getElementById('catModalDesc').textContent=cat.desc||'No description available.';
        document.getElementById('catModalBadge').textContent=label;
        document.getElementById('catModalBadge').style.cssText='font-size:.7rem;font-weight:700;padding:3px 12px;border-radius:20px;white-space:nowrap;margin-left:12px;margin-top:3px;background:rgba(0,0,0,.06);color:'+color;
        document.getElementById('catModalCount').textContent=cat.count>0?cat.count+' nominee'+(cat.count!==1?'s':'')+' so far':'No nominees yet';
        var cta=document.getElementById('catModalCta');
        if(cat.status==='promoted'){ cta.href=nomineesUrl+'&category='+cat.slug; cta.querySelector('.btn-title').textContent='Vote Now \u2192'; cta.style.background='#be9b3f'; cta.style.borderColor='#be9b3f'; }
        else if(!isVoting&&cat.status==='collecting'){ cta.href=nomUrl+'&category='+cat.slug; cta.querySelector('.btn-title').textContent='Nominate for this category \u2192'; cta.style.background=''; cta.style.borderColor=''; }
        else { cta.href=nomineesUrl+'&category='+cat.slug; cta.querySelector('.btn-title').textContent='View Nominees \u2192'; cta.style.background=''; cta.style.borderColor=''; }
        var img=document.getElementById('catModalImgEl');
        var icon=document.getElementById('catModalIcon');
        if(cat.image){ img.src=cat.image; img.style.display='block'; icon.style.display='none'; }
        else { img.style.display='none'; icon.style.display='flex'; }
        if(typeof $!=='undefined') $('#catModal').modal('show');
    };

    document.getElementById('catSearch').addEventListener('input',function(){
        var q=this.value.trim().toLowerCase();
        filtered=q?CATS.filter(function(c){return c.name.toLowerCase().indexOf(q)!==-1;}):CATS.slice();
        currentPage=1;
        renderGrid();
    });

    renderGrid();
}());
</script>


<?php if (!empty($ticketTypes)): ?>
<!-- ══ 4b. TICKETS SECTION ═══════════════════════════ -->
<section style="padding:80px 0;background:#fff;">
    <div class="auto-container">
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <span class="sub-title">Digitally Fit Awards Gala 2026 &mdash; Nairobi</span>
            <h2>Secure Your Seat</h2>
            <span class="divider"></span>
            <p style="color:#666;max-width:520px;margin:10px auto 0;">Join us for an extraordinary evening celebrating digital excellence. Choose a ticket that fits your experience.</p>
        </div>
        <div class="row justify-content-center">
        <?php foreach ($ticketTypes as $tkt):
            $tPrice    = (int)($tkt['price'] ?? 0);
            $tAvail    = !empty($tkt['is_available']);
            $tSaleStart= !empty($tkt['sale_starts_at']) ? strtotime($tkt['sale_starts_at']) : 0;
            $tSaleEnd  = !empty($tkt['sale_ends_at'])   ? strtotime($tkt['sale_ends_at'])   : 0;
            $tIsVIP    = stripos($tkt['name'],'vip')!==false || stripos($tkt['name'],'vvip')!==false;
            $tIsTable  = stripos($tkt['name'],'table')!==false;
            $tBorderColor = $tIsVIP ? '#be9b3f' : '#e5e7eb';
            $tBadge    = $tIsVIP ? '<span style="font-size:.65rem;font-weight:800;background:#be9b3f;color:#fff;padding:2px 10px;border-radius:20px;letter-spacing:.5px;position:absolute;top:-1px;left:50%;transform:translateX(-50%);white-space:nowrap;">VIP</span>' : '';
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" style="margin-bottom:28px;">
            <div style="border:2px solid <?= $tBorderColor ?>;border-radius:14px;padding:28px 24px;height:100%;position:relative;text-align:center;<?= $tIsVIP ? 'box-shadow:0 8px 32px rgba(190,155,63,.18);' : '' ?>">
                <?= $tBadge ?>
                <!-- Icon -->
                <div style="width:52px;height:52px;border-radius:12px;background:<?= $tIsVIP ? 'linear-gradient(135deg,#be9b3f,#d4af5a)' : 'linear-gradient(135deg,#0a0a0a,#1a1a1a)' ?>;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-<?= $tIsTable ? 'users' : ($tIsVIP ? 'star' : 'ticket-alt') ?>" style="color:#fff;font-size:1.3rem;"></i>
                </div>
                <h5 style="font-weight:800;color:#0d0d0d;margin-bottom:8px;font-size:1rem;"><?= htmlspecialchars($tkt['name']) ?></h5>
                <!-- Price -->
                <div style="margin-bottom:14px;">
                    <span style="font-size:1.8rem;font-weight:900;color:<?= $tIsVIP ? '#be9b3f' : '#0a0a0a' ?>;">KES <?= number_format($tPrice) ?></span>
                    <?php if ($tIsTable): ?><div style="font-size:.75rem;color:#888;margin-top:2px;">per table (10 seats)</div><?php endif; ?>
                </div>
                <?php if (!empty($tkt['description'])): ?>
                <p style="font-size:.82rem;color:#888;line-height:1.6;margin-bottom:16px;"><?= htmlspecialchars($tkt['description']) ?></p>
                <?php endif; ?>
                <!-- Status / CTA -->
                <?php if ($tAvail): ?>
                <div style="display:inline-flex;align-items:center;gap:6px;font-size:.75rem;color:#16a34a;font-weight:700;background:#f0fdf4;border:1px solid #86efac;padding:3px 12px;border-radius:20px;margin-bottom:16px;">
                    <i class="fas fa-circle" style="font-size:.45rem;"></i>On Sale Now
                </div><br>
                <a href="<?= SITE_URL ?>/tickets" class="theme-btn btn-style-one" style="<?= $tIsVIP ? 'background:#be9b3f;border-color:#be9b3f;' : '' ?>font-size:.82rem;">
                    <span class="btn-title">Buy Ticket &rarr;</span>
                </a>
                <?php elseif ($tSaleStart && $tSaleStart > time()): ?>
                <div style="font-size:.75rem;color:#f59e0b;font-weight:700;background:#fffbeb;border:1px solid #fcd34d;padding:3px 12px;border-radius:20px;margin-bottom:16px;display:inline-block;">
                    <i class="fas fa-clock" style="margin-right:4px;"></i>Sale starts <?= date('d M Y', $tSaleStart) ?>
                </div><br>
                <span style="font-size:.8rem;color:#aaa;font-style:italic;">Not yet on sale</span>
                <?php else: ?>
                <span style="font-size:.8rem;color:#aaa;font-style:italic;">Coming soon</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <div class="text-center" style="margin-top:16px;">
            <a href="<?= SITE_URL ?>/event-detail?slug=dfa-gala-2026" class="theme-btn btn-style-two">
                <span class="btn-title">View Full Ticket Details &rarr;</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ══ 5. HOW DFA WORKS ══════════════════════════════ -->
<section class="feature-section">
    <div class="anim-icons full-width">
        <span class="icon icon-circle-1 wow zoomIn"></span>
        <span class="icon icon-dotted-circle wow zoomIn" data-wow-delay="400ms"></span>
    </div>
    <div class="auto-container">
        <div class="sec-title text-center">
            <span class="sub-title">Simple Steps</span>
            <h2>How Digitally Fit Awards Works</h2>
            <span class="divider"></span>
        </div>
        <div class="row">
            <div class="feature-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
                <div class="inner-box">
                    <div class="icon-box">
                        <div class="icon"><span class="flaticon-calendar-1"></span></div>
                    </div>
                    <h4>1. Browse the Nominees</h4>
                    <p>Explore all categories and the talented artists shortlisted for Digitally Fit Awards 2026. Find your favourites across 9 award categories.</p>
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
                    <p>Join us at the Digitally Fit Awards Gala on December 5, 2026 at the Villa Rosa Kempinski Nairobi — a landmark celebration of innovation, creativity, and digital excellence in East Africa.</p>
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
            <span class="sub-title">December 5, 2026 &mdash; Villa Rosa Kempinski, Nairobi</span>
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
            <h2>DFA News &amp; Updates</h2>
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
                                <img src="<?= SITE_URL ?>/assets/images/logo/dfa-logo.svg" alt="Digitally Fit Awards" style="background:#be9b3f; object-fit:contain; border-radius:50%; padding:2px;">
                            </figure>
                            <h5 class="name"><?= htmlspecialchars($post['author']['name'] ?? 'Digitally Fit Awards') ?></h5>
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
            <span class="sub-title">Digitally Fit Awards Gala 2026</span>
            <h2>Sponsors &amp; Partners</h2>
            <span class="divider"></span>
            <p style="color:#777;max-width:520px;margin:10px auto 0;">We are grateful to the organisations making Digitally Fit Awards 2026 possible.</p>
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
