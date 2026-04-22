<?php
include 'config/config.php';
include 'libs/App.php';

$status      = $_GET['status']  ?? 'success';   // success | failed | error
$nomineeSlug = trim($_GET['nominee'] ?? '');
$eventSlug   = trim($_GET['event']   ?? '');
$votes       = (int) ($_GET['votes']  ?? 0);

$isSuccess = $status === 'success';

// Fetch event + nominee only for success state
$event   = null;
$nominee = null;

if ($eventSlug) {
    $resp  = tuqio_api('/api/public/events/' . urlencode($eventSlug));
    $event = $resp['event'] ?? null;
}

if ($isSuccess && $eventSlug && $nomineeSlug) {
    $nomResp = tuqio_api('/api/public/events/' . urlencode($eventSlug) . '/nominees');
    foreach ($nomResp['categories'] ?? [] as $cat) {
        foreach (($cat['candidates'] ?? $cat['nominees'] ?? []) as $n) {
            if (($n['slug'] ?? '') === $nomineeSlug) {
                $nominee = $n;
                $nominee['category_name'] = $cat['name'] ?? '';
                break 2;
            }
        }
    }
}

$nomineesUrl  = SITE_URL . '/nominees' . ($eventSlug ? '?event=' . urlencode($eventSlug) : '');
$voteAgainUrl = SITE_URL . '/vote-bundle' . ($eventSlug ? '?event=' . urlencode($eventSlug) : '');
if ($nominee && !empty($nominee['id'])) {
    $voteAgainUrl .= '&nominee=' . (int)$nominee['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Votes Cast! | Baraka Awards Kenya</title>
<link href="<?= SITE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/responsive.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/assets/css/custom.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
.vote-hero {
    background:linear-gradient(135deg,#0a0a0a,#1a1a1a);
    padding:70px 0 60px;text-align:center;color:#fff;
}
.vote-icon {
    width:90px;height:90px;border-radius:50%;
    background:linear-gradient(135deg,#be9b3f,#a0822f);
    display:flex;align-items:center;justify-content:center;
    font-size:2.5rem;color:#fff;margin:0 auto 22px;
    box-shadow:0 10px 35px rgba(190,155,63,.45);
    animation:popIn .5s cubic-bezier(.175,.885,.32,1.275) both;
}
@keyframes popIn { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
.vote-count-big {
    font-size:4rem;font-weight:900;line-height:1;
    background:linear-gradient(135deg,#fbbf24,#f59e0b);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    margin:16px 0 4px;
}
.vote-count-label { font-size:1rem;opacity:.8;margin-bottom:6px; }

.nominee-card {
    background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,.1);
    overflow:hidden;margin:0 auto;max-width:480px;
}
.nc-img { width:100%;height:220px;object-fit:cover; }
.nc-img-placeholder {
    width:100%;height:220px;background:linear-gradient(135deg,#0a0a0a,#1a1a1a);
    display:flex;align-items:center;justify-content:center;font-size:4rem;color:rgba(255,255,255,.3);
}
.nc-body { padding:24px 28px; }
.nc-category { font-size:.72rem;text-transform:uppercase;letter-spacing:1.5px;color:#be9b3f;font-weight:700;margin-bottom:8px; }
.nc-name { font-size:1.3rem;font-weight:900;color:#0a0a0a;margin-bottom:4px; }
.nc-subtitle { font-size:.85rem;color:#888;margin-bottom:16px; }
.nc-votes-row { display:flex;align-items:center;gap:12px;padding:14px;background:#f9f9fb;border-radius:10px;margin-top:14px; }
.nc-votes-num { font-size:1.6rem;font-weight:900;color:#be9b3f; }
.nc-votes-label { font-size:.78rem;color:#888;font-weight:600; }

.action-wrap { max-width:480px;margin:24px auto 0;display:flex;flex-direction:column;gap:12px; }
.ab { display:block;padding:14px;border-radius:10px;font-size:.92rem;font-weight:700;text-align:center;text-decoration:none;border:none;cursor:pointer;transition:opacity .2s; }
.ab:hover { opacity:.9; }
.ab-primary { background:linear-gradient(135deg,#be9b3f,#a0822f);color:#fff; }
.ab-navy    { background:#0a0a0a;color:#fff; }
.ab-outline { background:#fff;color:#0a0a0a;border:2px solid #0a0a0a; }

.vs-share-list { display:flex;flex-direction:column;gap:9px;max-width:480px;margin:0 auto; }
.vs-share-btn {
    display:flex;align-items:center;gap:10px;
    padding:11px 18px;border-radius:8px;border:none;cursor:pointer;
    font-size:.88rem;font-weight:700;text-decoration:none;
    transition:opacity .2s,transform .15s;width:100%;
}
.vs-share-btn:hover { opacity:.88;text-decoration:none;transform:translateX(2px); }
.vs-share-btn i { font-size:1.05rem;width:20px;text-align:center; }
.vs-share-wa { background:#25d366;color:#fff; }
.vs-share-ig { background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff; }
.vs-share-tw { background:#000;color:#fff; }
.vs-share-li { background:#0077b5;color:#fff; }
.vs-copy     { background:#f0f0f0;color:#333;border:1px solid #e0e0e0; }
.vs-copy-ok  { display:none;color:#a0822f;font-size:.78rem;text-align:center;margin-top:4px;font-weight:700; }
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

<!-- Vote hero -->
<div class="vote-hero" style="<?= !$isSuccess ? 'background:linear-gradient(135deg,#374151,#1f2937);' : '' ?>">
    <?php if ($isSuccess): ?>
    <div class="vote-icon"><i class="fas fa-check"></i></div>
    <h1 style="font-size:2rem;font-weight:900;margin-bottom:8px;">Votes Confirmed!</h1>
    <?php if ($votes > 0): ?>
    <div class="vote-count-big" id="voteCounter">0</div>
    <div class="vote-count-label">vote<?= $votes !== 1 ? 's' : '' ?> added to <?= $nominee ? htmlspecialchars($nominee['name']) : 'your nominee' ?></div>
    <?php else: ?>
    <p style="font-size:1rem;opacity:.8;margin-top:8px;">Your payment was received and votes have been allocated.</p>
    <?php endif; ?>
    <?php elseif ($status === 'failed'): ?>
    <div class="vote-icon" style="background:linear-gradient(135deg,#dc2626,#991b1b);"><i class="fas fa-times"></i></div>
    <h1 style="font-size:2rem;font-weight:900;margin-bottom:8px;">Payment Not Completed</h1>
    <p style="font-size:1rem;opacity:.8;margin-top:8px;">Your payment was cancelled or declined. You have not been charged.</p>
    <?php else: ?>
    <div class="vote-icon" style="background:linear-gradient(135deg,#d97706,#92400e);"><i class="fas fa-exclamation-triangle"></i></div>
    <h1 style="font-size:2rem;font-weight:900;margin-bottom:8px;">Something Went Wrong</h1>
    <p style="font-size:1rem;opacity:.8;margin-top:8px;">We could not verify your payment. If you were charged, please contact support.</p>
    <?php endif; ?>
</div>

<!-- Content -->
<section class="shop-section" style="padding:50px 0 70px;">
    <div class="auto-container">
        <div style="max-width:520px;margin:0 auto;padding:0 16px;">

            <?php if ($nominee): ?>
            <!-- Nominee card -->
            <div class="nominee-card">
                <?php
                $nomImg = !empty($nominee['image'])     ? $nominee['image']
                        : (!empty($nominee['thumbnail']) ? $nominee['thumbnail'] : '');
                ?>
                <?php if ($nomImg): ?>
                <img class="nc-img" src="<?= htmlspecialchars($nomImg) ?>" alt="<?= htmlspecialchars($nominee['name']) ?>"
                     onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="nc-img-placeholder" style="display:none;"><i class="fas fa-user"></i></div>
                <?php else: ?>
                <div class="nc-img-placeholder"><i class="fas fa-user"></i></div>
                <?php endif; ?>
                <div class="nc-body">
                    <?php if (!empty($nominee['category_name'])): ?>
                    <div class="nc-category"><i class="fas fa-award" style="margin-right:5px;"></i><?= htmlspecialchars($nominee['category_name']) ?></div>
                    <?php endif; ?>
                    <div class="nc-name"><?= htmlspecialchars($nominee['name']) ?></div>
                    <?php if (!empty($nominee['subtitle'])): ?>
                    <div class="nc-subtitle"><?= htmlspecialchars($nominee['subtitle']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($nominee['votes_count'])): ?>
                    <div class="nc-votes-row">
                        <div>
                            <div class="nc-votes-num"><?= number_format($nominee['votes_count']) ?></div>
                            <div class="nc-votes-label">Total votes so far</div>
                        </div>
                        <div style="flex:1;">
                            <div style="background:#f0f0f5;border-radius:6px;height:8px;overflow:hidden;">
                                <div style="height:100%;background:linear-gradient(90deg,#be9b3f,#f59e0b);border-radius:6px;width:100%;"></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action buttons -->
            <div class="action-wrap">
                <?php if ($isSuccess): ?>
                    <a href="<?= htmlspecialchars($nomineesUrl) ?>" class="ab ab-primary">
                        <i class="fas fa-chart-bar" style="margin-right:8px;"></i>View Live Vote Counts
                    </a>
                    <a href="<?= htmlspecialchars($voteAgainUrl) ?>" class="ab ab-navy">
                        <i class="fas fa-vote-yea" style="margin-right:8px;"></i>Vote Again<?= $nominee ? ' for ' . htmlspecialchars($nominee['name']) : '' ?>
                    </a>
                <?php elseif ($status === 'failed'): ?>
                    <?php if ($eventSlug): ?>
                    <a href="<?= htmlspecialchars($voteAgainUrl) ?>" class="ab ab-primary">
                        <i class="fas fa-redo" style="margin-right:8px;"></i>Try Again
                    </a>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($nomineesUrl) ?>" class="ab ab-outline">
                        <i class="fas fa-arrow-left" style="margin-right:8px;"></i>Back to Nominees
                    </a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($nomineesUrl) ?>" class="ab ab-primary">
                        <i class="fas fa-arrow-left" style="margin-right:8px;"></i>Back to Nominees
                    </a>
                    <a href="<?= SITE_URL ?>/events" class="ab ab-outline">
                        <i class="fas fa-calendar-alt" style="margin-right:8px;"></i>Browse Events
                    </a>
                <?php endif; ?>
            </div>

            <!-- Share -->
            <?php
            $shareText = $nominee
                ? 'I just voted for ' . $nominee['name'] . ($event ? ' at ' . $event['name'] : '') . '! Cast your votes on Baraka Awards Kenya.'
                : 'I just voted on Baraka Awards Kenya! Cast your votes too.';
            $shareUrl  = SITE_URL . ($eventSlug ? '/nominees?event=' . urlencode($eventSlug) : '/events');
            $shareEnc  = urlencode($shareText . ' ' . $shareUrl);
            ?>
            <div style="margin-top:30px;">
                <div style="font-size:.78rem;text-transform:uppercase;letter-spacing:1px;color:#aaa;font-weight:700;margin-bottom:14px;text-align:center;">Share the Love</div>
                <div class="vs-share-list">
                    <a href="https://wa.me/?text=<?= $shareEnc ?>"
                       target="_blank" rel="noopener" class="vs-share-btn vs-share-wa">
                        <i class="fab fa-whatsapp"></i> Share on WhatsApp
                    </a>
                    <a href="#" class="vs-share-btn vs-share-ig" onclick="vsIgShare(event)">
                        <i class="fab fa-instagram"></i> Share on Instagram
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= $shareEnc ?>"
                       target="_blank" rel="noopener" class="vs-share-btn vs-share-tw">
                        <i class="fab fa-x-twitter"></i> Share on Twitter / X
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($shareUrl) ?>"
                       target="_blank" rel="noopener" class="vs-share-btn vs-share-li">
                        <i class="fab fa-linkedin"></i> Share on LinkedIn
                    </a>
                    <button class="vs-share-btn vs-copy" onclick="vsCopyLink()">
                        <i class="fas fa-copy"></i> Copy Link
                    </button>
                </div>
                <div class="vs-copy-ok" id="vsCopyOk">Link copied to clipboard!</div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/footer-links.php'; ?>

</div><!-- /page-wrapper -->

<script src="<?= SITE_URL ?>/assets/js/jquery.js"></script>
<script src="<?= SITE_URL ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>

<script>
var VS_SHARE_URL  = <?= json_encode($shareUrl) ?>;
var VS_SHARE_TEXT = <?= json_encode($shareText) ?>;

function vsCopyLink() {
    var el = document.getElementById('vsCopyOk');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(VS_SHARE_URL).then(function(){ vsShowOk(el, 'Link copied to clipboard!'); });
    } else {
        var ta = document.createElement('textarea');
        ta.value = VS_SHARE_URL; document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
        vsShowOk(el, 'Link copied to clipboard!');
    }
}

function vsIgShare(e) {
    e.preventDefault();
    var el = document.getElementById('vsCopyOk');
    if (navigator.share) {
        navigator.share({ title: VS_SHARE_TEXT, url: VS_SHARE_URL }).catch(function(){});
    } else {
        var ta = document.createElement('textarea');
        ta.value = VS_SHARE_URL; document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
        vsShowOk(el, 'Link copied — paste it in your Instagram story!');
    }
}

function vsShowOk(el, msg) {
    el.textContent = msg; el.style.display = 'block';
    setTimeout(function(){ el.style.display = 'none'; }, 3000);
}
</script>

<?php if ($isSuccess && $votes > 0): ?>
<script>
// Animated vote count
(function() {
    var target = <?= (int)$votes ?>;
    var el     = document.getElementById('voteCounter');
    if (!el) return;
    var start = null;
    var dur   = 1200;
    function animate(ts) {
        if (!start) start = ts;
        var prog = Math.min((ts - start) / dur, 1);
        var ease = 1 - Math.pow(1 - prog, 3);
        el.textContent = Math.round(ease * target).toLocaleString();
        if (prog < 1) requestAnimationFrame(animate);
    }
    setTimeout(function() { requestAnimationFrame(animate); }, 300);
})();

// Confetti
(function() {
    var colors = ['#be9b3f','#0a0a0a','#f59e0b','#be9b3f','#3b82f6','#fff'];
    var style  = document.createElement('style');
    style.textContent = '.cf{position:fixed;top:-20px;animation:cfFall linear forwards;pointer-events:none;z-index:9999}' +
        '@keyframes cfFall{0%{transform:translateY(0) rotate(0deg);opacity:1}100%{transform:translateY(110vh) rotate(720deg);opacity:0}}';
    document.head.appendChild(style);
    for (var i = 0; i < 80; i++) {
        (function(i) {
            setTimeout(function() {
                var el   = document.createElement('div');
                el.className = 'cf';
                var sz   = 6 + Math.random() * 8;
                el.style.cssText = 'left:' + (Math.random() * 100) + 'vw;width:' + sz + 'px;height:' + sz + 'px;background:' +
                    colors[Math.floor(Math.random() * colors.length)] + ';border-radius:' + (Math.random() > .5 ? '50%' : '2px') +
                    ';animation-duration:' + (2 + Math.random() * 2) + 's';
                document.body.appendChild(el);
                setTimeout(function() { el.remove(); }, 5000);
            }, i * 25);
        })(i);
    }
})();
</script>
<?php endif; ?>
</body>
</html>
