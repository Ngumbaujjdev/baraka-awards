<?php
include 'config/config.php';
include 'libs/App.php';

$nomineeSlug = trim($_GET['slug'] ?? '');
$eventSlug   = trim($_GET['event'] ?? 'dfa-gala-2026');

if (!$nomineeSlug) {
    header('Location: ' . SITE_URL . '/nominees?event=' . urlencode($eventSlug));
    exit;
}

// Try direct single-nominee endpoint first
$resp    = tuqio_api('/api/public/events/' . urlencode($eventSlug) . '/nominees/' . urlencode($nomineeSlug));
$nominee = $resp['nominee'] ?? $resp['data'] ?? null;
$cat     = $resp['category'] ?? [];
$event   = $resp['event']    ?? [];

// Fallback: search full nominees list by slug
if (!$nominee || !isset($nominee['name'])) {
    $fullResp = tuqio_api('/api/public/events/' . urlencode($eventSlug) . '/nominees');
    $event    = $fullResp['event'] ?? $event;
    $cats     = $fullResp['categories'] ?? [];
    if (empty($cats) && !empty($fullResp['groups'])) {
        foreach ($fullResp['groups'] as $grp) {
            foreach ($grp['categories'] ?? $grp['sub_categories'] ?? [] as $sc) {
                $cats[] = $sc;
            }
        }
        foreach ($fullResp['ungrouped'] ?? [] as $uc) { $cats[] = $uc; }
    }
    foreach ($cats as $c) {
        foreach ($c['candidates'] ?? $c['nominees'] ?? [] as $n) {
            if (($n['slug'] ?? '') === $nomineeSlug) {
                $nominee = $n;
                $cat     = ['name' => $c['name'] ?? '', 'slug' => $c['slug'] ?? ''];
                break 2;
            }
        }
    }
}

if (!$nominee || !isset($nominee['name'])) {
    header('Location: ' . SITE_URL . '/nominees?event=' . urlencode($eventSlug));
    exit;
}

$votingIsOpen  = (bool) ($event['voting_is_open'] ?? false);
$voteBundleUrl = SITE_URL . '/vote-bundle?event=' . urlencode($eventSlug);

$pageTitle = htmlspecialchars($nominee['name']) . ' | Baraka Awards Kenya';
$ogImage   = !empty($nominee['image']) ? $nominee['image'] : OG_IMAGE;

$shareUrl  = SITE_URL . '/nominee?slug=' . urlencode($nomineeSlug) . '&event=' . urlencode($eventSlug);
$shareText = 'Vote for ' . $nominee['name'] . ' at Baraka Awards Kenya 2026!';

$socialLinks = $nominee['social_links'] ?? [];
$votesCount  = (int) ($nominee['votes_count'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php
$nomSeoDesc = htmlspecialchars(mb_strimwidth(strip_tags($nominee['description'] ?? ''), 0, 160, '...'));
$nomSeoUrl  = SITE_URL . '/nominee?slug=' . urlencode($nomineeSlug) . '&event=' . urlencode($eventSlug);
$nomCatName = htmlspecialchars($cat['name'] ?? 'Award Nominee');
?>

<!-- SEO -->
<title><?= $pageTitle ?></title>
<meta name="description" content="<?= $nomSeoDesc ?>">
<meta name="keywords" content="<?= htmlspecialchars($nominee['name']) ?>, Baraka Awards Kenya, Kenya award nominee, vote <?= htmlspecialchars($nominee['name']) ?>, <?= $nomCatName ?>, nominated Kenya, award nominee East Africa, Baraka Awards 2026, online excellence Kenya">
<meta name="author" content="Baraka Awards Kenya">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= $nomSeoUrl ?>">

<!-- Open Graph -->
<meta property="og:title" content="<?= htmlspecialchars($nominee['name']) ?> — Baraka Awards Kenya">
<meta property="og:type" content="profile">
<meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= $nomSeoUrl ?>">
<meta property="og:description" content="<?= $nomSeoDesc ?>">
<meta property="og:site_name" content="Baraka Awards Kenya">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@barakaawards">
<meta name="twitter:title" content="<?= htmlspecialchars($nominee['name']) ?> — Baraka Awards Kenya">
<meta name="twitter:description" content="<?= $nomSeoDesc ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","@id":"https://barakaawards.tuqiohub.africa/#organization","logo":"https://barakaawards.tuqiohub.africa/assets/images/favicon/favicon-96x96.png","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>","description":"Baraka Awards Kenya recognises and rewards companies, businesses, individuals, and brands that demonstrate exceptional online presence and impact across East Africa.","foundingDate":"2024","foundingLocation":{"@type":"Place","address":{"@type":"PostalAddress","streetAddress":"Southfield Mall, Mombasa Road","addressLocality":"Nairobi","addressRegion":"Nairobi","addressCountry":"Kenya"}},"contactPoint":{"@type":"ContactPoint","telephone":"+254710388288","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support","availableLanguage":"en-US"},"sameAs":["https://www.instagram.com/barakaawardske/","https://www.facebook.com/barakaawards","https://twitter.com/barakaawards","https://www.tiktok.com/@barakaawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?= SITE_URL ?>/"},{"@type":"ListItem","position":2,"name":"Nominees","item":"<?= SITE_URL ?>/nominees"},{"@type":"ListItem","position":3,"name":"<?= addslashes($nominee['name']) ?>","item":"<?= $nomSeoUrl ?>"}]}
</script>

