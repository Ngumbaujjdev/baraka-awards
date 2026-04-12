<?php
include 'config/config.php';
include 'libs/App.php';

// ── Fetch all DFA events for switcher ───────────────────
$_dfaCatEvsList = tuqio_api('/api/public/events?client=digitally-fit-awards');
$_allDfaCatEvs  = $_dfaCatEvsList['data'] ?? [];
usort($_allDfaCatEvs, fn($a,$b) => strcmp($a['start_date'] ?? '9999-12-31', $b['start_date'] ?? '9999-12-31'));
// Default: first event with banner_image, else latest
$_defaultCatEv = null;
foreach ($_allDfaCatEvs as $_e) {
    if (!$_defaultCatEv && !empty($_e['banner_image'])) { $_defaultCatEv = $_e; }
}
if (!$_defaultCatEv && !empty($_allDfaCatEvs)) { $_defaultCatEv = end($_allDfaCatEvs); }

// Resolve event slug
$eventSlug = $_GET['event'] ?? ($_defaultCatEv['slug'] ?? '');

// Fetch event details (for dates/status)
$eventResp    = tuqio_api('/api/public/events/' . urlencode($eventSlug));
$galaEvent    = $eventResp['event'] ?? $eventResp['data'] ?? $eventResp ?? [];
if (empty($galaEvent) || !isset($galaEvent['slug'])) {
    // fallback: try root key
    foreach ($eventResp as $k => $v) {
        if (is_array($v) && isset($v['slug'])) { $galaEvent = $v; break; }
    }
}

// Fetch categories/nominees
$nomResp       = tuqio_api('/api/public/events/' . urlencode($eventSlug) . '/nominees');
$allCategories = $nomResp['categories'] ?? [];

$isVotingOpen   = !empty($galaEvent['voting_is_open']);
$nomOpen        = false;
$nomStart = $nomEnd = null;
foreach ($allCategories as $_c) {
    if (!empty($_c['nomination_starts_at'])) {
        $nomStart = $_c['nomination_starts_at'];
        $nomEnd   = $_c['nomination_ends_at'] ?? null;
        $nomOpen  = strtotime($nomStart) <= time() && ($nomEnd ? strtotime($nomEnd) >= time() : true);
        break;
    }
}

$voteOpens  = !empty($galaEvent['voting_opens_at'])  ? strtotime($galaEvent['voting_opens_at'])  : 0;
$voteCloses = !empty($galaEvent['voting_closes_at']) ? strtotime($galaEvent['voting_closes_at']) : 0;

// Build group lookup: category_id => group_name
$_catGroupLookup = [];
foreach ($nomResp['groups'] ?? [] as $_grp) {
    foreach ($_grp['categories'] ?? [] as $_gc) {
        $_catGroupLookup[$_gc['id']] = $_grp['name'];
    }
}
// Build groups list for filter pills: [{name, slugs}]
$_catGroupsList = array_values(array_map(fn($g) => [
    'name'  => $g['name'],
    'slugs' => array_column($g['categories'] ?? [], 'slug'),
], array_filter($nomResp['groups'] ?? [], fn($g) => !empty($g['categories']))));
$_catGroupsJson = json_encode($_catGroupsList);

$totalCats = count($allCategories);
$catsJson  = json_encode(array_map(fn($c) => [
    'id'     => $c['id'] ?? '',
    'name'   => $c['name'] ?? '',
    'slug'   => $c['slug'] ?? '',
    'desc'   => $c['description'] ?? '',
    'image'  => !empty($c['image']) ? (str_starts_with($c['image'], 'http') ? $c['image'] : API_STORAGE . $c['image']) : '',
    'status' => $c['nomination_status'] ?? 'collecting',
    'count'  => count($c['nominees'] ?? []),
    'group'  => $_catGroupLookup[$c['id'] ?? ''] ?? '',
], $allCategories));

