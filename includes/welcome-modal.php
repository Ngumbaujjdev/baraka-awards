<?php
// welcome-modal.php — First-visit popup (suppress for 7 days after dismiss)
// Requires these vars from the including page (index.php):
//   $_votingOpen       bool — is voting live?
//   $_ticketsAvailable bool — is at least one ticket type available?
$_wm_voting  = !empty($_votingOpen);
$_wm_tickets = !empty($_ticketsAvailable);
// Show modal only if there's at least one CTA to display
if (!$_wm_voting && !$_wm_tickets) return;
?>
<style>
#welcomeModal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99999;align-items:center;justify-content:center;padding:16px;}
.wm-card{background:#fff;border-radius:18px;max-width:440px;width:100%;overflow:hidden;box-shadow:0 24px 80px rgba(0,0,0,.35);position:relative;animation:wmSlideIn .35s cubic-bezier(.16,1,.3,1) both;}
@keyframes wmSlideIn{from{opacity:0;transform:translateY(30px) scale(.97)}to{opacity:1;transform:none}}
.wm-top{background:linear-gradient(135deg,#053732 0%,#0a5c50 100%);padding:32px 28px 24px;text-align:center;color:#fff;}
.wm-top img{height:52px;margin-bottom:14px;}
.wm-top h3{font-size:1.35rem;font-weight:800;margin:0 0 6px;}
.wm-top p{font-size:.88rem;color:rgba(255,255,255,.75);margin:0;}
.wm-body{padding:24px 28px 28px;}
.wm-cta-grid{display:flex;flex-direction:column;gap:10px;}
.wm-btn{display:block;width:100%;padding:13px 20px;border-radius:9px;font-size:.95rem;font-weight:700;text-align:center;text-decoration:none;transition:opacity .2s;}
.wm-btn:hover{opacity:.88;text-decoration:none;}
.wm-btn-vote{background:#be9b3f;color:#fff;}
.wm-btn-tickets{background:#053732;color:#fff;}
.wm-btn-learn{background:#f5f5f5;color:#053732;}
.wm-close{position:absolute;top:14px;right:16px;background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;}
.wm-close:hover{background:rgba(255,255,255,.3);}
.wm-dismiss{text-align:center;margin-top:14px;}
.wm-dismiss button{background:none;border:none;color:#aaa;font-size:.78rem;cursor:pointer;padding:0;}
.wm-dismiss button:hover{color:#053732;}
</style>

<div id="welcomeModal" role="dialog" aria-modal="true" aria-label="Welcome to Digitally Fit Awards">
    <div class="wm-card">
        <div class="wm-top">
            <button class="wm-close" onclick="dismissWelcome()" aria-label="Close">&times;</button>
            <img src="<?= SITE_URL ?>/assets/images/mema-logo-white.png" alt="Digitally Fit Awards" onerror="this.style.display='none'">
            <h3>Welcome to Digitally Fit Awards</h3>
            <p>Kenya's Premier Gospel Music Awards &mdash; Gala 2026</p>
        </div>
        <div class="wm-body">
            <div class="wm-cta-grid">
                <?php if ($_wm_voting): ?>
                <a href="<?= SITE_URL ?>/nominees" class="wm-btn wm-btn-vote" onclick="dismissWelcome()">
                    Vote Now &rarr;
                </a>
                <?php endif; ?>
                <?php if ($_wm_tickets): ?>
                <a href="<?= SITE_URL ?>/tickets" class="wm-btn wm-btn-tickets" onclick="dismissWelcome()">
                    Get Tickets &rarr;
                </a>
                <?php endif; ?>
                <a href="<?= SITE_URL ?>/about" class="wm-btn wm-btn-learn" onclick="dismissWelcome()">
                    Learn More
                </a>
            </div>
            <div class="wm-dismiss">
                <button onclick="dismissWelcome()">Don't show this again</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var KEY  = 'mema_welcomed';
    var DAYS = 7;
    var last = localStorage.getItem(KEY);
    var now  = Date.now();
    if (!last || now - parseInt(last, 10) > DAYS * 86400 * 1000) {
        setTimeout(function() {
            var modal = document.getElementById('welcomeModal');
            if (modal) modal.style.display = 'flex';
        }, 1500);
    }
})();

function dismissWelcome() {
    localStorage.setItem('mema_welcomed', Date.now());
    var modal = document.getElementById('welcomeModal');
    if (modal) modal.style.display = 'none';
}

// Close on backdrop click
document.addEventListener('click', function(e) {
    var modal = document.getElementById('welcomeModal');
    if (modal && e.target === modal) dismissWelcome();
});
</script>
