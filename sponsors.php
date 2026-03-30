<?php
include 'config/config.php';
include 'libs/App.php';

$galaResp    = tuqio_api('/api/public/events/dfa-gala-2026');
$galaEvent   = $galaResp['data'] ?? $galaResp ?? [];
$allSponsors = $galaEvent['sponsors'] ?? [];

// Group by tier
$tierOrder = ['title' => 1, 'partner' => 2, 'gold' => 3, 'silver' => 4, 'bronze' => 5];
usort($allSponsors, fn($a, $b) =>
    ($tierOrder[$a['tier'] ?? 'bronze'] ?? 9) <=> ($tierOrder[$b['tier'] ?? 'bronze'] ?? 9)
);
$tierLabels = [
    'title'   => 'Title Sponsor',
    'partner' => 'Official Partners',
    'gold'    => 'Gold Sponsors',
    'silver'  => 'Silver Sponsors',
    'bronze'  => 'Bronze Sponsors',
];
$grouped = [];
foreach ($allSponsors as $sp) {
    $grouped[$sp['tier'] ?? 'bronze'][] = $sp;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sponsors &amp; Partners | Digitally Fit Awards</title>
<meta name="description" content="Meet the sponsors and partners powering the Digitally Fit Awards Gala 2026 — East Africa's premier digital excellence awards.">
<meta name="author" content="Digitally Fit Awards">
<meta name="robots" content="index, follow">
<meta property="og:title" content="Sponsors & Partners | Digitally Fit Awards">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:description" content="Meet the sponsors and partners powering the Digitally Fit Awards Gala 2026.">
<meta property="og:site_name" content="Digitally Fit Awards">
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
.sponsor-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 14px;
    padding: 32px 24px;
    text-align: center;
    transition: all .25s;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
}
.sponsor-card:hover { box-shadow: 0 8px 30px rgba(5,55,50,.1); border-color: #be9b3f; transform: translateY(-3px); }
.sponsor-card img { height: 80px; max-width: 180px; object-fit: contain; }
.sponsor-card .sp-name { font-weight: 700; color: #0a0a0a; font-size: .95rem; }
.sponsor-card .sp-tier { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 2px 10px; border-radius: 20px; }
.tier-title   { background: #fdf0d5; color: #7a5a00; }
.tier-partner { background: #e8f4f0; color: #0a0a0a; }
.tier-gold    { background: #fef9ec; color: #a07c00; }
.tier-silver  { background: #f4f4f4; color: #555; }
.tier-bronze  { background: #fdf0e8; color: #7a4000; }
.tier-heading { font-size: 1.05rem; font-weight: 800; color: #0a0a0a; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 24px; padding-bottom: 10px; border-bottom: 2px solid rgba(190,155,63,.35); display: inline-block; }
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

<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/images/banner/banner.jpg);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>Sponsors &amp; Partners</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>Sponsors</li>
            </ul>
        </div>
    </div>
</section>

<section style="padding:70px 0;background:#f9fafb;">
    <div class="auto-container">

        <?php if (empty($allSponsors)): ?>
        <div class="text-center" style="padding:60px 0;color:#aaa;">
            <i class="flaticon-trophy-1" style="font-size:3rem;margin-bottom:16px;display:block;"></i>
            <p style="font-size:1rem;">Sponsor details coming soon. <a href="<?= SITE_URL ?>/contact" style="color:#be9b3f;font-weight:600;">Contact us</a> to become a sponsor.</p>
        </div>

        <?php else: ?>

        <?php foreach ($tierOrder as $tier => $order):
            if (empty($grouped[$tier])) continue;
            $isTitle = $tier === 'title';
        ?>
        <div style="margin-bottom:60px;">
            <div class="text-center" style="margin-bottom:32px;">
                <span class="tier-heading"><?= $tierLabels[$tier] ?? ucfirst($tier) ?></span>
            </div>
            <div class="row justify-content-center">
                <?php foreach ($grouped[$tier] as $sp):
                    $colClass = $isTitle ? 'col-lg-4 col-md-6' : ($tier === 'partner' ? 'col-lg-3 col-md-4 col-sm-6' : 'col-lg-3 col-md-4 col-sm-6');
                ?>
                <div class="<?= $colClass ?> mb-4">
                    <a href="<?= htmlspecialchars($sp['website'] ?? '#') ?>"
                       target="<?= !empty($sp['website']) ? '_blank' : '_self' ?>"
                       rel="noopener"
                       style="text-decoration:none;display:block;height:100%;">
                        <div class="sponsor-card">
                            <?php if (!empty($sp['logo'])): ?>
                            <img src="<?= htmlspecialchars($sp['logo']) ?>"
                                 alt="<?= htmlspecialchars($sp['name'] ?? '') ?>"
                                 onerror="this.style.display='none'">
                            <?php endif; ?>
                            <div class="sp-name"><?= htmlspecialchars($sp['name'] ?? '') ?></div>
                            <span class="sp-tier tier-<?= htmlspecialchars($tier) ?>"><?= $tierLabels[$tier] ?? ucfirst($tier) ?></span>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>

        <!-- Become a Sponsor CTA -->
        <div style="background:linear-gradient(135deg,#0a0a0a,#1a1a1a);border-radius:16px;padding:48px;text-align:center;color:#fff;margin-top:20px;">
            <h3 style="font-weight:800;margin-bottom:10px;">Interested in Sponsoring Digitally Fit Awards 2026?</h3>
            <p style="color:rgba(255,255,255,.8);max-width:520px;margin:0 auto 24px;">Partner with East Africa's premier digital excellence awards and put your brand in front of a passionate, digitally-engaged audience across the country.</p>
            <a href="<?= SITE_URL ?>/contact" class="theme-btn btn-style-one" style="background:#be9b3f;border-color:#be9b3f;">
                <span class="btn-title">Get in Touch →</span>
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
