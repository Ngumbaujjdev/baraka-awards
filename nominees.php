<?php
include 'config/config.php';
include 'libs/App.php';

// ── Fetch all Baraka events for switcher ───────────────────
$_barakaEvsList = tuqio_api('/api/public/events?client=baraka-awards');
$_allDfaEvs  = $_barakaEvsList['data'] ?? [];
usort($_allDfaEvs, fn($a,$b) => strcmp($a['start_date'] ?? '9999-12-31', $b['start_date'] ?? '9999-12-31'));
// Default: first event with banner_image, else latest
$_defaultNomEv = null;
foreach ($_allDfaEvs as $_e) {
    if (!$_defaultNomEv && !empty($_e['banner_image'])) { $_defaultNomEv = $_e; }
}
if (!$_defaultNomEv && !empty($_allDfaEvs)) { $_defaultNomEv = end($_allDfaEvs); }

// ── Resolve which event to show ─────────────────────────
$activeSlug = $_GET['event'] ?? ($_defaultNomEv['slug'] ?? '');

$response   = $activeSlug ? tuqio_api('/api/public/events/' . $activeSlug . '/nominees') : [];
$event      = $response['event'] ?? [];
$categories = $response['categories'] ?? [];

$now          = time();
$votingCloses = !empty($event['voting_closes_at']) ? strtotime($event['voting_closes_at']) : 0;
$isVotingOpen = !empty($event['voting_is_open']); // use backend-calculated flag
$voteUrl      = API_BASE . '/events/' . ($event['slug'] ?? $activeSlug);
$eventName    = $event['name'] ?? 'Baraka Awards Kenya';

$voteBundleUrl = SITE_URL . '/vote-bundle.php?event=' . urlencode($activeSlug ?: '');

// Build grouped sidebar structure
$sidebarGroups = [];
if (!empty($response['groups'])) {
    foreach ($response['groups'] as $grp) {
        $sidebarGroups[$grp['name'] ?? 'Other'] = $grp['categories'] ?? [];
    }
}
if (!empty($response['ungrouped'])) {
    $sidebarGroups['Other'] = $response['ungrouped'];
}
// Fallback: no groups — show flat list
if (empty($sidebarGroups) && !empty($categories)) {
    $sidebarGroups['__root__'] = $categories;
}

$initialsColors = ['#be9b3f', '#0a0a0a', '#1a1a1a', '#6c757d'];
$globalIdx = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>Nominees &amp; Finalists | <?= htmlspecialchars($eventName) ?> | Baraka Awards Kenya</title>
<meta name="description" content="Meet the nominees and finalists for <?= htmlspecialchars($eventName) ?>. Browse all categories and vote for your favourites on Baraka Awards Kenya.">
<meta name="keywords" content="nominees Kenya, finalists awards Kenya, vote nominees, <?= htmlspecialchars($eventName) ?>, Baraka Awards Kenya voting">
<meta name="author" content="Baraka Awards Kenya">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= SITE_URL ?>/nominees.php">

<!-- Schema.org microdata -->
<meta itemprop="name" content="Nominees &amp; Finalists | <?= htmlspecialchars($eventName) ?>">
<meta itemprop="description" content="Meet the nominees and finalists for <?= htmlspecialchars($eventName) ?>. Vote for your favourites on Baraka Awards Kenya.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="Nominees &amp; Finalists | <?= htmlspecialchars($eventName) ?>">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= SITE_URL ?>/nominees.php">
<meta property="og:description" content="Meet the nominees and finalists for <?= htmlspecialchars($eventName) ?>. Vote on Baraka Awards Kenya.">
<meta property="og:site_name" content="Baraka Awards Kenya">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@barakaawards">
<meta name="twitter:title" content="Nominees &amp; Finalists | <?= htmlspecialchars($eventName) ?>">
<meta name="twitter:description" content="Meet the nominees and finalists for <?= htmlspecialchars($eventName) ?>. Vote on Baraka Awards Kenya.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>","description":"Kenya's premier entertainment and cultural awards platform — organised by the Baraka Awards Kenya team.","contactPoint":{"@type":"ContactPoint","telephone":"+254710388288","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support"},"sameAs":["https://www.facebook.com/share/p/1DJyLwtvqf/","https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://twitter.com/barakaawards","https://www.tiktok.com/@barakaawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?= SITE_URL ?>/"},{"@type":"ListItem","position":2,"name":"Nominees","item":"<?= SITE_URL ?>/nominees.php"}]}
</script>

<!-- JSON-LD: WebPage -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Nominees & Finalists | Baraka Awards Kenya","url":"<?= SITE_URL ?>/nominees.php","description":"Meet the nominees and finalists. Vote for your favourites on Baraka Awards Kenya."}
</script>
<link href="<?= SITE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/responsive.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/custom.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/nominees.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/svg+xml" href="<?= SITE_URL ?>/assets/images/favicon/favicon.svg">
<link rel="shortcut icon" href="<?= SITE_URL ?>/assets/images/favicon/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_URL ?>/assets/images/favicon/apple-touch-icon.png">
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
<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/slides/kenya-breadcrump.webp);">
    <div class="anim-icons full-width">
        <span class="icon icon-bull-eye"></span>
        <span class="icon icon-dotted-circle"></span>
    </div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>Nominees &amp; Finalists</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>Nominees</li>
            </ul>
        </div>
    </div>
</section>