$eventName = $galaEvent['name'] ?? 'Digitally Fit Awards Gala 2026';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Award Categories | <?= htmlspecialchars($eventName) ?> | Digitally Fit Awards</title>
<meta name="description" content="Browse all award categories for <?= htmlspecialchars($eventName) ?>. Nominate your favourite gospel artists in <?= $totalCats ?> categories.">
<meta name="robots" content="index, follow">
<meta property="og:title" content="Award Categories | <?= htmlspecialchars($eventName) ?>">
<meta property="og:description" content="Browse all <?= $totalCats ?> award categories for <?= htmlspecialchars($eventName) ?>.">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link href="<?= SITE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/responsive.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/custom.css" rel="stylesheet">
<link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
<link rel="shortcut icon" href="<?= SITE_URL ?>/assets/images/favicon/favicon.ico">
<style>
/* ── Page-scoped styles ─────────────────────────────── */
.cats-hero { background: linear-gradient(160deg,#0a0a0a 0%,#0a0a0a 100%); padding: 80px 0; }
.cat-card-wrap { margin-bottom: 24px; }
.cat-card-inner {
    background: #fff; border: 1px solid rgba(190,155,63,.2);
    border-radius: 14px; overflow: hidden; cursor: pointer; height: 100%;
    transition: all .25s;
}
.cat-card-inner:hover {
    border-color: rgba(190,155,63,.6); background: #fffbf0;
    transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.25);
}
.cat-img-area { height: 130px; overflow: hidden; position: relative; }
.cat-img-area img { width:100%; height:100%; object-fit:cover; }
.cat-img-overlay { position:absolute;inset:0;background:linear-gradient(to bottom,transparent 35%,rgba(5,55,50,.7)); }
.cat-img-placeholder { height:130px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);display:flex;align-items:center;justify-content:center; }
.cat-body { padding: 15px; }
.cat-name { color:#0a0a0a;font-weight:700;font-size:.9rem;line-height:1.35;margin:0 0 6px; }
.cat-desc { color:#555;font-size:.77rem;line-height:1.5;margin-bottom:10px; }
.cat-footer { display:flex;align-items:center;justify-content:space-between; }
.cat-count { font-size:.7rem;color:#888; }
.cat-cta   { font-size:.75rem;color:#be9b3f;font-weight:700;text-decoration:none; }
.cat-badge {
    font-size:.63rem;font-weight:700;padding:2px 8px;border-radius:20px;
    background:rgba(0,0,0,.25);white-space:nowrap;
}
/* Sidebar */
.cats-sidebar .sidebar-info-card {
    background:#f9fafb;border-radius:12px;padding:20px;margin-bottom:20px;
}
.cats-sidebar .info-row { display:flex;align-items:flex-start;gap:10px;font-size:.85rem;color:#444;margin-bottom:10px; }
.cats-sidebar .info-row i { color:#be9b3f;width:16px;margin-top:2px;flex-shrink:0; }
.cats-sidebar .info-row strong { color:#0a0a0a; }
/* Search */
.cat-search-wrap { max-width:480px;margin:0 auto 32px;position:relative; }
.cat-search-wrap input { width:100%;padding:13px 46px 13px 20px;border:none;border-radius:30px;font-size:.9rem;color:#333;box-shadow:0 4px 22px rgba(0,0,0,.22);outline:none; }
.cat-search-wrap i { position:absolute;right:16px;top:50%;transform:translateY(-50%);color:#bbb;pointer-events:none; }
/* Pagination */
#catsPagination { display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin:24px 0 0; }
/* Modal */
#catsModal .modal-content { border-radius:16px;overflow:hidden;border:none; }
#catsModalImg { height:230px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);position:relative; }
#catsModalImg img { width:100%;height:100%;object-fit:cover; }
#catsModalPlaceholder { position:absolute;inset:0;display:flex;align-items:center;justify-content:center; }
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

<!-- Page title banner -->
<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/slides/kenya-breadcrump.webp);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>Award Categories</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li><?= htmlspecialchars($eventName) ?></li>
                <li>Categories</li>
            </ul>
        </div>
    </div>
</section>

<!-- ── Main content ── -->
<div class="sidebar-page-container" style="padding:70px 0;">
<div class="auto-container">
<div class="row clearfix">

    <!-- Content -->
    <div class="content-side col-lg-9 col-md-12 col-sm-12">
        <?php if (empty($allCategories)): ?>
        <div class="text-center" style="padding:80px 0;">
            <i class="fas fa-trophy" style="font-size:3rem;color:#be9b3f;opacity:.3;"></i>
            <h4 style="margin-top:20px;color:#0a0a0a;">Categories Coming Soon</h4>
            <p class="text-muted">Categories for <?= htmlspecialchars($eventName) ?> will be published soon.</p>
        </div>
        <?php else: ?>

        <!-- Event switcher pills -->
        <?php if (count($_allDfaCatEvs) > 1): ?>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:22px;">
            <?php foreach ($_allDfaCatEvs as $_ev): $isAct = ($_ev['slug'] ?? '') === $eventSlug; ?>
            <a href="<?= SITE_URL ?>/categories?event=<?= urlencode($_ev['slug'] ?? '') ?>"
               style="display:inline-block;padding:7px 18px;border-radius:20px;font-size:.82rem;font-weight:700;text-decoration:none;
                      <?= $isAct ? 'background:#be9b3f;color:#fff;border:2px solid #be9b3f;' : 'background:#fff;color:#555;border:2px solid #ddd;' ?>">
                <?= htmlspecialchars($_ev['name'] ?? '') ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Stats + search header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
            <div>
                <h4 style="color:#0a0a0a;font-weight:800;margin:0;"><?= $totalCats ?> Award <?= $totalCats === 1 ? 'Category' : 'Categories' ?></h4>
                <p style="color:#888;font-size:.85rem;margin:0;"><?= htmlspecialchars($eventName) ?></p>
            </div>
            <div style="position:relative;min-width:240px;">
                <input type="text" id="catsSearch" placeholder="Search categories…" autocomplete="off"
                       style="width:100%;padding:10px 40px 10px 16px;border:2px solid #eee;border-radius:30px;font-size:.88rem;color:#333;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#be9b3f'" onblur="this.style.borderColor='#eee'">
                <i class="fas fa-search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#bbb;pointer-events:none;"></i>
            </div>
        </div>

        <!-- Group filter pills -->
        <div id="catsGroupPills" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:22px;"></div>

        <!-- Category grid — rendered by JS -->
        <div class="row" id="catsGrid" style="min-height:200px;"></div>

        <!-- No results -->
        <div id="catsNoResults" style="display:none;text-align:center;padding:60px 0;color:#aaa;">
            <i class="fas fa-search" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:12px;"></i>
            No categories match your search.
        </div>

        <!-- Pagination -->
        <div id="catsPagination"></div>

        <?php endif; ?>
    </div>
    <!-- /content -->

    <!-- Sidebar -->
    <div class="sidebar-side col-lg-3 col-md-12 col-sm-12 cats-sidebar">
        <aside class="sidebar padding-left">

            <!-- Action CTA -->
            <div class="sidebar-info-card">
                <h6 style="font-weight:800;color:#0a0a0a;margin-bottom:14px;"><i class="fas fa-info-circle" style="color:#be9b3f;margin-right:6px;"></i>Get Involved</h6>
                <?php if ($nomOpen): ?>
                <div class="info-row"><i class="fas fa-pen-nib"></i><div><strong>Nominations open</strong><br>Submit a nomination for any category below.</div></div>
                <a href="<?= SITE_URL ?>/nominate?event=<?= urlencode($eventSlug) ?>" class="theme-btn btn-style-one" style="display:block;text-align:center;font-size:.82rem;margin-top:10px;">
                    <span class="btn-title">Nominate Now</span>
                </a>
                <?php elseif ($isVotingOpen): ?>
                <div class="info-row"><i class="fas fa-vote-yea"></i><div><strong>Voting is live!</strong><br>Browse nominees and cast your votes.</div></div>
                <a href="<?= SITE_URL ?>/nominees?event=<?= urlencode($eventSlug) ?>" class="theme-btn btn-style-one" style="display:block;text-align:center;font-size:.82rem;margin-top:10px;background:#be9b3f;border-color:#be9b3f;">
                    <span class="btn-title">Vote Now</span>
                </a>
                <?php else: ?>
                <div class="info-row"><i class="fas fa-clock"></i><div>Nominations and voting dates will be announced soon.</div></div>
                <?php endif; ?>
            </div>

            <!-- Key dates -->
            <div class="sidebar-info-card">
                <h6 style="font-weight:800;color:#0a0a0a;margin-bottom:14px;"><i class="fas fa-calendar-alt" style="color:#be9b3f;margin-right:6px;"></i>Key Dates</h6>
                <?php if ($nomStart): ?>
                <div class="info-row"><i class="fas fa-pen-nib"></i><div><strong>Nominations</strong><br><?= date('d M Y', strtotime($nomStart)) ?><?= $nomEnd ? ' &ndash; '.date('d M Y', strtotime($nomEnd)) : '' ?></div></div>
                <?php endif; ?>
                <?php if ($voteOpens): ?>
                <div class="info-row"><i class="fas fa-vote-yea"></i><div><strong>Voting opens</strong><br><?= date('d M Y', $voteOpens) ?><?= $voteCloses ? ' &ndash; '.date('d M Y', $voteCloses) : '' ?></div></div>
                <?php endif; ?>
                <div class="info-row"><i class="fas fa-star"></i><div><strong>Gala Night</strong><br>December 5, 2026 &mdash; Villa Rosa Kempinski, Nairobi</div></div>
            </div>

            <!-- Quick links -->
            <div class="sidebar-widget">
                <h5 class="sidebar-title">Explore</h5>
                <div class="widget-content">
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php
                        $links = [
                            ['href' => SITE_URL.'/event-detail?slug='.$eventSlug, 'icon' => 'fa-calendar-alt', 'label' => 'Event Details'],
                            ['href' => SITE_URL.'/nominees?event='.$eventSlug,    'icon' => 'fa-users',        'label' => 'View Nominees'],
                            ['href' => SITE_URL.'/nominate?event='.$eventSlug,    'icon' => 'fa-pen-nib',      'label' => 'Submit a Nomination'],
                            ['href' => SITE_URL.'/vote-bundle?event='.$eventSlug, 'icon' => 'fa-vote-yea',     'label' => 'Vote Bundles'],
                        ];
                        foreach ($links as $i => $l): ?>
                        <li style="<?= $i < count($links)-1 ? 'border-bottom:1px solid #f0f0f0;' : '' ?>">
                            <a href="<?= htmlspecialchars($l['href']) ?>" style="display:flex;align-items:center;gap:10px;padding:10px 0;color:#333;text-decoration:none;font-size:.88rem;font-weight:500;" onmouseover="this.style.color='#be9b3f'" onmouseout="this.style.color='#333'">
                                <i class="fas <?= $l['icon'] ?>" style="color:#be9b3f;width:18px;text-align:center;flex-shrink:0;"></i>
                                <?= htmlspecialchars($l['label']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        </aside>
    </div>
    <!-- /sidebar -->

</div>
</div>
</div>

<!-- Category detail modal -->
<div class="modal fade" id="catsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content">
      <div id="catsModalImg">
        <button type="button" class="close" data-dismiss="modal" style="position:absolute;top:12px;right:14px;color:#fff;opacity:1;font-size:1.5rem;background:rgba(0,0,0,.3);border:none;border-radius:50%;width:32px;height:32px;line-height:1;z-index:2;">&times;</button>
        <img id="catsModalImgEl" src="" alt="" style="display:none;">
        <div id="catsModalPlaceholder"><i class="flaticon-trophy-1" style="font-size:4rem;color:rgba(190,155,63,.45);"></i></div>
      </div>
      <div class="modal-body" style="padding:26px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
          <h5 id="catsModalName" style="font-weight:800;color:#0a0a0a;margin:0;line-height:1.3;"></h5>
          <span id="catsModalBadge" style="font-size:.7rem;font-weight:700;padding:3px 12px;border-radius:20px;white-space:nowrap;margin-left:12px;margin-top:3px;"></span>
        </div>
        <p id="catsModalDesc" style="color:#555;font-size:.9rem;line-height:1.75;margin-bottom:14px;"></p>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
          <span id="catsModalCount" style="font-size:.82rem;color:#be9b3f;font-weight:700;"></span>
          <a id="catsModalCta" href="#" class="theme-btn btn-style-one" style="font-size:.8rem;padding:8px 20px;"><span class="btn-title">Nominate &rarr;</span></a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</div>
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>

<script>
(function(){
    var CATS        = <?= $catsJson ?>;
    var GROUPS      = <?= $_catGroupsJson ?>;
    var PAGE_SIZE   = 12;
    var isVoting    = <?= $isVotingOpen ? 'true' : 'false' ?>;
    var nomUrl      = '<?= SITE_URL ?>/nominate?event=<?= urlencode($eventSlug) ?>';
    var nomineesUrl = '<?= SITE_URL ?>/nominees?event=<?= urlencode($eventSlug) ?>';
    var filtered    = CATS.slice();
    var currentPage = 1;
    var activeGroup = '';

    var statusColors = {collecting:'#22c55e', closed:'#ef4444', completed:'#6366f1'};
    var statusLabels = {collecting:'Accepting Nominations', closed:'Closed', completed:'Done'};

    // ── Group filter pills ──────────────────────────────
    function hasGroupData() {
        return GROUPS.length > 0 && GROUPS.some(function(g){ return g.slugs && g.slugs.length > 0; });
    }

    function renderGroupPills() {
        var wrap = document.getElementById('catsGroupPills');
        if (!wrap || !hasGroupData()) { if(wrap) wrap.style.display='none'; return; }
        wrap.style.display = 'flex';
        var html = '<button onclick="setGroupFilter(\'\')" style="padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;'
            + (activeGroup==='' ? 'background:#be9b3f;color:#fff;border:2px solid #be9b3f;' : 'background:#fff;color:#555;border:2px solid #ddd;')
            + '">All</button>';
        GROUPS.forEach(function(g) {
            html += '<button onclick="setGroupFilter(\''+g.name.replace(/'/g,"\\'")+'\')" style="padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;'
                + (activeGroup===g.name ? 'background:#be9b3f;color:#fff;border:2px solid #be9b3f;' : 'background:#fff;color:#555;border:2px solid #ddd;')
                + '">'+g.name+'</button>';
        });
        wrap.innerHTML = html;
    }

    window.setGroupFilter = function(groupName) {
        activeGroup = groupName;
        renderGroupPills();
        applyFilters();
    };

    function applyFilters() {
        var q = (document.getElementById('catsSearch') ? document.getElementById('catsSearch').value.trim().toLowerCase() : '');
        filtered = CATS.filter(function(c) {
            var matchQ   = !q   || c.name.toLowerCase().indexOf(q) !== -1;
            var matchGrp = !activeGroup;
            if (!matchGrp) {
                var grp = GROUPS.find(function(g){ return g.name === activeGroup; });
                matchGrp = grp ? grp.slugs.indexOf(c.slug) !== -1 : false;
            }
            return matchQ && matchGrp;
        });
        currentPage = 1;
        renderGrid();
    }

    function buildCard(cat) {
        var color  = statusColors[cat.status] || '#888';
        var label  = statusLabels[cat.status] || cat.status;
        var imgHtml= cat.image
            ? '<div class="cat-img-area"><img src="'+cat.image+'" alt="" onerror="this.parentElement.outerHTML=\'<div class=cat-img-placeholder><i class=flaticon-trophy-1 style=font-size:2.5rem;color:rgba(190,155,63,.35)></i></div>\'"><div class="cat-img-overlay"></div></div>'
            : '<div class="cat-img-placeholder"><i class="flaticon-trophy-1" style="font-size:2.5rem;color:rgba(190,155,63,.35);"></i></div>';
        var cta = (!isVoting && cat.status==='collecting')
            ? '<a href="'+nomUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" class="cat-cta">Nominate &rarr;</a>'
            : '<a href="'+nomineesUrl+'&category='+cat.slug+'" onclick="event.stopPropagation();" class="cat-cta">View Nominees &rarr;</a>';
        var count = cat.count > 0
            ? '<span class="cat-count"><i class="fas fa-users" style="margin-right:3px;"></i>'+cat.count+' nominated</span>'
            : '<span class="cat-count">No nominees yet</span>';
        var grpBadge = cat.group ? '<span style="font-size:.6rem;font-weight:700;color:#be9b3f;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;display:block;">'+cat.group+'</span>' : '';
        return '<div class="col-lg-4 col-md-6 col-sm-12 cat-card-wrap">'
            + '<div class="cat-card-inner" onclick="openCatsModal('+JSON.stringify(cat)+')">'
            + imgHtml
            + '<div class="cat-body">'
            + grpBadge
            + '<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">'
            + '<h6 class="cat-name">'+cat.name+'</h6>'
            + '<span class="cat-badge" style="color:'+color+';margin-left:8px;margin-top:2px;">'+label+'</span>'
            + '</div>'
            + (cat.desc ? '<p class="cat-desc">'+cat.desc.substring(0,85)+(cat.desc.length>85?'\u2026':'')+'</p>' : '')
            + '<div class="cat-footer">'+count+cta+'</div>'
            + '</div></div></div>';
    }

    function renderGrid() {
        var grid  = document.getElementById('catsGrid');
        var noRes = document.getElementById('catsNoResults');
        if (!grid) return;
        if (!filtered.length) { grid.innerHTML=''; noRes.style.display='block'; renderPag(); return; }
        noRes.style.display='none';
        var s=(currentPage-1)*PAGE_SIZE, e=s+PAGE_SIZE;
        grid.innerHTML = filtered.slice(s,e).map(buildCard).join('');
        renderPag();
    }

    function renderPag() {
        var pag = document.getElementById('catsPagination');
        if (!pag) return;
        var total = Math.ceil(filtered.length / PAGE_SIZE);
        if (total <= 1) { pag.innerHTML=''; return; }
        var html='';
        for(var i=1;i<=total;i++){
            var a=i===currentPage;
            html+='<button onclick="goCatsPage('+i+')" style="min-width:36px;height:36px;border-radius:8px;border:2px solid '+(a?'#be9b3f':'#ddd')+';background:'+(a?'#be9b3f':'#fff')+';color:'+(a?'#fff':'#888')+';font-weight:700;font-size:.82rem;cursor:pointer;padding:0 10px;transition:all .2s;">'+i+'</button>';
        }
        pag.innerHTML=html;
    }

    window.goCatsPage = function(p){
        currentPage=p; renderGrid();
        window.scrollTo({top:document.getElementById('catsGrid').getBoundingClientRect().top+scrollY-80,behavior:'smooth'});
    };

    window.openCatsModal = function(cat) {
        var color = statusColors[cat.status]||'#888';
        var label = statusLabels[cat.status]||cat.status;
        document.getElementById('catsModalName').textContent  = cat.name;
        document.getElementById('catsModalDesc').textContent  = cat.desc || 'No description available.';
        document.getElementById('catsModalBadge').textContent = label;
        document.getElementById('catsModalBadge').style.cssText = 'font-size:.7rem;font-weight:700;padding:3px 12px;border-radius:20px;white-space:nowrap;margin-left:12px;margin-top:3px;background:rgba(0,0,0,.06);color:'+color;
        document.getElementById('catsModalCount').textContent = cat.count>0 ? cat.count+' nominee'+(cat.count!==1?'s':'')+' so far' : 'No nominees yet';
        var cta = document.getElementById('catsModalCta');
        if (!isVoting && cat.status==='collecting') {
            cta.href = nomUrl+'&category='+cat.slug;
            cta.querySelector('.btn-title').textContent = 'Nominate for this category \u2192';
        } else {
            cta.href = nomineesUrl+'&category='+cat.slug;
            cta.querySelector('.btn-title').textContent = 'View Nominees \u2192';
        }
        var img = document.getElementById('catsModalImgEl');
        var ph  = document.getElementById('catsModalPlaceholder');
        if (cat.image) { img.src=cat.image; img.style.display='block'; ph.style.display='none'; }
        else { img.style.display='none'; ph.style.display='flex'; }
        if (typeof $ !== 'undefined') $('#catsModal').modal('show');
    };

    document.getElementById('catsSearch').addEventListener('input', function(){
        applyFilters();
    });

    renderGroupPills();
    renderGrid();
}());
</script>
</body>
</html>
