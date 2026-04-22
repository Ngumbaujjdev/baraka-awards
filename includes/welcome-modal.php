<?php
// How-to-vote modal — shown once per browser session
?>
<div id="howToVoteModal" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;padding:16px;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(3px);" onclick="closeVoteModal()"></div>
    <div style="position:relative;background:#fff;border-radius:20px;max-width:480px;width:100%;padding:36px 32px 28px;box-shadow:0 24px 80px rgba(0,0,0,.3);animation:vModalIn .35s cubic-bezier(.175,.885,.32,1.275) both;">
        <button onclick="closeVoteModal()" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:1.3rem;color:#aaa;cursor:pointer;line-height:1;">&times;</button>

        <!-- Header -->
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#0a0a0a,#be9b3f);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <i class="fas fa-vote-yea" style="font-size:1.5rem;color:#fff;"></i>
            </div>
            <h3 style="font-size:1.25rem;font-weight:900;color:#0a0a0a;margin:0 0 6px;">How to Vote</h3>
            <p style="font-size:.85rem;color:#888;margin:0;">3 simple steps to support your nominee</p>
        </div>

        <!-- Steps -->
        <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
            <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:linear-gradient(135deg,#0a0a0a,#be9b3f);display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;font-size:.95rem;">1</div>
                <div>
                    <div style="font-weight:800;color:#0a0a0a;font-size:.95rem;margin-bottom:2px;">Choose a Category</div>
                    <div style="font-size:.82rem;color:#888;line-height:1.5;">Browse the nominee categories from the sidebar or the filter dropdown.</div>
                </div>
            </div>
            <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:linear-gradient(135deg,#0a0a0a,#be9b3f);display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;font-size:.95rem;">2</div>
                <div>
                    <div style="font-weight:800;color:#0a0a0a;font-size:.95rem;margin-bottom:2px;">Find Your Nominee</div>
                    <div style="font-size:.82rem;color:#888;line-height:1.5;">Use the search bar to quickly find a nominee by name across all categories.</div>
                </div>
            </div>
            <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:36px;height:36px;min-width:36px;border-radius:50%;background:linear-gradient(135deg,#0a0a0a,#be9b3f);display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;font-size:.95rem;">3</div>
                <div>
                    <div style="font-weight:800;color:#0a0a0a;font-size:.95rem;margin-bottom:2px;">Click Vote Now</div>
                    <div style="font-size:.82rem;color:#888;line-height:1.5;">Select a vote package, fill in your details, and complete payment to cast your votes.</div>
                </div>
            </div>
        </div>

        <!-- CTAs -->
        <a href="<?= SITE_URL ?>/nominees" onclick="closeVoteModal();"
           style="display:block;text-align:center;background:linear-gradient(135deg,#0a0a0a,#be9b3f);color:#fff;font-weight:800;font-size:.95rem;padding:14px;border-radius:10px;text-decoration:none;transition:opacity .2s;margin-bottom:10px;">
            <i class="fas fa-vote-yea" style="margin-right:8px;"></i>Vote Now &rarr;
        </a>
        <a href="<?= SITE_URL ?>/tickets" onclick="closeVoteModal();"
           style="display:block;text-align:center;background:#fff;color:#0a0a0a;border:2px solid #0a0a0a;font-weight:800;font-size:.95rem;padding:12px;border-radius:10px;text-decoration:none;transition:opacity .2s;">
            <i class="fas fa-ticket-alt" style="margin-right:8px;"></i>Buy Tickets Now
        </a>
        <p style="text-align:center;font-size:.75rem;color:#bbb;margin:12px 0 0;cursor:pointer;" onclick="closeVoteModal();">Dismiss</p>
    </div>
</div>

<style>
@keyframes vModalIn { from{transform:scale(.85);opacity:0} to{transform:scale(1);opacity:1} }
</style>
<script>
document.getElementById('howToVoteModal').style.display = 'flex';
function closeVoteModal() {
    document.getElementById('howToVoteModal').style.display = 'none';
}
</script>