<!-- Content -->
<div class="sidebar-page-container">
    <div class="auto-container">
        <div class="row clearfix">

            <!-- Content Side -->
            <div class="content-side col-lg-9 col-md-12 col-sm-12">

                <?php if (empty($categories)): ?>
                <div class="text-center" style="padding:80px 0;">
                    <i class="fas fa-users" style="font-size:3rem;color:#be9b3f;opacity:.3;"></i>
                    <h4 style="margin-top:20px;">Nominees Announced Soon</h4>
                    <p class="text-muted">Finalists for <?= htmlspecialchars($eventName) ?> have not been published yet. Check back soon.</p>
                </div>
                <?php else: ?>

                <!-- Event switcher pills (AJAX — no full page reload) -->
                <?php if (count($_allDfaEvs) > 1): ?>
                <div id="nomEventPills" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:22px;">
                    <?php foreach ($_allDfaEvs as $_ev): $isAct = ($_ev['slug'] ?? '') === $activeSlug; ?>
                    <button type="button"
                            class="nom-event-pill"
                            data-slug="<?= htmlspecialchars($_ev['slug'] ?? '', ENT_QUOTES) ?>"
                            data-active="<?= $isAct ? '1' : '0' ?>"
                            onclick="switchNomEvent(this.dataset.slug)">
                        <?= htmlspecialchars($_ev['name'] ?? '') ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Nominee Filter Row: Search + Group + Category -->
                <div class="nom-filter-row">
                    <div class="nom-search-wrap">
                        <input type="text" id="nom-search" placeholder="Search nominees by name..." autocomplete="off">
                        <i class="fas fa-search search-icon" id="nom-search-icon"></i>
                        <i class="fas fa-times search-clear" id="nom-search-clear"></i>
                    </div>
                    <?php if (count($sidebarGroups) > 1 || !isset($sidebarGroups['__root__'])): ?>
                    <div class="nom-group-wrap">
                        <select id="nom-group-select">
                            <option value="">All Groups</option>
                            <?php foreach ($sidebarGroups as $gName => $gCats): if ($gName === '__root__') continue; ?>
                            <option value="<?= htmlspecialchars($gName) ?>"><?= htmlspecialchars($gName) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="nom-cat-wrap">
                        <select id="nom-cat-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['slug']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div id="nom-no-results">
                    <i class="fas fa-search" style="font-size:2.5rem;opacity:.2;display:block;margin-bottom:12px;"></i>
                    No nominees found matching your search.
                </div>

                <div class="tab-content" id="nomineeTabContent">
                    <?php foreach ($categories as $catIndex => $category):
                        $candidates = $category['candidates'] ?? $category['nominees'] ?? [];
                        $totalCount = (int)($category['total_count'] ?? count($candidates));
                        $lastPage   = (int)($category['last_page'] ?? 1);
                        $maxVotes   = max(1, array_reduce($candidates, fn($c,$n) => max($c, $n['votes_count'] ?? $n['total_votes'] ?? 0), 0));
                    ?>
                    <div class="tab-pane fade <?= $catIndex === 0 ? 'show active' : '' ?>"
                         id="cat-pane-<?= htmlspecialchars($category['slug']) ?>"
                         role="tabpanel"
                         data-cat-slug="<?= htmlspecialchars($category['slug']) ?>"
                         data-cat-name="<?= htmlspecialchars($category['name']) ?>"
                         data-last-page="<?= $lastPage ?>"
                         data-current-page="1">

                        <div style="margin-bottom:28px;">
                            <h3 style="color:#0a0a0a;font-weight:700;font-size:1.4rem;margin-bottom:6px;">
                                <?= htmlspecialchars($category['name']) ?>
                            </h3>
                            <?php if (!empty($category['description'])): ?>
                            <p style="color:#777;font-size:.9rem;margin:0;"><?= htmlspecialchars($category['description']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="nominees-grid">
                        <?php if (empty($candidates)): ?>
                        <div class="text-center" style="padding:50px 0;color:#aaa;">
                            <i class="fas fa-hourglass-half" style="font-size:2rem;margin-bottom:10px;"></i>
                            <p>Nominees for this category will be announced soon.</p>
                        </div>
                        <?php else: ?>
                        <div class="row">
                            <?php foreach ($candidates as $candidate):
                                $votes   = (int)($candidate['votes_count'] ?? $candidate['total_votes'] ?? 0);
                                $pct     = min(100, round($votes / $maxVotes * 100));
                                $cImage  = $candidate['image'] ?? '';
                                $cName   = $candidate['name'] ?? '';
                                $words   = array_filter(explode(' ', trim($cName)), fn($w) => $w !== '' && preg_match('/^[a-zA-Z]/u', $w));
                                $initials= implode('', array_map(fn($w) => strtoupper($w[0] ?? ''), array_slice(array_values($words), 0, 2)));
                                $color   = $initialsColors[$globalIdx % 4];
                                $globalIdx++;
                            ?>
                            <div class="col-md-4 col-sm-6 col-12" style="margin-bottom:30px;">
                                <div class="nominee-card"
                                     data-name="<?= htmlspecialchars($cName) ?>"
                                     data-title="<?= htmlspecialchars($candidate['title'] ?? $candidate['subtitle'] ?? '') ?>"
                                     data-desc="<?= htmlspecialchars($candidate['description'] ?? '') ?>"
                                     data-image="<?= htmlspecialchars($cImage ?: '') ?>"
                                     data-initials="<?= htmlspecialchars($initials) ?>"
                                     data-color="<?= $color ?>"
                                     data-votes="<?= $votes ?>"
                                     data-pct="<?= $pct ?>"
                                     data-candidate-id="<?= (int)($candidate['id'] ?? 0) ?>"
                                     data-slug="<?= htmlspecialchars($candidate['slug'] ?? '') ?>"
                                     data-category="<?= htmlspecialchars($category['slug'] ?? '') ?>"
                                     data-category-name="<?= htmlspecialchars($category['name'] ?? '') ?>"
                                     data-is-winner="<?= !empty($candidate['is_winner']) ? '1' : '0' ?>"
                                     data-winner-pos="<?= htmlspecialchars($candidate['winner_position'] ?? '') ?>"
                                     data-code="<?= htmlspecialchars($candidate['code'] ?? '') ?>"
                                     data-video="<?= htmlspecialchars($candidate['video_url'] ?? '') ?>"
                                     data-socials="<?= htmlspecialchars(json_encode($candidate['social_links'] ?? [])) ?>"
                                     data-contact-email="<?= htmlspecialchars($candidate['contact_email'] ?? '') ?>"
                                     data-contact-phone="<?= htmlspecialchars($candidate['contact_phone'] ?? '') ?>">

                                    <?php if (!empty($candidate['is_winner'])): ?>
                                    <div class="winner-badge"><i class="fas fa-crown" style="margin-right:4px;"></i>Winner</div>
                                    <?php endif; ?>
                                    <?php if ($cImage): ?>
                                    <img src="<?= htmlspecialchars($cImage) ?>"
                                         alt="<?= htmlspecialchars($cName) ?>"
                                         class="nominee-photo"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div class="nominee-initials" style="display:none;background:<?= $color ?>;"><?= $initials ?></div>
                                    <?php else: ?>
                                    <div class="nominee-initials" style="background:<?= $color ?>;"><?= $initials ?></div>
                                    <?php endif; ?>

                                    <h4 class="nominee-name"><?= htmlspecialchars($cName) ?></h4>
                                    <?php if (!empty($candidate['subtitle'])): ?>
                                    <div class="nominee-title"><?= htmlspecialchars($candidate['subtitle']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($candidate['description'])): ?>
                                    <div class="nominee-location">
                                        <i class="fa fa-info-circle"></i>
                                        <?= htmlspecialchars(mb_strimwidth($candidate['description'], 0, 60, '…')) ?>
                                    </div>
                                    <?php endif; ?>

                                    <div class="vote-bar-container">
                                        <div class="vote-bar-fill" style="width:<?= $pct ?>%;"
                                             data-candidate-id="<?= (int)($candidate['id'] ?? 0) ?>-bar"></div>
                                    </div>
                                    <div class="vote-stats">
                                        <span>Votes</span>
                                        <span class="vote-count" data-candidate-id="<?= (int)($candidate['id'] ?? 0) ?>">
                                            <?= number_format($votes) ?>
                                        </span>
                                    </div>

                                    <div class="nominee-card-footer">
                                        <?php if ($isVotingOpen): ?>
                                        <a class="theme-btn btn-style-one"
                                           style="font-size:.8rem;padding:8px 20px;display:inline-block;"
                                           href="<?= htmlspecialchars($voteBundleUrl . '&nominee=' . (int)($candidate['id'] ?? 0)) ?>"
                                           onclick="event.stopPropagation();">
                                            <span class="btn-title"><i class="fas fa-vote-yea me-1"></i> Vote Now</span>
                                        </a>
                                        <?php else: ?>
                                        <span style="font-size:.8rem;color:#aaa;font-style:italic;">Voting not open</span>
                                        <?php endif; ?>
                                        <a href="<?= SITE_URL ?>/nominee?slug=<?= urlencode($candidate['slug'] ?? '') ?>&event=<?= urlencode($activeSlug) ?>"
                                           onclick="event.stopPropagation();"
                                           style="font-size:.76rem;color:#be9b3f;font-weight:600;text-decoration:none;display:block;text-align:center;margin-top:6px;">
                                            View Profile &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        </div><!-- /.nominees-grid -->

                        <div class="nominees-pagination"></div>

                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>
            <!-- End Content Side -->

            <!-- Sidebar -->
            <div class="sidebar-side col-lg-3 col-md-12 col-sm-12">
                <aside class="sidebar padding-left">

                    <!-- Category filter — grouped -->
                    <?php if (!empty($sidebarGroups)): ?>
                    <div class="sidebar-widget" style="margin-bottom:30px;">
                        <h5 class="sidebar-title">Categories</h5>
                        <div class="widget-content">
                            <?php
                            $SIDEBAR_GROUPS_VISIBLE = 5;
                            $totalSidebarGroups = count(array_filter(array_keys($sidebarGroups), fn($k) => $k !== '__root__'));
                            $hasMoreGroups = $totalSidebarGroups > $SIDEBAR_GROUPS_VISIBLE;
                            ?>
                            <nav id="catSidebarNav" class="nav flex-column cat-nav" role="tablist">
                                <?php
                                $sbIdx      = 0;
                                $grpOpenIdx = 0;
                                foreach ($sidebarGroups as $groupName => $groupCats):
                                    $isFirst  = $grpOpenIdx === 0;
                                    $hasLabel = $groupName !== '__root__' && count($sidebarGroups) > 1;
                                    $isHidden = $hasLabel && $grpOpenIdx >= $SIDEBAR_GROUPS_VISIBLE;
                                    $grpOpenIdx++;
                                    if ($hasLabel):
                                ?>
                                <details class="cat-group-details <?= $isHidden ? 'sidebar-group-hidden' : '' ?>" <?= $isFirst ? 'open' : '' ?>>
                                    <summary class="cat-group-summary">
                                        <?= htmlspecialchars($groupName) ?>
                                        <i class="fas fa-chevron-down cat-group-chevron"></i>
                                    </summary>
                                    <div class="cat-group-body">
                                <?php endif; ?>
                                <?php foreach ($groupCats as $cat):
                                    $cCount   = (int)($cat['total_count'] ?? count($cat['nominees'] ?? []));
                                    $catSlug  = htmlspecialchars($cat['slug'] ?? '');
                                    $isActive = ($sbIdx === 0);
                                    $sbIdx++;
                                ?>
                                <button class="nav-link <?= $isActive ? 'active' : '' ?>"
                                        id="cat-tab-<?= $catSlug ?>"
                                        data-toggle="pill"
                                        data-target="#cat-pane-<?= $catSlug ?>"
                                        type="button" role="tab">
                                    <?= htmlspecialchars($cat['name'] ?? '') ?>
                                    <span class="count-badge"><?= $cCount ?></span>
                                </button>
                                <?php endforeach; ?>
                                <?php if ($hasLabel): ?>
                                    </div>
                                </details>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </nav>
                            <?php if ($hasMoreGroups): ?>
                            <?php $hiddenCount = $totalSidebarGroups - $SIDEBAR_GROUPS_VISIBLE; $firstBatch = min($hiddenCount, 5); ?>
                            <button id="sidebarShowMore" onclick="sidebarShowAllGroups(this)" style="
                                width:100%; margin-top:10px; padding:9px 0; background:none;
                                border:1px dashed rgba(190,155,63,.4); border-radius:8px;
                                color:#be9b3f; font-size:.8rem; font-weight:700; cursor:pointer;
                                letter-spacing:.4px; transition:all .2s;">
                                <i class="fas fa-chevron-down" style="margin-right:5px;font-size:.7rem;"></i>
                                Show <?= $firstBatch ?> More <span style="opacity:.55;font-weight:500;">(<?= $hiddenCount ?> remaining)</span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Countdown -->
                    <?php if (!empty($event['has_voting'])): ?>
                    <div class="countdown-widget">
                        <?php if ($isVotingOpen && $votingCloses): ?>
                        <h6><i class="fas fa-vote-yea me-1"></i> Voting Closes In</h6>
                        <div class="countdown-boxes" id="countdown-boxes">
                            <div class="countdown-box"><span class="num" id="cd-days">--</span><span class="lbl">Days</span></div>
                            <div class="countdown-box"><span class="num" id="cd-hours">--</span><span class="lbl">Hrs</span></div>
                            <div class="countdown-box"><span class="num" id="cd-mins">--</span><span class="lbl">Min</span></div>
                            <div class="countdown-box"><span class="num" id="cd-secs">--</span><span class="lbl">Sec</span></div>
                        </div>
                        <div style="margin-top:16px;">
                            <a href="<?= htmlspecialchars($voteBundleUrl) ?>"
                               class="theme-btn btn-style-two"
                               style="background:#fff;color:#be9b3f;border-color:#fff;font-size:.82rem;width:100%;text-align:center;display:block;border:2px solid #fff;">
                                <span class="btn-title"><i class="fas fa-vote-yea me-1"></i> Cast Your Votes</span>
                            </a>
                        </div>
                        <?php elseif ($votingCloses && $now > $votingCloses): ?>
                        <h6>Voting Status</h6>
                        <p class="countdown-closed"><i class="fas fa-check-circle me-1"></i> Voting has closed. Results will be announced at the event.</p>
                        <?php else: ?>
                        <h6>Public Voting</h6>
                        <p class="countdown-closed">Voting will be announced soon. Stay tuned!</p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Explore links -->
                    <div class="sidebar-widget">
                        <h5 class="sidebar-title">Explore</h5>
                        <div class="widget-content">
                            <ul style="list-style:none;padding:0;margin:0;">
                                <li style="border-bottom:1px solid #f0f0f0;">
                                    <a href="<?= SITE_URL ?>/events" style="display:flex;align-items:center;gap:10px;padding:10px 0;color:#333;text-decoration:none;font-size:.9rem;font-weight:500;" onmouseover="this.style.color='#be9b3f'" onmouseout="this.style.color='#333'">
                                        <i class="fa fa-calendar-alt" style="color:#be9b3f;width:18px;text-align:center;flex-shrink:0;"></i>
                                        All Events
                                    </a>
                                </li>
                                <li style="border-bottom:1px solid #f0f0f0;">
                                    <a href="<?= SITE_URL ?>/polls" style="display:flex;align-items:center;gap:10px;padding:10px 0;color:#333;text-decoration:none;font-size:.9rem;font-weight:500;" onmouseover="this.style.color='#be9b3f'" onmouseout="this.style.color='#333'">
                                        <i class="fa fa-poll" style="color:#be9b3f;width:18px;text-align:center;flex-shrink:0;"></i>
                                        Live Polls
                                    </a>
                                </li>
                                <li style="border-bottom:1px solid #f0f0f0;">
                                    <a href="<?= SITE_URL ?>/gallery" style="display:flex;align-items:center;gap:10px;padding:10px 0;color:#333;text-decoration:none;font-size:.9rem;font-weight:500;" onmouseover="this.style.color='#be9b3f'" onmouseout="this.style.color='#333'">
                                        <i class="fa fa-images" style="color:#be9b3f;width:18px;text-align:center;flex-shrink:0;"></i>
                                        Gallery
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= SITE_URL ?>/blog" style="display:flex;align-items:center;gap:10px;padding:10px 0;color:#333;text-decoration:none;font-size:.9rem;font-weight:500;" onmouseover="this.style.color='#be9b3f'" onmouseout="this.style.color='#333'">
                                        <i class="fa fa-newspaper" style="color:#be9b3f;width:18px;text-align:center;flex-shrink:0;"></i>
                                        Blog
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </aside>
            </div>
            <!-- End Sidebar -->

        </div>
    </div>
</div>

<!-- Nominee Modal — custom overlay (no Bootstrap dependency) -->
<div id="nomineeModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;z-index:99999;background:rgba(0,0,0,.55);align-items:center;justify-content:center;padding:16px;">
    <div id="nom-modal-box" style="background:#fff;border-radius:14px;width:100%;max-width:540px;max-height:90vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 12px 48px rgba(0,0,0,.28);position:relative;">

        <!-- Header -->
        <div style="background:linear-gradient(135deg,#0a0a0a 0%,#1a1a1a 100%);padding:18px 22px;flex-shrink:0;display:flex;align-items:flex-start;justify-content:space-between;">
            <div>
                <span id="modal-category" style="font-size:.7rem;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.8px;font-weight:700;"></span>
                <div id="modal-name" style="margin:3px 0 0;font-weight:800;color:#fff;line-height:1.2;font-size:1.15rem;"></div>
            </div>
            <button id="nom-modal-close" type="button" style="background:none;border:none;color:#fff;opacity:.8;font-size:1.8rem;font-weight:300;line-height:1;cursor:pointer;padding:0 0 0 18px;flex-shrink:0;">&times;</button>
        </div>

        <!-- Scrollable body -->
        <div style="padding:24px 22px;overflow-y:auto;flex:1;background:#fff;">

            <!-- Avatar + info row -->
            <div style="display:flex;align-items:flex-start;gap:18px;margin-bottom:18px;">
                <div style="flex-shrink:0;">
                    <img id="modal-photo" src="" alt="" style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid #ede9f6;display:none;">
                    <div id="modal-initials" style="width:88px;height:88px;border-radius:50%;color:#fff;font-size:1.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;"></div>
                </div>
                <div style="flex:1;min-width:0;padding-top:4px;">
                    <div id="modal-winner-badge" style="display:none;font-size:.72rem;background:#f59e0b;color:#fff;padding:3px 10px;border-radius:20px;font-weight:700;margin-bottom:6px;">
                        <i class="fas fa-trophy"></i> <span id="modal-winner-label">Winner</span>
                    </div>
                    <div id="modal-title" style="font-size:.9rem;color:#be9b3f;font-weight:700;margin-bottom:6px;"></div>
                    <div id="modal-code-wrap" style="display:none;margin-bottom:6px;">
                        <span style="font-size:.7rem;color:#999;text-transform:uppercase;letter-spacing:.5px;">Code</span><br>
                        <span id="modal-code" style="font-family:monospace;font-size:.9rem;font-weight:700;color:#0a0a0a;background:#f0eeff;padding:2px 8px;border-radius:5px;"></span>
                    </div>
                    <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(5,55,50,.06);padding:5px 12px;border-radius:20px;">
                        <i class="fas fa-poll" style="color:#0a0a0a;font-size:.8rem;"></i>
                        <span id="modal-vote-count" style="font-size:.82rem;font-weight:700;color:#0a0a0a;"></span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div id="modal-desc-wrap" style="display:none;background:#f9fafb;border-radius:8px;padding:14px;margin-bottom:14px;">
                <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;font-weight:700;margin:0 0 6px;">About</p>
                <div id="modal-desc-text" style="font-size:.88rem;color:#444;line-height:1.65;margin:0;"></div>
            </div>

            <!-- Contact -->
            <div id="modal-contact-wrap" style="display:none;margin-bottom:14px;">
                <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;font-weight:700;margin:0 0 8px;">Contact</p>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div id="modal-email-row" style="display:none;align-items:center;gap:8px;">
                        <i class="fas fa-envelope" style="color:#0a0a0a;font-size:.8rem;width:16px;text-align:center;"></i>
                        <a id="modal-email" href="#" style="font-size:.85rem;color:#0a0a0a;text-decoration:none;"></a>
                    </div>
                    <div id="modal-phone-row" style="display:none;align-items:center;gap:8px;">
                        <i class="fas fa-phone" style="color:#0a0a0a;font-size:.8rem;width:16px;text-align:center;"></i>
                        <span id="modal-phone" style="font-size:.85rem;color:#444;"></span>
                    </div>
                </div>
            </div>

            <!-- Video -->
            <div id="modal-video-wrap" style="display:none;margin-bottom:14px;">
                <a id="modal-video" href="#" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:8px;font-size:.85rem;color:#be9b3f;font-weight:600;text-decoration:none;">
                    <i class="fas fa-play-circle" style="font-size:1rem;"></i> Watch Video
                </a>
            </div>

            <!-- Socials -->
            <div id="modal-socials-wrap" style="display:none;">
                <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;font-weight:700;margin:0 0 8px;">Social</p>
                <div id="modal-socials" style="display:flex;gap:10px;flex-wrap:wrap;"></div>
            </div>

        </div><!-- /body -->

        <!-- Footer -->
        <div style="padding:14px 22px;background:#f9fafb;border-top:1px solid #f0f0f0;flex-shrink:0;">
            <?php if ($isVotingOpen): ?>
            <a href="#" id="modal-vote-btn" class="theme-btn btn-style-one" style="font-size:.85rem;padding:10px 24px;width:100%;text-align:center;display:block;">
                <span class="btn-title"><i class="fas fa-vote-yea me-1"></i> Vote for this Nominee</span>
            </a>
            <?php else: ?>
            <div style="color:#aaa;font-style:italic;font-size:.88rem;text-align:center;">Voting is not currently open.</div>
            <?php endif; ?>
            <a id="modal-profile-link" href="#"
               style="font-size:.82rem;color:#be9b3f;font-weight:600;text-decoration:none;display:block;text-align:center;margin-top:10px;">
                View Full Profile &rarr;
            </a>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
</div>

<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php
$_grpKeysInline = array_keys($sidebarGroups);
$_grpValsInline = array_map(fn($cats) => array_column($cats, 'slug'), array_values($sidebarGroups));
$_nomConfigJson = htmlspecialchars(json_encode([
    'group_cats'      => array_combine($_grpKeysInline, $_grpValsInline) ?: (object)[],
    'all_cat_opts'    => array_values(array_map(fn($c) => ['slug' => $c['slug'] ?? '', 'name' => $c['name'] ?? ''], $categories)),
    'is_voting_open'  => $isVotingOpen,
    'vote_bundle_url' => $voteBundleUrl,
    'event_slug'      => $activeSlug,
]), ENT_QUOTES, 'UTF-8');
?>
<!-- Nominee page data — in HTML attr so it's safe from script-tag nesting issues -->
<div id="nom-page-data" data-config="<?= $_nomConfigJson ?>" style="display:none;"></div>

<script>
(function () {
    'use strict';

    var API_BASE   = '<?= API_BASE ?>';
    var EVENT_SLUG = <?= json_encode($activeSlug ?: '') ?>;
    var IS_VOTING  = <?= $isVotingOpen ? 'true' : 'false' ?>;


    // ── Countdown ─────────────────────────────────────────────────────────────
    var votingClosesAt = <?= $votingCloses ?: 'null' ?>;
    function updateCountdown() {
        if (!votingClosesAt) return;
        var remaining = votingClosesAt - Math.floor(Date.now() / 1000);
        if (remaining <= 0) {
            var boxes = document.getElementById('countdown-boxes');
            if (boxes) boxes.innerHTML = '<span style="font-size:.9rem;opacity:.85;">Voting has closed.</span>';
            return;
        }
        var d = Math.floor(remaining / 86400);
        var h = Math.floor((remaining % 86400) / 3600);
        var m = Math.floor((remaining % 3600) / 60);
        var s = remaining % 60;
        var el;
        if ((el = document.getElementById('cd-days')))  el.textContent = String(d).padStart(2,'0');
        if ((el = document.getElementById('cd-hours'))) el.textContent = String(h).padStart(2,'0');
        if ((el = document.getElementById('cd-mins')))  el.textContent = String(m).padStart(2,'0');
        if ((el = document.getElementById('cd-secs')))  el.textContent = String(s).padStart(2,'0');
    }
    if (votingClosesAt) { updateCountdown(); setInterval(updateCountdown, 1000); }

    // ── Animated number counter helper ────────────────────────────────────────
    function animateCount(el, fromVal, toVal, duration) {
        if (fromVal === toVal) return;
        var start = null;
        el.classList.add('vote-updated');
        setTimeout(function(){ el.classList.remove('vote-updated'); }, 1000);
        function step(ts) {
            if (!start) start = ts;
            var prog = Math.min((ts - start) / duration, 1);
            var ease = 1 - Math.pow(1 - prog, 3);
            el.textContent = Math.round(fromVal + (toVal - fromVal) * ease).toLocaleString();
            if (prog < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    // ── Vote velocity tracking ────────────────────────────────────────────────
    var prevVotes = {};  // {candidate_id: {count, timestamp}}
    function getVelocity(id, newCount) {
        var now = Date.now() / 1000;
        var prev = prevVotes[id];
        if (!prev) { prevVotes[id] = { count: newCount, timestamp: now }; return 0; }
        var elapsed = now - prev.timestamp;
        if (elapsed < 5) return 0;  // too soon
        var perMin = Math.round((newCount - prev.count) / elapsed * 60);
        prevVotes[id] = { count: newCount, timestamp: now };
        return Math.max(0, perMin);
    }

    // ── Live vote count polling ───────────────────────────────────────────────
    var lastCounts = {};
    function refreshVoteCounts() {
        if (!EVENT_SLUG) return;
        fetch(API_BASE + '/api/public/events/' + encodeURIComponent(EVENT_SLUG) + '/vote-counts')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.categories) return;
                data.categories.forEach(function(cat) {
                    var maxV = 1;
                    cat.nominees.forEach(function(c) { if (c.votes_count > maxV) maxV = c.votes_count; });
                    cat.nominees.forEach(function(c) {
                        var pct     = Math.min(100, Math.round(c.votes_count / maxV * 100));
                        var prevVal = lastCounts[c.id] || 0;
                        lastCounts[c.id] = c.votes_count;

                        // Animate count
                        var cEl = document.querySelector('.vote-count[data-candidate-id="' + c.id + '"]');
                        if (cEl) {
                            if (c.votes_count !== prevVal) {
                                animateCount(cEl, prevVal, c.votes_count, 800);
                            } else {
                                cEl.textContent = c.votes_count.toLocaleString();
                            }
                        }

                        // Animate bar
                        var bEl = document.querySelector('.vote-bar-fill[data-candidate-id="' + c.id + '-bar"]');
                        if (bEl) bEl.style.width = pct + '%';

                        // Velocity badge
                        var vel = getVelocity(c.id, c.votes_count);
                        var vbEl = document.getElementById('vel-' + c.id);
                        if (vel > 0) {
                            if (!vbEl) {
                                var statsRow = document.querySelector('.vote-stats .vote-count[data-candidate-id="' + c.id + '"]');
                                if (statsRow) {
                                    vbEl = document.createElement('span');
                                    vbEl.id = 'vel-' + c.id;
                                    vbEl.className = 'velocity-badge';
                                    statsRow.parentNode.appendChild(vbEl);
                                }
                            }
                            if (vbEl) vbEl.innerHTML = '🔥 +' + vel + '/min';
                        }

                        // Update card data-votes for modal
                        var card = document.querySelector('.nominee-card[data-candidate-id="' + c.id + '"]');
                        if (card) {
                            card.dataset.votes = c.votes_count;
                            card.dataset.pct   = pct;
                        }
                    });
                });
            })
            .catch(function() {});
    }
    setInterval(refreshVoteCounts, 8000);

}());

// ── Pagination + AJAX card loading ────────────────────────────────────────
(function ($) {
    var API_BASE        = '<?= API_BASE ?>';
    var EVENT_SLUG         = <?= json_encode($activeSlug ?: '') ?>;
    var IS_VOTING_OPEN     = <?= $isVotingOpen ? 'true' : 'false' ?>;
    var VOTE_BUNDLE_URL    = '<?= htmlspecialchars($voteBundleUrl) ?>';
    var NOMINEE_PROFILE_URL = '<?= SITE_URL ?>/nominee';
    var COLORS          = ['#be9b3f', '#0a0a0a', '#1a1a1a', '#6c757d'];
    var cardColorIdx    = <?= $globalIdx ?>; // continue from PHP-rendered count

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function escA(s) {
        return String(s).replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function buildCardsHtml(nominees, catSlug, catName) {
        if (!nominees.length) {
            return '<div class="text-center" style="padding:50px 0;color:#aaa;"><i class="fas fa-hourglass-half" style="font-size:2rem;margin-bottom:10px;display:block;"></i><p>Nominees for this category will be announced soon.</p></div>';
        }
        var maxV = Math.max.apply(null, nominees.map(function(n){ return n.votes_count || 0; }));
        if (maxV < 1) maxV = 1;
        var html = '<div class="row">';
        nominees.forEach(function (c) {
            var name  = c.name || '';
            var words = name.trim().split(/\s+/).filter(function(w){ return w && /^[a-zA-Z]/i.test(w); });
            var ini   = words.slice(0, 2).map(function(w){ return w[0].toUpperCase(); }).join('');
            var color = COLORS[cardColorIdx % 4];
            cardColorIdx++;
            var votes  = parseInt(c.votes_count || 0, 10);
            var pct    = Math.min(100, Math.round(votes / maxV * 100));
            var img    = c.image || '';
            var badge  = c.is_winner ? '<div class="winner-badge"><i class="fas fa-crown" style="margin-right:4px;"></i>Winner</div>' : '';
            var photo  = img
                ? '<img src="' + esc(img) + '" alt="' + esc(name) + '" class="nominee-photo" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\';">'
                  + '<div class="nominee-initials" style="display:none;background:' + color + ';">' + esc(ini) + '</div>'
                : '<div class="nominee-initials" style="background:' + color + ';">' + esc(ini) + '</div>';
            var subtitle = c.subtitle ? '<div class="nominee-title">' + esc(c.subtitle) + '</div>' : '';
            var desc     = c.description || '';
            var descT    = desc.length > 60 ? desc.substring(0, 60) + '…' : desc;
            var descHtml = descT ? '<div class="nominee-location"><i class="fa fa-info-circle"></i> ' + esc(descT) + '</div>' : '';
            var profileLink = '<a href="' + esc(NOMINEE_PROFILE_URL + '?slug=' + encodeURIComponent(c.slug || '') + '&event=' + encodeURIComponent(EVENT_SLUG)) + '" onclick="event.stopPropagation();" style="font-size:.76rem;color:#be9b3f;font-weight:600;text-decoration:none;display:block;text-align:center;margin-top:6px;">View Profile &rarr;</a>';
            var voteHtml = IS_VOTING_OPEN
                ? '<a class="theme-btn btn-style-one" style="font-size:.8rem;padding:8px 20px;display:inline-block;" href="' + esc(VOTE_BUNDLE_URL + '&nominee=' + (c.id || 0)) + '" onclick="event.stopPropagation();"><span class="btn-title"><i class="fas fa-vote-yea me-1"></i> Vote Now</span></a>'
                : '<span style="font-size:.8rem;color:#aaa;font-style:italic;">Voting not open</span>';
            var socials = JSON.stringify(c.social_links || []);
            html += '<div class="col-md-4 col-sm-6 col-12" style="margin-bottom:30px;">'
                  + '<div class="nominee-card"'
                  + ' data-name="'           + escA(name) + '"'
                  + ' data-title="'          + escA(c.subtitle || '') + '"'
                  + ' data-desc="'           + escA(c.description || '') + '"'
                  + ' data-image="'          + escA(img) + '"'
                  + ' data-initials="'       + escA(ini) + '"'
                  + ' data-color="'          + color + '"'
                  + ' data-votes="'          + votes + '"'
                  + ' data-pct="'            + pct + '"'
                  + ' data-candidate-id="'   + (c.id || 0) + '"'
                  + ' data-slug="'           + escA(c.slug || '') + '"'
                  + ' data-category="'       + escA(catSlug) + '"'
                  + ' data-category-name="'  + escA(catName) + '"'
                  + ' data-is-winner="'      + (c.is_winner ? '1' : '0') + '"'
                  + ' data-winner-pos="'     + escA(c.winner_position || '') + '"'
                  + ' data-code="'           + escA(c.code || '') + '"'
                  + ' data-video="'          + escA(c.video_url || '') + '"'
                  + ' data-socials="'        + escA(socials) + '"'
                  + ' data-contact-email="'  + escA(c.contact_email || '') + '"'
                  + ' data-contact-phone="'  + escA(c.contact_phone || '') + '">'
                  + badge + photo
                  + '<h4 class="nominee-name">' + esc(name) + '</h4>'
                  + subtitle + descHtml
                  + '<div class="vote-bar-container"><div class="vote-bar-fill" style="width:' + pct + '%;" data-candidate-id="' + (c.id||0) + '-bar"></div></div>'
                  + '<div class="vote-stats"><span>Votes</span><span class="vote-count" data-candidate-id="' + (c.id||0) + '">' + votes.toLocaleString() + '</span></div>'
                  + '<div class="nominee-card-footer">' + voteHtml + profileLink + '</div>'
                  + '</div></div>';
        });
        return html + '</div>';
    }

    function buildPagination($pane, catSlug, cur, last) {
        var $pg = $pane.find('.nominees-pagination');
        if (last <= 1) { $pg.empty(); return; }
        var h = '<nav style="margin-top:16px;"><ul class="pagination justify-content-center flex-wrap">';
        h += '<li class="page-item' + (cur <= 1 ? ' disabled' : '') + '"><a class="page-link" href="#" data-cat="' + catSlug + '" data-pg="' + (cur-1) + '">&laquo;</a></li>';
        var s = Math.max(1, cur - 2), e = Math.min(last, s + 4);
        s = Math.max(1, e - 4);
        if (s > 1) {
            h += '<li class="page-item"><a class="page-link" href="#" data-cat="' + catSlug + '" data-pg="1">1</a></li>';
            if (s > 2) h += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
        }
        for (var p = s; p <= e; p++) {
            h += '<li class="page-item' + (p === cur ? ' active' : '') + '"><a class="page-link" href="#" data-cat="' + catSlug + '" data-pg="' + p + '">' + p + '</a></li>';
        }
        if (e < last) {
            if (e < last - 1) h += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
            h += '<li class="page-item"><a class="page-link" href="#" data-cat="' + catSlug + '" data-pg="' + last + '">' + last + '</a></li>';
        }
        h += '<li class="page-item' + (cur >= last ? ' disabled' : '') + '"><a class="page-link" href="#" data-cat="' + catSlug + '" data-pg="' + (cur+1) + '">&raquo;</a></li>';
        $pg.html(h + '</ul></nav>');
    }

    function loadNomineesPage(catSlug, page) {
        var $pane   = $('#cat-pane-' + catSlug);
        var $grid   = $pane.find('.nominees-grid');
        var catName = $pane.data('cat-name') || catSlug;
        $grid.html('<div class="text-center" style="padding:40px;"><i class="fas fa-spinner fa-spin" style="font-size:2rem;color:#0a0a0a;"></i></div>');
        $.get(API_BASE + '/api/public/events/' + encodeURIComponent(EVENT_SLUG) + '/nominees', {
            category_slug: catSlug, page: page, per_page: 24
        }).done(function (data) {
            $grid.html(buildCardsHtml(data.nominees || [], catSlug, catName));
            $pane.attr('data-current-page', page).attr('data-last-page', data.last_page || 1);
            buildPagination($pane, catSlug, page, data.last_page || 1);
        }).fail(function () {
            $grid.html('<div class="text-center" style="padding:40px;color:#aaa;">Failed to load. Please try again.</div>');
        });
    }

    // Init pagination for server-rendered categories that already have pages
    $(function () {
        $('.tab-pane[data-last-page]').each(function () {
            var $pane    = $(this);
            var catSlug  = $pane.data('cat-slug');
            var lastPage = parseInt($pane.data('last-page'), 10) || 1;
            if (lastPage > 1) {
                buildPagination($pane, catSlug, 1, lastPage);
            }
        });
    });

    // Pagination click handler
    $(document).on('click', '.nominees-pagination .page-link', function (e) {
        e.preventDefault();
        var $li = $(this).parent();
        if ($li.hasClass('disabled') || $li.hasClass('active')) return;
        var catSlug = $(this).data('cat');
        var pg      = parseInt($(this).data('pg'), 10);
        if (!catSlug || !pg || pg < 1) return;
        loadNomineesPage(catSlug, pg);
        var top = $('#cat-pane-' + catSlug).offset().top;
        $('html,body').animate({ scrollTop: top - 100 }, 300);
    });

    // Expose for AJAX event-switcher
    window._nomUpdateEventConfig = function(slug, isVotingOpen, voteBundleUrl) {
        EVENT_SLUG      = slug;
        IS_VOTING_OPEN  = isVotingOpen;
        VOTE_BUNDLE_URL = voteBundleUrl;
    };
    window._nomBuildPagination = buildPagination;

}(jQuery));

// ── Nominee Search + Group + Category Filter ─────────────────────────
<?php
$_grpKeys = array_keys($sidebarGroups);
$_grpVals = array_map(fn($cats) => array_column($cats, 'slug'), array_values($sidebarGroups));
$_groupCatsJson    = json_encode(array_combine($_grpKeys, $_grpVals) ?: (object)[]);
$_allCatOptsJson   = json_encode(array_values(array_map(fn($c) => [
    'slug' => $c['slug'] ?? '',
    'name' => $c['name'] ?? '',
], $categories)));
?>
(function() {
    var $input = document.getElementById('nom-search');
    var $clear = document.getElementById('nom-search-clear');
    var $icon  = document.getElementById('nom-search-icon');
    var $noRes = document.getElementById('nom-no-results');

    var GROUP_CATS      = <?= $_groupCatsJson ?>;
    var ALL_CAT_OPTIONS = <?= $_allCatOptsJson ?>;
    var _lastActivePaneSlug = '';

    function getCatSel()   { return document.getElementById('nom-cat-select'); }
    function getGroupSel() { return document.getElementById('nom-group-select'); }

    function initS2(selector, placeholder) {
        if (!window.jQuery || !$.fn.select2) return;
        var $el = $(selector);
        if (!$el.length) return;
        if ($el.hasClass('select2-hidden-accessible')) $el.select2('destroy');
        $el.select2({ placeholder: placeholder, allowClear: true });
    }

    function repopulateCatOptions(groupName) {
        var $cs = getCatSel();
        if (!$cs) return;
        if (window.jQuery && $.fn.select2) { try { $('#nom-cat-select').select2('destroy'); } catch(e){} }
        var allowed = groupName && GROUP_CATS[groupName] ? GROUP_CATS[groupName] : null;
        var html = '<option value=""></option>';
        ALL_CAT_OPTIONS.forEach(function(c) {
            if (!allowed || allowed.indexOf(c.slug) !== -1)
                html += '<option value="' + c.slug + '">' + c.name + '</option>';
        });
        $cs.innerHTML = html;
        initS2('#nom-cat-select', 'All Categories');
    }

    // ── Public reinit — always registered (even if no nominees on this page) ──
    window.initNomFilters = function(newGroupCats, newAllCatOpts) {
        GROUP_CATS      = newGroupCats      || {};
        ALL_CAT_OPTIONS = newAllCatOpts     || [];
        _lastActivePaneSlug = '';
        if (window.jQuery && $.fn.select2) {
            try { var $cs2 = $('#nom-cat-select'); if ($cs2.hasClass('select2-hidden-accessible')) $cs2.select2('destroy'); } catch(e){}
            try { var $gs2 = $('#nom-group-select'); if ($gs2.hasClass('select2-hidden-accessible')) $gs2.select2('destroy'); } catch(e){}
        }
        initS2('#nom-cat-select', 'All Categories');
        if (getGroupSel()) initS2('#nom-group-select', 'All Groups');
        var firstSlug = (ALL_CAT_OPTIONS[0] || {}).slug;
        if (firstSlug) { _lastActivePaneSlug = firstSlug; activatePane(firstSlug); }
        if ($input) { $input.value = ''; }
        var tc = document.getElementById('nomineeTabContent');
        if (tc) tc.classList.remove('search-all');
        if ($noRes) $noRes.style.display = 'none';
        document.querySelectorAll('.tab-pane').forEach(function(p) { p.style.display = ''; });
    };

    // Initial Select2 setup
    $(function() {
        initS2('#nom-cat-select', 'All Categories');
        if (getGroupSel()) initS2('#nom-group-select', 'All Groups');
    });

    // No filter row (event has no nominees yet) — stop here
    if (!$input) return;

    var _lockGroupChange = false;

    if (window.jQuery) {
        $(document).on('change', '#nom-cat-select', function() {
            var slug  = $(this).val();
            var $gs   = getGroupSel();
            if (slug && $gs) {
                var foundGrp = '';
                Object.keys(GROUP_CATS).forEach(function(g) {
                    if (g !== '__root__' && GROUP_CATS[g].indexOf(slug) !== -1) foundGrp = g;
                });
                var currentGrp = $($gs).val() || '';
                if (foundGrp && foundGrp !== currentGrp) {
                    _lockGroupChange = true;
                    repopulateCatOptions(foundGrp);
                    $('#nom-cat-select').val(slug).trigger('change.select2');
                    $('#nom-group-select').val(foundGrp).trigger('change.select2');
                    _lockGroupChange = false;
                }
            }
            applyFilters();
        });

        $(document).on('change', '#nom-group-select', function() {
            if (_lockGroupChange) return;
            repopulateCatOptions($(this).val());
            applyFilters();
        });
    }

    function activatePane(slug) {
        if (!slug) return;
        document.querySelectorAll('.tab-pane').forEach(function(p) { p.classList.remove('show', 'active'); });
        document.querySelectorAll('[data-toggle="pill"]').forEach(function(b) { b.classList.remove('active'); });
        var pane = document.getElementById('cat-pane-' + slug);
        var btn  = document.querySelector('[data-target="#cat-pane-' + slug + '"]');
        if (pane) pane.classList.add('active', 'show');
        if (btn)  btn.classList.add('active');
    }

    function applyFilters() {
        var $cs  = getCatSel();
        var $gs  = getGroupSel();
        var q    = $input.value.trim().toLowerCase();
        var cat  = $cs ? $cs.value : '';
        var grp  = $gs ? $gs.value : '';
        var tc   = document.getElementById('nomineeTabContent');

        $clear.style.display = q.length > 0 ? 'block' : 'none';
        $icon.style.display  = q.length > 0 ? 'none'  : 'block';

        if (q) {
            // ── Global search: show ALL panes, filter cards across every category ──
            if (tc) tc.classList.add('search-all');
            var totalVisible = 0;
            document.querySelectorAll('.tab-pane').forEach(function(pane) {
                var paneHits = 0;
                pane.querySelectorAll('.nominee-card').forEach(function(card) {
                    var name = (card.getAttribute('data-name') || '').toLowerCase();
                    var show = name.indexOf(q) !== -1;
                    card.parentElement.style.display = show ? '' : 'none';
                    if (show) paneHits++;
                });
                pane.style.display = paneHits === 0 ? 'none' : '';
                totalVisible += paneHits;
            });
            if ($noRes) $noRes.style.display = totalVisible === 0 ? 'block' : 'none';
            return;
        }

        // ── No query — restore single-pane tab mode ──
        if (tc) tc.classList.remove('search-all');
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.style.display = '';
            pane.querySelectorAll('.nominee-card').forEach(function(card) {
                card.parentElement.style.display = '';
            });
        });
        if ($noRes) $noRes.style.display = 'none';

        // Tab navigation based on cat/group dropdown
        if (cat) {
            _lastActivePaneSlug = cat;
            activatePane(cat);
        } else if (grp) {
            var slugs = GROUP_CATS[grp] || [];
            for (var i = 0; i < slugs.length; i++) {
                if (document.getElementById('cat-pane-' + slugs[i])) {
                    _lastActivePaneSlug = slugs[i];
                    activatePane(slugs[i]);
                    break;
                }
            }
        } else if (_lastActivePaneSlug) {
            activatePane(_lastActivePaneSlug);
        }
    }

    window._nomApplyFilters = applyFilters;
    window._nomActivatePane = activatePane;
    window._nomGroupCats    = GROUP_CATS;

    $input.addEventListener('input', applyFilters);
    $clear.addEventListener('click', function() { $input.value = ''; applyFilters(); $input.focus(); });
}());