<!-- JSON-LD: Person -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Person","name":"<?= addslashes($nominee['name']) ?>","description":"<?= addslashes($nomSeoDesc) ?>","image":"<?= htmlspecialchars($ogImage) ?>","url":"<?= $nomSeoUrl ?>"}
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
/* ── Hero ── */
.np-hero {
    position: relative;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    padding: 0;
    overflow: hidden;
    min-height: 360px;
}
.np-hero-bg {
    position: absolute; inset: 0;
    background-size: cover; background-position: center top;
    filter: blur(6px) brightness(.45);
    transform: scale(1.06);
}
.np-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to bottom, rgba(5,55,50,.7) 0%, rgba(5,55,50,.92) 100%);
}
.np-hero-inner {
    position: relative; z-index: 2;
    padding: 40px 0 50px;
    color: #fff;
}
.np-breadcrumb { list-style: none; padding: 0; margin: 0 0 28px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
.np-breadcrumb li { font-size: .8rem; color: rgba(255,255,255,.5); }
.np-breadcrumb li + li::before { content: '›'; margin-right: 6px; }
.np-breadcrumb a { color: rgba(255,255,255,.6); text-decoration: none; }
.np-breadcrumb a:hover { color: #be9b3f; }
.np-breadcrumb .current { color: #be9b3f; font-weight: 600; }

/* Photo */
.np-photo-wrap { flex-shrink: 0; }
.np-photo {
    width: 180px; height: 180px; border-radius: 50%;
    object-fit: cover; object-position: center top;
    border: 5px solid #be9b3f;
    box-shadow: 0 10px 40px rgba(0,0,0,.45);
    display: block;
}
.np-photo-placeholder {
    width: 180px; height: 180px; border-radius: 50%;
    background: rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    border: 5px solid #be9b3f;
    box-shadow: 0 10px 40px rgba(0,0,0,.45);
    font-size: 4rem; color: rgba(255,255,255,.5);
}

/* Info */
.np-hero-content { display: flex; align-items: flex-start; gap: 36px; }
.np-info { flex: 1; padding-top: 8px; }
.np-cat-badge {
    display: inline-block;
    background: rgba(190,155,63,.18); border: 1.5px solid #be9b3f;
    color: #f5d98a; border-radius: 20px;
    padding: 5px 16px; font-size: .78rem; font-weight: 700;
    letter-spacing: .6px; text-transform: uppercase; margin-bottom: 12px;
}
.np-name { font-size: 2.4rem; font-weight: 900; line-height: 1.15; margin: 0 0 6px; color: #fff; }
.np-subtitle { font-size: 1rem; color: rgba(255,255,255,.65); margin: 0 0 14px; }
.np-winner-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg,#be9b3f,#f0c44b);
    color: #fff; border-radius: 20px; padding: 5px 16px;
    font-size: .82rem; font-weight: 700; margin-bottom: 14px;
}
.np-votes-display {
    display: inline-flex; align-items: baseline; gap: 6px;
    background: rgba(255,255,255,.1); border: 1px solid rgba(190,155,63,.35);
    border-radius: 10px; padding: 10px 20px; margin-top: 8px;
}
.np-votes-num { font-size: 2rem; font-weight: 900; color: #be9b3f; line-height: 1; }
.np-votes-label { font-size: .85rem; color: rgba(255,255,255,.7); font-weight: 600; }

/* ── Body ── */
.np-body { padding: 50px 0 70px; background: #f7f8f9; }
.np-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
    padding: 28px; margin-bottom: 24px;
}
.np-card-title {
    font-size: 1rem; font-weight: 800; color: #0a0a0a;
    margin: 0 0 16px; display: flex; align-items: center; gap: 8px;
}
.np-card-title i { color: #be9b3f; }
.np-description { font-size: .97rem; line-height: 1.85; color: #444; }
.np-description p { margin-bottom: .8em; }

/* Social links (nominee's own) */
.social-links-row { display: flex; flex-wrap: wrap; gap: 10px; }
.social-link-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 16px; border-radius: 7px;
    background: #f5f5f5; color: #333;
    font-size: .84rem; font-weight: 600;
    text-decoration: none; border: 1px solid #e5e5e5;
    transition: all .2s;
}
.social-link-btn:hover { background: #0a0a0a; color: #fff; border-color: #0a0a0a; text-decoration: none; }

/* Video embed */
.np-video { border-radius: 10px; overflow: hidden; margin-top: 4px; }
.np-video iframe { width: 100%; height: 270px; border: 0; display: block; }

/* ── Sidebar ── */
.np-sidebar { position: sticky; top: 90px; }

/* Vote card */
.btn-vote-big {
    display: block; width: 100%;
    background: linear-gradient(135deg, #be9b3f, #d4af5a);
    color: #fff; border: none; border-radius: 10px;
    padding: 15px 20px; font-size: 1.05rem; font-weight: 800;
    text-align: center; text-decoration: none;
    letter-spacing: .5px; cursor: pointer;
    box-shadow: 0 4px 16px rgba(190,155,63,.35);
    transition: all .2s;
}
.btn-vote-big:hover { background: linear-gradient(135deg,#a8872f,#be9b3f); color: #fff; text-decoration: none; transform: translateY(-1px); }
.btn-vote-big.disabled { background: #ddd; color: #999; cursor: not-allowed; box-shadow: none; transform: none; }
.vote-bar-wrap { margin-top: 16px; }
.vote-count-row { display: flex; justify-content: space-between; align-items: center; font-size: .82rem; color: #888; margin-bottom: 8px; }
.vote-count-row .count-val { font-size: 1.05rem; font-weight: 800; color: #0a0a0a; }
.vote-bar-bg { background: #f0f0f0; border-radius: 20px; height: 8px; overflow: hidden; }
.vote-bar-fill { height: 8px; border-radius: 20px; background: linear-gradient(90deg,#0a0a0a,#be9b3f); transition: width .8s; }
.voting-closed-note { font-size: .75rem; color: #aaa; text-align: center; margin-top: 10px; }

/* Share buttons */
.share-btn-list { display: flex; flex-direction: column; gap: 9px; margin-top: 4px; }
.share-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 16px; border-radius: 8px;
    border: none; cursor: pointer;
    font-size: .88rem; font-weight: 700;
    text-decoration: none; transition: opacity .2s, transform .15s;
    width: 100%;
}
.share-btn:hover { opacity: .88; text-decoration: none; transform: translateX(2px); }
.share-btn i { font-size: 1.05rem; width: 20px; text-align: center; }
.share-wa   { background: #25d366; color: #fff; }
.share-ig   { background: linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888); color: #fff; }
.share-tw   { background: #000; color: #fff; }
.share-li   { background: #0077b5; color: #fff; }
.share-copy { background: #f0f0f0; color: #333; border: 1px solid #e0e0e0; }
.copy-success { display: none; color: #a0822f; font-size: .78rem; text-align: center; margin-top: 6px; font-weight: 700; }

/* Back link */
.np-back-link {
    display: flex; align-items: center; gap: 6px;
    color: #0a0a0a; font-size: .85rem; font-weight: 700;
    text-decoration: none; justify-content: center;
    padding: 10px 0; transition: color .2s;
}
.np-back-link:hover { color: #be9b3f; text-decoration: none; }

/* ── Responsive ── */
@media (max-width: 767px) {
    .np-hero-content { flex-direction: column; align-items: center; text-align: center; gap: 20px; }
    .np-photo, .np-photo-placeholder { width: 140px; height: 140px; }
    .np-name { font-size: 1.75rem; }
    .np-votes-display { margin: 8px auto 0; }
    .np-body { padding: 30px 0 50px; }
    .np-sidebar { position: static; margin-top: 10px; }
}
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

<!-- Hero -->
<section class="np-hero">
    <?php if (!empty($nominee['image'])): ?>
    <div class="np-hero-bg" style="background-image:url('<?= htmlspecialchars($nominee['image']) ?>');"></div>
    <div class="np-hero-overlay"></div>
    <?php endif; ?>

    <div class="np-hero-inner">
        <div class="auto-container">
            <!-- Breadcrumb -->
            <ul class="np-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <?php if (!empty($cat['name'])): ?>
                <li><a href="<?= SITE_URL ?>/nominees?event=<?= urlencode($eventSlug) ?>&category=<?= urlencode($cat['slug'] ?? '') ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                <?php endif; ?>
                <li class="current"><?= htmlspecialchars($nominee['name']) ?></li>
            </ul>

            <div class="np-hero-content">
                <!-- Photo -->
                <div class="np-photo-wrap">
                    <?php if (!empty($nominee['image'])): ?>
                    <img src="<?= htmlspecialchars($nominee['image']) ?>"
                         alt="<?= htmlspecialchars($nominee['name']) ?>"
                         class="np-photo"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div class="np-photo-placeholder" style="display:none;"><i class="flaticon-user"></i></div>
                    <?php else: ?>
                    <div class="np-photo-placeholder"><i class="flaticon-user"></i></div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="np-info">
                    <?php if (!empty($cat['name'])): ?>
                    <div class="np-cat-badge"><?= htmlspecialchars($cat['name']) ?></div>
                    <?php endif; ?>

                    <h1 class="np-name"><?= htmlspecialchars($nominee['name']) ?></h1>

                    <?php if (!empty($nominee['subtitle'])): ?>
                    <p class="np-subtitle"><?= htmlspecialchars($nominee['subtitle']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($nominee['is_winner'])): ?>
                    <div class="np-winner-badge">
                        <i class="fa fa-trophy"></i>
                        Winner<?= ($nominee['winner_position'] ?? 1) > 1 ? ' #' . (int)$nominee['winner_position'] : '' ?>
                    </div>
                    <?php endif; ?>

                    <div class="np-votes-display">
                        <span class="np-votes-num" id="heroVoteCount"><?= number_format($votesCount) ?></span>
                        <span class="np-votes-label">votes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Body -->
<section class="np-body">
    <div class="auto-container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-8">

                <?php if (!empty($nominee['description'])): ?>
                <div class="np-card">
                    <h4 class="np-card-title"><i class="fa fa-user-circle"></i> About <?= htmlspecialchars($nominee['name']) ?></h4>
                    <div class="np-description">
                        <?= nl2br(htmlspecialchars($nominee['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($socialLinks)): ?>
                <?php
                $socialMeta = [
                    'instagram' => ['icon' => 'fa fa-instagram', 'label' => 'Instagram',  'bg' => 'linear-gradient(135deg,#f09433,#dc2743,#bc1888)', 'color' => '#fff'],
                    'facebook'  => ['icon' => 'fa fa-facebook',  'label' => 'Facebook',   'bg' => '#1877f2',  'color' => '#fff'],
                    'twitter'   => ['icon' => 'fa fa-twitter',   'label' => 'Twitter/X',  'bg' => '#000',     'color' => '#fff'],
                    'youtube'   => ['icon' => 'fa fa-youtube',   'label' => 'YouTube',    'bg' => '#ff0000',  'color' => '#fff'],
                    'tiktok'    => ['icon' => 'fa fa-music',     'label' => 'TikTok',     'bg' => '#010101',  'color' => '#fff'],
                    'linkedin'  => ['icon' => 'fa fa-linkedin',  'label' => 'LinkedIn',   'bg' => '#0077b5',  'color' => '#fff'],
                    'website'   => ['icon' => 'fa fa-globe',     'label' => 'Website',    'bg' => '#f5f5f5',  'color' => '#333'],
                ];
                ?>
                <div class="np-card">
                    <h4 class="np-card-title"><i class="fa fa-share-alt"></i> Connect with <?= htmlspecialchars($nominee['name']) ?></h4>
                    <div class="social-links-row">
                        <?php foreach ($socialLinks as $platform => $url):
                            if (empty($url)) continue;
                            $m = $socialMeta[$platform] ?? ['icon' => 'fa fa-link', 'label' => ucfirst($platform), 'bg' => '#eee', 'color' => '#333'];
                        ?>
                        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener" class="social-link-btn"
                           style="background:<?= $m['bg'] ?>;color:<?= $m['color'] ?>;border-color:transparent;">
                            <i class="<?= $m['icon'] ?>"></i> <?= $m['label'] ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($nominee['video_url'])): ?>
                <div class="np-card">
                    <h4 class="np-card-title"><i class="fa fa-play-circle"></i> Featured Video</h4>
                    <div class="np-video">
                        <?php
                        $videoUrl = $nominee['video_url'];
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
                            $videoUrl = 'https://www.youtube.com/embed/' . $m[1];
                        }
                        ?>
                        <iframe src="<?= htmlspecialchars($videoUrl) ?>" allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- /col-lg-8 -->

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="np-sidebar">

                    <!-- Vote card -->
                    <div class="np-card">
                        <h4 class="np-card-title"><i class="fa fa-star"></i> Support This Nominee</h4>

                        <?php if ($votingIsOpen): ?>
                        <a href="<?= $voteBundleUrl ?>&nominee=<?= (int)$nominee['id'] ?>" class="btn-vote-big">
                            <i class="fa fa-star" style="margin-right:6px;"></i> Vote Now &rarr;
                        </a>
                        <?php else: ?>
                        <span class="btn-vote-big disabled">
                            <i class="fa fa-lock" style="margin-right:6px;"></i>
                            <?= $votesCount > 0 ? 'Voting Has Closed' : 'Voting Not Yet Open' ?>
                        </span>
                        <p class="voting-closed-note">Check back when voting opens to cast your vote.</p>
                        <?php endif; ?>

                        <div class="vote-bar-wrap">
                            <div class="vote-count-row">
                                <span>Total Votes</span>
                                <span class="count-val"><?= number_format($votesCount) ?></span>
                            </div>
                            <div class="vote-bar-bg">
                                <div class="vote-bar-fill" style="width:<?= $votesCount > 0 ? min(100, max(4, 60)) : 4 ?>%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Share card -->
                    <div class="np-card">
                        <h4 class="np-card-title"><i class="fa fa-share-alt"></i> Share This Nominee</h4>
                        <div class="share-btn-list">
                            <a href="https://wa.me/?text=<?= urlencode("$shareText $shareUrl") ?>"
                               target="_blank" class="share-btn share-wa">
                                <i class="fa fa-whatsapp"></i> Share on WhatsApp
                            </a>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener"
                               class="share-btn share-ig" onclick="igShare(event)">
                                <i class="fa fa-instagram"></i> Share on Instagram
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($shareText) ?>&url=<?= urlencode($shareUrl) ?>"
                               target="_blank" class="share-btn share-tw">
                                <i class="fa fa-twitter"></i> Share on Twitter / X
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($shareUrl) ?>"
                               target="_blank" class="share-btn share-li">
                                <i class="fa fa-linkedin"></i> Share on LinkedIn
                            </a>
                            <button class="share-btn share-copy" onclick="copyLink()">
                                <i class="fa fa-copy"></i> Copy Link
                            </button>
                        </div>
                        <div class="copy-success" id="copySuccess">Link copied to clipboard!</div>
                    </div>

                    <!-- Back link -->
                    <?php if (!empty($cat['slug'])): ?>
                    <a href="<?= SITE_URL ?>/nominees?event=<?= urlencode($eventSlug) ?>&category=<?= urlencode($cat['slug']) ?>"
                       class="np-back-link">
                        <i class="fa fa-arrow-left"></i>
                        Back to <?= htmlspecialchars($cat['name'] ?? 'Category') ?>
                    </a>
                    <?php endif; ?>

                </div>
            </div><!-- /col-lg-4 -->

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</div>
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>

<script>
var SHARE_URL  = <?= json_encode($shareUrl) ?>;
var SHARE_TEXT = <?= json_encode($shareText) ?>;

function copyLink() {
    var el = document.getElementById('copySuccess');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(SHARE_URL).then(function () { showCopied(el); });
    } else {
        var ta = document.createElement('textarea');
        ta.value = SHARE_URL;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        showCopied(el);
    }
}

function showCopied(el) {
    el.style.display = 'block';
    setTimeout(function () { el.style.display = 'none'; }, 2500);
}

function igShare(e) {
    e.preventDefault();
    /* Instagram has no web share intent — copy link + open app */
    if (navigator.share) {
        navigator.share({ title: SHARE_TEXT, url: SHARE_URL }).catch(function(){});
    } else {
        var ta = document.createElement('textarea');
        ta.value = SHARE_URL;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        var el = document.getElementById('copySuccess');
        el.textContent = 'Link copied — paste it in your Instagram story!';
        el.style.display = 'block';
        setTimeout(function () {
            el.style.display = 'none';
            el.textContent = 'Link copied to clipboard!';
        }, 3500);
    }
}
</script>

<?php include 'includes/footer-links.php'; ?>
</body>
</html>
