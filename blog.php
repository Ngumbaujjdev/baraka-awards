<?php
include 'config/config.php';
include 'libs/App.php';
include 'data/static-articles.php'; // $STATIC_ARTICLES

$page      = (int)($_GET['page'] ?? 1);
$search    = trim($_GET['search'] ?? '');
$catFilter = trim($_GET['category'] ?? '');

$queryParams = [
    'client'   => CLIENT_SLUG,
    'page'     => $page,
    'search'   => $search,
    'category' => $catFilter,
];
$queryString = http_build_query(array_filter($queryParams));

$resp        = tuqio_api('/api/public/blog?' . $queryString);
$posts       = $resp['data'] ?? [];
$currentPage = $resp['current_page'] ?? 1;
$lastPage    = $resp['last_page'] ?? 1;
$totalItems  = $resp['total'] ?? 0;

// Fall back to static articles when API has nothing
if (empty($posts)) {
    $pool = $STATIC_ARTICLES;

    // apply search filter
    if ($search !== '') {
        $pool = array_values(array_filter($pool, fn($a) =>
            stripos($a['title'], $search) !== false ||
            stripos($a['excerpt'], $search) !== false
        ));
    }
    // apply category filter
    if ($catFilter !== '') {
        $pool = array_values(array_filter($pool, fn($a) =>
            isset($a['category']['name']) && stripos($a['category']['name'], $catFilter) !== false
        ));
    }

    $totalItems  = count($pool);
    $perPage     = 9;
    $lastPage    = max(1, (int)ceil($totalItems / $perPage));
    $currentPage = max(1, min($page, $lastPage));
    $posts       = array_slice($pool, ($currentPage - 1) * $perPage, $perPage);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>Articles &amp; News | Baraka Awards Kenya</title>
<meta name="description" content="Stay up to date with the latest news, articles, and event updates from Baraka Awards Kenya — Kenya's premier event management platform.">
<meta name="keywords" content="Baraka Awards Kenya blog, Kenya events news, awards articles Kenya, event updates Nairobi, Baraka Awards Kenya articles">
<meta name="author" content="Baraka Awards Kenya">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= SITE_URL ?>/blog.php">

<!-- Schema.org microdata -->
<meta itemprop="name" content="Articles & News | Baraka Awards Kenya">
<meta itemprop="description" content="Latest news, articles, and event updates from Baraka Awards Kenya.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="Articles &amp; News | Baraka Awards Kenya">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= SITE_URL ?>/blog.php">
<meta property="og:description" content="Latest news, articles, and event updates from Baraka Awards Kenya.">
<meta property="og:site_name" content="Baraka Awards Kenya">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@barakaawards">
<meta name="twitter:title" content="Articles &amp; News | Baraka Awards Kenya">
<meta name="twitter:description" content="Latest news, articles, and event updates from Baraka Awards Kenya.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>","contactPoint":{"@type":"ContactPoint","telephone":"+254710388288","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support"},"sameAs":["https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://www.facebook.com/share/p/1DJyLwtvqf/","https://twitter.com/barakaawards","https://www.tiktok.com/@barakaawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?= SITE_URL ?>/"},{"@type":"ListItem","position":2,"name":"Blog","item":"<?= SITE_URL ?>/blog.php"}]}
</script>