// ── AJAX event switcher ──────────────────────────────────────────────────
window.switchNomEvent = function(slug) {
    // Find current active slug
    var activePill = document.querySelector('.nom-event-pill[data-active="1"]');
    var currentSlug = activePill ? activePill.dataset.slug : '';
    if (!slug || slug === currentSlug) return;

    // Loading overlay on content area
    var tc = document.getElementById('nomineeTabContent');
    if (tc) {
        tc.style.position = 'relative';
        var ov = document.createElement('div');
        ov.id = 'nom-switch-overlay';
        ov.style.cssText = 'position:absolute;inset:0;background:rgba(255,255,255,.8);'
            + 'display:flex;align-items:center;justify-content:center;z-index:20;border-radius:8px;min-height:200px;';
        ov.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:2rem;color:#be9b3f;"></i>';
        tc.appendChild(ov);
    }

    fetch('nominees?event=' + encodeURIComponent(slug))
        .then(function(r) { return r.text(); })
        .then(function(html) {
            var parser = new DOMParser();
            var doc    = parser.parseFromString(html, 'text/html');

            // Swap tab content
            var newTc = doc.getElementById('nomineeTabContent');
            if (tc && newTc) tc.innerHTML = newTc.innerHTML;

            // Swap sidebar nav
            var newNav = doc.getElementById('catSidebarNav');
            var curNav = document.getElementById('catSidebarNav');
            if (curNav && newNav) curNav.innerHTML = newNav.innerHTML;

            // Swap filter dropdowns (preserve search text)
            var searchVal = document.getElementById('nom-search') ? document.getElementById('nom-search').value : '';
            var newFr = doc.querySelector('.nom-filter-row');
            var curFr = document.querySelector('.nom-filter-row');
            if (curFr && newFr) {
                curFr.innerHTML = newFr.innerHTML;
                var si = document.getElementById('nom-search');
                if (si) si.value = searchVal;
            }

            // Read page config from the safe data-config attribute
            var nomData = {};
            try {
                var configEl = doc.getElementById('nom-page-data');
                if (configEl) nomData = JSON.parse(configEl.getAttribute('data-config') || '{}');
            } catch(e) {}
            if (window.initNomFilters) {
                window.initNomFilters(nomData.group_cats || {}, nomData.all_cat_opts || []);
            }
            if (window._nomUpdateEventConfig) {
                window._nomUpdateEventConfig(
                    nomData.event_slug      || slug,
                    !!nomData.is_voting_open,
                    nomData.vote_bundle_url || ''
                );
            }

            // Re-init pagination on any multi-page panes
            if (window.jQuery && window._nomBuildPagination) {
                jQuery('.tab-pane[data-last-page]').each(function() {
                    var $p = jQuery(this);
                    var cs = $p.data('cat-slug');
                    var lp = parseInt($p.data('last-page'), 10) || 1;
                    if (lp > 1) window._nomBuildPagination($p, cs, 1, lp);
                });
            }

            // Update pill active states
            document.querySelectorAll('.nom-event-pill').forEach(function(p) {
                var isAct = p.dataset.slug === slug;
                p.dataset.active   = isAct ? '1' : '0';
                p.style.background = isAct ? '#be9b3f' : '#fff';
                p.style.color      = isAct ? '#fff'    : '#555';
                p.style.borderColor= isAct ? '#be9b3f' : '#ddd';
            });

            // Update browser URL without page reload
            history.pushState({ event: slug }, '', 'nominees?event=' + encodeURIComponent(slug));
        })
        .catch(function() {
            // Fallback to full page load on any error
            window.location.href = 'nominees?event=' + encodeURIComponent(slug);
        })
        .finally(function() {
            var ov2 = document.getElementById('nom-switch-overlay');
            if (ov2) ov2.remove();
        });
};

