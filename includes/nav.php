<?php
// Fetch events for nav (cached in session for 5 min)
$_navCacheKey = 'nav_events_' . floor(time() / 300);
if (!isset($_SESSION[$_navCacheKey])) {
    $navResp = tuqio_api('/api/public/events');
    $_SESSION[$_navCacheKey] = $navResp['data'] ?? [];
    // Clear old cache keys
    foreach ($_SESSION as $k => $v) {
        if (str_starts_with($k, 'nav_events_') && $k !== $_navCacheKey) unset($_SESSION[$k]);
    }
}
$_navEvents  = array_values($_SESSION[$_navCacheKey]);
$_navToday   = date('Y-m-d');
$_navFeatured = array_filter($_navEvents, fn($e) => !empty($e['is_featured']) && ($e['start_date'] ?? '') >= $_navToday);
$_navUpcoming = array_filter($_navEvents, fn($e) => ($e['start_date'] ?? '') >= $_navToday && empty($e['is_featured']));
$_navFeatured = array_slice(array_values($_navFeatured), 0, 3);
$_navUpcoming = array_slice(array_values($_navUpcoming), 0, 5);
?>
<div class="header-lower">
    <div class="auto-container">
        <div class="main-box">
            <div class="logo-box">
                <div class="logo">
                    <a href="<?php echo SITE_URL; ?>/">
                        <img src="<?php echo SITE_URL; ?>/assets/images/logo/dfa-logo.svg"
                             alt="Baraka Awards Kenya" title="Baraka Awards Kenya"
                             style="height:70px; width:auto; display:block;">
                    </a>
                </div>
            </div>

            <div class="nav-outer">
                <nav class="main-menu navbar-expand-md">
                    <div class="navbar-header">
                        <button class="navbar-toggler" type="button"
                                data-toggle="collapse" data-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                        <ul class="navigation clearfix">

                            <li class="current"><a href="<?php echo SITE_URL; ?>/">Home</a></li>

                            <li class="dropdown has-mega-menu"><a href="<?php echo SITE_URL; ?>/events">Events</a>
                                <div class="mega-menu" style="min-width:560px;padding:24px;background:#fff;box-shadow:0 10px 40px rgba(0,0,0,0.12);border-top:3px solid #BF9E44;">
                                    <div style="display:flex;gap:28px;">

                                        <?php if (!empty($_navFeatured)): ?>
                                        <!-- Featured column -->
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#BF9E44;margin-bottom:12px;">Featured</div>
                                            <?php foreach ($_navFeatured as $fe):
                                                $feSlug = $fe['slug'] ?? '';
                                                $feImg  = !empty($fe['thumbnail_image']) ? API_STORAGE . $fe['thumbnail_image']
                                                        : (!empty($fe['banner_image']) ? API_STORAGE . $fe['banner_image'] : '');
                                                $feDate = !empty($fe['start_date']) ? date('d M Y', strtotime($fe['start_date'])) : 'TBD';
                                            ?>
                                            <a href="<?php echo SITE_URL; ?>/event-detail?slug=<?php echo urlencode($feSlug); ?>"
                                               style="display:flex;gap:10px;align-items:center;padding:8px 0;border-bottom:1px solid #f5f5f5;text-decoration:none;">
                                                <div style="width:50px;height:38px;border-radius:5px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,#0d0d0d,#1a1a1a);">
                                                    <?php if ($feImg): ?>
                                                    <img src="<?php echo htmlspecialchars($feImg); ?>"
                                                         style="width:100%;height:100%;object-fit:cover;"
                                                         onerror="this.style.display='none'">
                                                    <?php endif; ?>
                                                </div>
                                                <div style="min-width:0;">
                                                    <div style="font-size:.82rem;font-weight:700;color:#0d0d0d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                                        <?php echo htmlspecialchars($fe['name'] ?? ''); ?>
                                                    </div>
                                                    <div style="font-size:.7rem;color:#aaa;"><?php echo $feDate; ?></div>
                                                    <?php if (!empty($fe['voting_is_open'])): ?>
                                                    <span style="font-size:.62rem;background:#BF9E44;color:#fff;padding:1px 7px;border-radius:20px;font-weight:700;">Voting Open</span>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                            <?php endforeach; ?>
                                            <a href="<?php echo SITE_URL; ?>/events" style="font-size:.78rem;color:#BF9E44;font-weight:600;display:block;margin-top:10px;">Browse all events →</a>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Upcoming list column -->
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#0d0d0d;margin-bottom:12px;">Upcoming</div>
                                            <?php if (!empty($_navUpcoming)): ?>
                                            <?php foreach ($_navUpcoming as $ue):
                                                $ueSlug = $ue['slug'] ?? '';
                                                $ueDate = !empty($ue['start_date']) ? date('d M', strtotime($ue['start_date'])) : 'TBD';
                                            ?>
                                            <a href="<?php echo SITE_URL; ?>/event-detail?slug=<?php echo urlencode($ueSlug); ?>"
                                               style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid #f5f5f5;text-decoration:none;">
                                                <span style="font-size:.82rem;color:#333;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px;">
                                                    <?php echo htmlspecialchars($ue['name'] ?? ''); ?>
                                                </span>
                                                <span style="font-size:.72rem;color:#aaa;flex-shrink:0;margin-left:8px;"><?php echo $ueDate; ?></span>
                                            </a>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <p style="font-size:.82rem;color:#aaa;">No upcoming events</p>
                                            <?php endif; ?>
                                            <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;">
                                                <a href="<?php echo SITE_URL; ?>/events?filter=upcoming"
                                                   style="font-size:.75rem;padding:5px 12px;background:#0d0d0d;color:#fff;border-radius:4px;text-decoration:none;">Upcoming</a>
                                                <a href="<?php echo SITE_URL; ?>/events?filter=past"
                                                   style="font-size:.75rem;padding:5px 12px;border:1px solid #eee;color:#555;border-radius:4px;text-decoration:none;">Past</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </li>

                            <li><a href="<?php echo SITE_URL; ?>/nominees">Nominees</a></li>

                            <li><a href="<?php echo SITE_URL; ?>/tickets">Tickets</a></li>

                            <li><a href="<?php echo SITE_URL; ?>/gallery">Gallery</a></li>

                            <li><a href="<?php echo SITE_URL; ?>/blog">Blog</a></li>

                            <li class="dropdown"><a href="#">About</a>
                                <ul>
                                    <li><a href="<?php echo SITE_URL; ?>/about">About Baraka Awards</a></li>
                                    <li><a href="<?php echo SITE_URL; ?>/faq">FAQ</a></li>
                                    <li><a href="<?php echo SITE_URL; ?>/contact">Contact</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>

                <div class="outer-box clearfix">
                    <div class="search-box-btn search-btn search-box-outer">
                        <span class="icon fa fa-search"></span>
                    </div>
                    <div class="btn-box">
                        <a href="<?php echo SITE_URL; ?>/nominees"
                           class="theme-btn btn-style-one"
                           style="background:#BF9E44;border-color:#BF9E44;color:#fff;">
                            <span class="btn-title">Vote Now <i class="fa fa-arrow-right"></i></span>
                        </a>
                    </div>
                    <button class="nav-toggler"><i class="flaticon flaticon-menu-2"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