<!-- JSON-LD: Blog -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Blog","name":"Articles & News | Baraka Awards Kenya","url":"<?= SITE_URL ?>/blog.php","description":"Latest news, articles, and updates from Baraka Awards Kenya.","publisher":{"@type":"Organization","name":"Baraka Awards Kenya","url":"<?= SITE_URL ?>"}}
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
.blog-card { background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);height:100%;transition:transform .3s,box-shadow .3s; }
.blog-card:hover { transform:translateY(-5px);box-shadow:0 14px 36px rgba(0,0,0,0.11); }
.blog-card .card-thumb { height:200px;overflow:hidden;position:relative; }
.blog-card .card-thumb img { width:100%;height:100%;object-fit:cover;transition:transform .4s; }
.blog-card:hover .card-thumb img { transform:scale(1.05); }
.blog-card .cat-tag {
    position:absolute;top:14px;left:14px;background:#be9b3f;color:#fff;
    font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;
    text-transform:uppercase;letter-spacing:1px;
}
.blog-card .card-body { padding:22px; }
.blog-card .post-meta { display:flex;gap:14px;flex-wrap:wrap;margin-bottom:10px; }
.blog-card .post-meta span { font-size:.78rem;color:#999; }
.blog-card .post-meta i { color:#be9b3f;margin-right:3px; }
.blog-card h4 { font-size:1.05rem;font-weight:700;color:#0a0a0a;margin-bottom:10px;line-height:1.35; }
.blog-card h4 a { color:inherit;text-decoration:none; }
.blog-card h4 a:hover { color:#be9b3f; }
.blog-card .excerpt { font-size:.85rem;color:#777;line-height:1.65;margin-bottom:16px; }
.placeholder-thumb { height:200px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);display:flex;align-items:center;justify-content:center; }
.placeholder-thumb i { font-size:2.5rem;color:rgba(255,255,255,.3); }
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

<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/slides/kenya-breadcrump.webp);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>Baraka Awards News &amp; Updates</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>Blog</li>
            </ul>
        </div>
    </div>
</section>

<section class="section-light">
    <div class="auto-container">

        <?php if ($search !== '' || $catFilter !== ''): ?>
        <div class="filter-notice mb-4 d-flex align-items-center gap-3 flex-wrap">
            <span class="text-muted" style="font-size:.9rem;">
                <?php if ($catFilter !== ''): ?>
                Showing articles in <strong><?= htmlspecialchars($catFilter) ?></strong>
                <?php elseif ($search !== ''): ?>
                Search results for "<strong><?= htmlspecialchars($search) ?></strong>"
                <?php endif; ?>
                — <?= $totalItems ?> found
            </span>
            <a href="<?= SITE_URL ?>/blog" class="badge bg-secondary text-decoration-none" style="font-size:.78rem;">
                <i class="fas fa-times me-1"></i>Clear filter
            </a>
        </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
        <div class="text-center empty-state">
            <i class="fas fa-newspaper empty-icon"></i>
            <h4>No Articles Yet</h4>
            <p class="text-muted">Check back soon for news and updates.</p>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($posts as $post): ?>
            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp">
                <div class="blog-card">
                    <div class="card-thumb">
                        <?php if (!empty($post['featured_image']) && $post['featured_image'] !== 'null'): ?>
                        <img src="<?= htmlspecialchars($post['featured_image']) ?>"
                             alt="<?= htmlspecialchars($post['title']) ?>"
                             onerror="this.parentElement.innerHTML='<div class=\'placeholder-thumb\'><i class=\'fas fa-newspaper\'></i></div>'">
                        <?php else: ?>
                        <div class="placeholder-thumb"><i class="fas fa-newspaper"></i></div>
                        <?php endif; ?>
                        <?php if (!empty($post['category'])): ?>
                        <span class="cat-tag"><?= htmlspecialchars($post['category']['name']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="post-meta">
                            <?php if (!empty($post['published_at'])): ?>
                            <span><i class="fa fa-calendar-alt"></i><?= date('d M Y', strtotime($post['published_at'])) ?></span>
                            <?php endif; ?>
                            <span><i class="fa fa-user"></i>Baraka Awards</span>
                        </div>
                        <h4><a href="<?= SITE_URL ?>/blog-single?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h4>
                        <?php if (!empty($post['excerpt'])): ?>
                        <p class="excerpt"><?= htmlspecialchars(mb_strimwidth($post['excerpt'], 0, 110, '…')) ?></p>
                        <?php endif; ?>
                        <a href="<?= SITE_URL ?>/blog-single?slug=<?= urlencode($post['slug']) ?>" class="blog-readmore">
                            Read More <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($lastPage > 1): ?>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <nav aria-label="Blog Pagination">
                    <ul class="pagination justify-content-center">
                        <?php
                        $baseQuery = $_GET;
                        // Previous Button
                        if ($currentPage > 1) {
                            $baseQuery['page'] = $currentPage - 1;
                            $prevUrl = SITE_URL . '/blog?' . http_build_query(array_filter($baseQuery));
                            echo '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($prevUrl) . '">Previous</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                        }

                        // Page Numbers
                        for ($i = 1; $i <= $lastPage; $i++) {
                            $baseQuery['page'] = $i;
                            $pageUrl = SITE_URL . '/blog?' . http_build_query(array_filter($baseQuery));
                            $activeClass = ($i === $currentPage) ? 'active' : '';
                            echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="' . htmlspecialchars($pageUrl) . '">' . $i . '</a></li>';
                        }

                        // Next Button
                        if ($currentPage < $lastPage) {
                            $baseQuery['page'] = $currentPage + 1;
                            $nextUrl = SITE_URL . '/blog?' . http_build_query(array_filter($baseQuery));
                            echo '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($nextUrl) . '">Next</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</div>
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>
</body>
</html>