// ── Nominee Modal ──────────────────────────────────────────────────────────
$(document).ready(function() {
    var EVENT_SLUG          = <?= json_encode($activeSlug ?: '') ?>;
    var NOMINEE_PROFILE_URL = '<?= SITE_URL ?>/nominee';

    var socialIcons = {
        facebook:  { icon:'fab fa-facebook-f',  color:'#1877f2', label:'Facebook'  },
        twitter:   { icon:'fab fa-twitter',     color:'#1da1f2', label:'Twitter'   },
        instagram: { icon:'fab fa-instagram',   color:'#e1306c', label:'Instagram' },
        linkedin:  { icon:'fab fa-linkedin-in', color:'#0a66c2', label:'LinkedIn'  },
        youtube:   { icon:'fab fa-youtube',     color:'#ff0000', label:'YouTube'   },
        tiktok:    { icon:'fab fa-tiktok',      color:'#010101', label:'TikTok'    },
        website:   { icon:'fas fa-globe',       color:'#0a0a0a', label:'Website'   }
    };

    $(document).on('click', '.nominee-card', function(e) {
        if ($(e.target).closest('a').length) return; // don't open modal on vote button click
        var $t        = $(this);
        var name      = $t.data('name')          || '';
        var title     = $t.data('title')         || '';
        var desc      = $t.data('desc')          || '';
        var img       = $t.data('image')         || '';
        var ini       = $t.data('initials')      || '';
        var color     = $t.data('color')         || '#0a0a0a';
        var votes     = parseInt($t.data('votes'), 10) || 0;
        var catName   = $t.data('category-name') || '';
        var nomId     = $t.data('candidate-id')  || '';
        var slug      = $t.data('slug')          || '';
        var isWinner  = parseInt($t.data('is-winner'), 10);
        var winnerPos = $t.data('winner-pos')    || '';
        var code      = $t.data('code')          || '';
        var video     = $t.data('video')         || '';
        var email      = $t.data('contact-email') || '';
        var phone      = $t.data('contact-phone') || '';
        var socialsRaw = $t.data('socials');
        var socials   = {};
        try { socials = typeof socialsRaw === 'object' ? socialsRaw : JSON.parse(socialsRaw || '{}'); } catch(e) {}

        // Header
        $('#modal-category').text(catName);
        $('#modal-name').text(name);

        // Avatar
        if (img) {
            $('#modal-photo').attr('src', img).attr('alt', name).show();
            $('#modal-initials').hide();
        } else {
            $('#modal-photo').hide();
            $('#modal-initials').text(ini).css('background-color', color).show();
        }

        // Winner badge
        if (isWinner) {
            $('#modal-winner-label').text(winnerPos ? 'Winner — #' + winnerPos : 'Winner');
            $('#modal-winner-badge').css('display', 'inline-block');
        } else {
            $('#modal-winner-badge').hide();
        }

        // Subtitle
        $('#modal-title').text(title).toggle(!!title);

        // Code
        if (code) {
            $('#modal-code').text(code);
            $('#modal-code-wrap').show();
        } else {
            $('#modal-code-wrap').hide();
        }

        // Votes
        $('#modal-vote-count').text(votes.toLocaleString() + ' votes');

        // Description
        if (desc) {
            $('#modal-desc-text').text(desc);
            $('#modal-desc-wrap').show();
        } else {
            $('#modal-desc-wrap').hide();
        }

        // Contact info
        var hasContact = !!(email || phone);
        if (email) {
            $('#modal-email').text(email).attr('href', 'mailto:' + email);
            $('#modal-email-row').css('display', 'flex');
        } else {
            $('#modal-email-row').hide();
        }
        if (phone) {
            $('#modal-phone').text(phone);
            $('#modal-phone-row').css('display', 'flex');
        } else {
            $('#modal-phone-row').hide();
        }
        $('#modal-contact-wrap').toggle(hasContact);

        // Video
        if (video) {
            $('#modal-video').attr('href', video);
            $('#modal-video-wrap').show();
        } else {
            $('#modal-video-wrap').hide();
        }

        // Socials
        var $sc = $('#modal-socials').empty();
        var hasSocials = false;
        $.each(socials, function(platform, url) {
            if (!url) return;
            var cfg = socialIcons[platform.toLowerCase()] || { icon:'fas fa-link', color:'#888', label: platform };
            hasSocials = true;
            $sc.append('<a href="' + url + '" target="_blank" rel="noopener" title="' + cfg.label + '" ' +
                'style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:' + cfg.color + ';color:#fff;font-size:.82rem;text-decoration:none;">' +
                '<i class="' + cfg.icon + '"></i></a>');
        });
        $('#modal-socials-wrap').toggle(hasSocials);

        // Vote button
        if ($('#modal-vote-btn').length) {
            $('#modal-vote-btn').attr('href', 'vote-bundle.php?event=' + encodeURIComponent(EVENT_SLUG) + '&nominee=' + encodeURIComponent(nomId));
        }

        // Profile link
        $('#modal-profile-link').attr('href', NOMINEE_PROFILE_URL + '?slug=' + encodeURIComponent(slug) + '&event=' + encodeURIComponent(EVENT_SLUG));

        // Open custom overlay
        $('#nomineeModal').css('display', 'flex');
        $('body').css('overflow', 'hidden');
    });

    // Close: button
    $(document).on('click', '#nom-modal-close', function () {
        $('#nomineeModal').hide();
        $('body').css('overflow', '');
    });

    // Close: backdrop click
    $(document).on('click', '#nomineeModal', function (e) {
        if (e.target === this) {
            $(this).hide();
            $('body').css('overflow', '');
        }
    });

    // Close: Escape key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $('#nomineeModal').is(':visible')) {
            $('#nomineeModal').hide();
            $('body').css('overflow', '');
        }
    });
});
</script>
<script>
// Show all sidebar categories when "more" button is clicked
window.showAllSidebarCats = function() {
    var nav = document.getElementById('nomineeTabs');
    var btn = document.getElementById('showAllCats');
    if (!nav || !btn) return;
    // Build additional buttons from all tab panes already in DOM
    var existing = Array.from(nav.querySelectorAll('button[data-target]')).map(function(b) {
        return b.getAttribute('data-target');
    });
    document.querySelectorAll('[id^="cat-pane-"]').forEach(function(pane) {
        var target = '#' + pane.id;
        if (existing.indexOf(target) !== -1) return;
        var slug = pane.id.replace('cat-pane-', '');
        var catName = pane.getAttribute('data-cat-name') || slug;
        var count = pane.getAttribute('data-total-count') || '';
        var b = document.createElement('button');
        b.className = 'nav-link';
        b.setAttribute('data-toggle', 'pill');
        b.setAttribute('data-target', target);
        b.type = 'button';
        b.innerHTML = catName + (count ? ' <span class="count-badge">' + count + '</span>' : '');
        nav.insertBefore(b, btn);
    });
    btn.style.display = 'none';
    // Re-init Bootstrap pill tabs on new buttons
    if (window.jQuery) {
        jQuery(nav).find('[data-toggle="pill"]').each(function() {
            jQuery(this).on('click', function() { jQuery(this).tab('show'); });
        });
    }
};

// Auto-activate category tab + scroll when ?category=slug is in URL
(function() {
    var params = new URLSearchParams(window.location.search);
    var cat    = params.get('category');
    if (!cat) return;
    var el = document.getElementById('cat-pane-' + cat);
    if (el) {
        setTimeout(function() {
            // Activate the sidebar pill tab
            var $tab = $('[data-target="#cat-pane-' + cat + '"]');
            if ($tab.length) $tab.tab('show');
            // Update the category dropdown (Select2)
            var $sel = $('#nom-cat-select');
            if ($sel.length) {
                $sel.val(cat).trigger('change.select2');
            }
            // Scroll to the pane
            var top = el.getBoundingClientRect().top + window.pageYOffset - 110;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }, 450);
    }
})();

// Sidebar pill click: scroll to the matching category pane
$(document).on('click', '[data-toggle="pill"]', function() {
    var target = $(this).data('target');
    if (!target) return;
    setTimeout(function() {
        var $pane = $(target);
        if ($pane.length) {
            var top = $pane[0].getBoundingClientRect().top + window.pageYOffset - 110;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }
    }, 100);
});
</script>
</body>
</html>
