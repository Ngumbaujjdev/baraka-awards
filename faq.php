<?php
include 'config/config.php';
include 'libs/App.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- SEO -->
<title>Frequently Asked Questions | Digitally Fit Awards</title>
<meta name="description" content="Find answers to common questions about Digitally Fit Awards — how to vote, nominate, buy tickets, and participate in events across Kenya.">
<meta name="keywords" content="Digitally Fit Awards FAQ, how to vote Kenya, how to nominate Kenya, event tickets FAQ, Digitally Fit Awards help">
<meta name="author" content="Digitally Fit Awards">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://dfa.tuqiohub.africa/faq.php">

<!-- Schema.org microdata -->
<meta itemprop="name" content="Frequently Asked Questions | Digitally Fit Awards">
<meta itemprop="description" content="Find answers to common questions about Digitally Fit Awards events, voting, nominations, and tickets.">
<meta itemprop="image" content="<?= OG_IMAGE ?>">

<!-- Open Graph -->
<meta property="og:title" content="Frequently Asked Questions | Digitally Fit Awards">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="https://dfa.tuqiohub.africa/faq.php">
<meta property="og:description" content="Find answers to common questions about Digitally Fit Awards events, voting, nominations, and tickets.">
<meta property="og:site_name" content="Digitally Fit Awards">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@digitallyfitawards">
<meta name="twitter:title" content="Frequently Asked Questions | Digitally Fit Awards">
<meta name="twitter:description" content="Find answers to common questions about Digitally Fit Awards events, voting, nominations, and tickets.">
<meta name="twitter:image" content="<?= OG_IMAGE ?>">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-XXXXXXXXXX');</script>

<!-- JSON-LD: Organization -->
<script type="application/ld+json">
{"@context":"https://schema.org/","@type":"Organization","name":"Digitally Fit Awards","url":"https://dfa.tuqiohub.africa","contactPoint":{"@type":"ContactPoint","telephone":"+254757140682","email":"info@dfa.tuqiohub.africa","contactType":"customer support"},"sameAs":["https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://www.facebook.com/share/p/1DJyLwtvqf/","https://twitter.com/digitallyfitawards","https://www.tiktok.com/@digitallyfitawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://dfa.tuqiohub.africa/"},{"@type":"ListItem","position":2,"name":"FAQ","item":"https://dfa.tuqiohub.africa/faq.php"}]}
</script>

<!-- JSON-LD: WebPage -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Frequently Asked Questions | Digitally Fit Awards","url":"https://dfa.tuqiohub.africa/faq.php","description":"Find answers to common questions about Digitally Fit Awards events, voting, nominations, and tickets."}
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
<style>
.faq-section { padding: 70px 0; background: #f9fafb; }
.faq-category-title { font-weight: 800; color: #0a0a0a; font-size: 1.1rem; margin-bottom: 20px; display: inline-block; }
.accordion-button { font-weight: 600; font-size: .92rem; color: #0a0a0a; background: #fff; box-shadow: none; }
.accordion-button:not(.collapsed) { color: #be9b3f; background: #fff; box-shadow: none; }
.accordion-button:focus { box-shadow: none; }
.accordion-button:not(.collapsed)::after { filter: invert(24%) sepia(100%) saturate(5000%) hue-rotate(344deg) brightness(90%); }
.accordion-item { border: 1px solid #eee; border-radius: 10px !important; margin-bottom: 10px; overflow: hidden; }
.accordion-body { font-size: .9rem; color: #555; line-height: 1.8; }
.faq-sidebar-link { display: block; padding: 10px 14px; border-radius: 8px; font-size: .88rem; font-weight: 600; color: #0a0a0a; text-decoration: none; transition: all .2s; margin-bottom: 4px; }
.faq-sidebar-link:hover, .faq-sidebar-link.active { background: #be9b3f; color: #fff; }
.faq-sidebar-box { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 3px 16px rgba(0,0,0,0.06); }
.faq-sidebar-links a { color: #0a0a0a; font-size: .88rem; text-decoration: none; }
.faq-sidebar-links a:hover { color: #be9b3f; }
.faq-sidebar-links li { padding: 7px 0; }
.faq-sidebar-links li + li { border-top: 1px solid #f5f5f5; }
.faq-cta { background: linear-gradient(135deg, #0a0a0a, #1a1a1a); border-radius: 14px; padding: 36px; text-align: center; color: #fff; margin-top: 10px; }
.faq-cta h4 { font-weight: 800; margin-bottom: 10px; }
.faq-cta p  { opacity: .8; margin-bottom: 22px; }
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

<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/mema-about/about-1.webp);">
    <div class="anim-icons full-width"><span class="icon icon-bull-eye"></span><span class="icon icon-dotted-circle"></span></div>
    <div class="auto-container">
        <div class="title-outer">
            <h1>Frequently Asked Questions</h1>
            <ul class="page-breadcrumb">
                <li><a href="<?= SITE_URL ?>">Home</a></li>
                <li>FAQ</li>
            </ul>
        </div>
    </div>
</section>

<div class="faq-section">
    <div class="auto-container">
        <div class="row">
            <div class="content-side col-lg-8 col-md-12">

                <div class="mb-5">
                    <div class="faq-category-title" id="general">General</div>
                    <div class="accordion" id="faqGeneral">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#g1">What is Digitally Fit Awards?</button></h2><div id="g1" class="accordion-collapse collapse show" data-bs-parent="#faqGeneral"><div class="accordion-body">Digitally Fit Awards is East Africa's premier digital excellence awards ceremony, organised by KEOnline, celebrating outstanding achievement across 300+ digital categories.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g2">What does DFA stand for?</button></h2><div id="g2" class="accordion-collapse collapse" data-bs-parent="#faqGeneral"><div class="accordion-body">DFA stands for <strong>Digitally Fit Awards</strong>. We celebrate individuals and organisations that have demonstrated exceptional performance in the digital space across East Africa.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g3">When and where is the Digitally Fit Awards Gala?</button></h2><div id="g3" class="accordion-collapse collapse" data-bs-parent="#faqGeneral"><div class="accordion-body">The inaugural Digitally Fit Awards Gala takes place on <strong>16 September 2026</strong> at the <strong>Radisson Blu Hotel &amp; Convention Centre, Nairobi</strong>. Doors open at 4:30 PM. Get your tickets on the <a href="tickets" style="color:#BF9E44;">Tickets page</a>.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g4">How many award categories are there?</button></h2><div id="g4" class="accordion-collapse collapse" data-bs-parent="#faqGeneral"><div class="accordion-body">Digitally Fit Awards 2026 has <strong>9 award categories</strong>: Female Artist of the Year, Male Artist of the Year, Emerging Gospel Artist of the Year, Gospel Song of the Year, Gospel Video of the Year, Gospel Collaboration of the Year, Audio Producer of the Year, Video Director of the Year, and Photography of the Year.</div></div></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="faq-category-title" id="voting">Voting &amp; Nominees</div>
                    <div class="accordion" id="faqVoting">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#v1">How do I vote for a nominee?</button></h2><div id="v1" class="accordion-collapse collapse show" data-bs-parent="#faqVoting"><div class="accordion-body">Go to the <a href="nominees" style="color:#BF9E44;">Nominees &amp; Voting</a> page, select the event, browse the categories, and click <strong>Vote Now</strong>. Voting is open only during the official window for each event.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#v2">Can I vote more than once?</button></h2><div id="v2" class="accordion-collapse collapse" data-bs-parent="#faqVoting"><div class="accordion-body">Yes — DFA uses a vote-bundle system. You purchase a bundle of votes (e.g. 10 votes for KES 100) and allocate them to your favourite nominee. You can purchase as many bundles as you like during the voting window.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#v3">How are nominees selected?</button></h2><div id="v3" class="accordion-collapse collapse" data-bs-parent="#faqVoting"><div class="accordion-body">Nominees are submitted by the public through the <a href="nominate" style="color:#BF9E44;">Nominate</a> page, then reviewed and shortlisted by the DFA team before appearing on the public voting page.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#v4">When does voting close?</button></h2><div id="v4" class="accordion-collapse collapse" data-bs-parent="#faqVoting"><div class="accordion-body">Each event has its own deadline, shown as a live countdown on the nominee page. Once voting closes, results are compiled and winners are announced at the event.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#v5">Are vote counts real-time?</button></h2><div id="v5" class="accordion-collapse collapse" data-bs-parent="#faqVoting"><div class="accordion-body">Yes — the nominee page refreshes vote counts every 8 seconds automatically. Some events may hide exact counts until the voting period ends.</div></div></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="faq-category-title" id="nominations">Nominations</div>
                    <div class="accordion" id="faqNominations">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#n1">How do I nominate someone?</button></h2><div id="n1" class="accordion-collapse collapse show" data-bs-parent="#faqNominations"><div class="accordion-body">Visit the <a href="nominate" style="color:#BF9E44;">Nominate</a> page, select the event and category, fill in the nominee's details and reason, then submit. Nominations are reviewed before being published.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#n2">Is there a fee to nominate?</button></h2><div id="n2" class="accordion-collapse collapse" data-bs-parent="#faqNominations"><div class="accordion-body">Submitting a nomination is free. Some organizers may charge a processing fee — this will be clearly stated on the event's nomination page.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#n3">How will I know if my nomination was accepted?</button></h2><div id="n3" class="accordion-collapse collapse" data-bs-parent="#faqNominations"><div class="accordion-body">Your nomination enters a review queue. If shortlisted, the nominee will appear on the public voting page. You may receive an email at the address you provided during submission.</div></div></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="faq-category-title" id="tickets">Tickets &amp; Attendance</div>
                    <div class="accordion" id="faqTickets">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#o1">How do I buy tickets?</button></h2><div id="o1" class="accordion-collapse collapse show" data-bs-parent="#faqTickets"><div class="accordion-body">Visit the <a href="tickets" style="color:#BF9E44;">Tickets page</a>, choose your preferred tier, and complete the secure online payment via M-Pesa or card. Your e-ticket will be sent to your email immediately after payment.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o2">What ticket options are available?</button></h2><div id="o2" class="accordion-collapse collapse" data-bs-parent="#faqTickets"><div class="accordion-body">Digitally Fit Awards Gala 2026 offers several tiers: <strong>Individual Seat</strong> (KES 1,000), <strong>Couple</strong> (KES 1,800), <strong>Table of 10</strong> (KES 9,000), and <strong>VIP Bundle</strong>. Check the <a href="tickets" style="color:#BF9E44;">Tickets page</a> for current availability and any early-bird offers.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o3">Can I get a refund?</button></h2><div id="o3" class="accordion-collapse collapse" data-bs-parent="#faqTickets"><div class="accordion-body">Tickets are non-refundable. If you have a genuine concern, please contact us at <a href="mailto:info@digitallyfitawards.co.ke" style="color:#BF9E44;">info@digitallyfitawards.co.ke</a> and the team will do their best to assist.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o4">Is there a dress code?</button></h2><div id="o4" class="accordion-collapse collapse" data-bs-parent="#faqTickets"><div class="accordion-body">Smart or formal attire is encouraged. The Digitally Fit Awards Gala is a prestigious awards night — dress to celebrate and honour the occasion!</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o5">Where exactly is the venue?</button></h2><div id="o5" class="accordion-collapse collapse" data-bs-parent="#faqTickets"><div class="accordion-body">The Digitally Fit Awards Gala 2026 is held at the <strong>Radisson Blu Hotel &amp; Convention Centre, Upper Hill, Nairobi</strong>. Parking is available on site. Doors open at 4:30 PM on 16 September 2026.</div></div></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="faq-category-title" id="technical">Technical</div>
                    <div class="accordion" id="faqTechnical">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#t1">The voting page isn't loading. What should I do?</button></h2><div id="t1" class="accordion-collapse collapse show" data-bs-parent="#faqTechnical"><div class="accordion-body">Try refreshing. If the issue persists, clear your browser cache or try a different browser. Still stuck? <a href="contact" style="color:#BF9E44;">Contact us</a> and we'll investigate immediately.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#t2">Which browsers are supported?</button></h2><div id="t2" class="accordion-collapse collapse" data-bs-parent="#faqTechnical"><div class="accordion-body">Digitally Fit Awards works on all modern browsers including Chrome, Firefox, Safari, and Edge on desktop and mobile. Keep your browser updated for the best experience.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#t3">How do I report a bug?</button></h2><div id="t3" class="accordion-collapse collapse" data-bs-parent="#faqTechnical"><div class="accordion-body">Use the <a href="contact" style="color:#BF9E44;">Contact</a> page. Include the page URL, description of the issue, and your device/browser. Our team aims to respond within 24 hours.</div></div></div>
                    </div>
                </div>

                <div class="faq-cta">
                    <h4>Still Have Questions?</h4>
                    <p>Our support team is happy to help. Reach out any time.</p>
                    <a href="contact" class="theme-btn btn-style-one"><span class="btn-title"><i class="fas fa-envelope me-2"></i>Contact Support</span></a>
                </div>
            </div>

            <div class="sidebar-side col-lg-4 col-md-12">
                <aside class="sidebar padding-left" style="position:sticky;top:100px;">
                    <div class="sidebar-widget mb-4">
                        <h5 class="sidebar-title">Jump to Section</h5>
                        <nav>
                            <a href="#general"     class="faq-sidebar-link active">General</a>
                            <a href="#voting"      class="faq-sidebar-link">Voting &amp; Nominees</a>
                            <a href="#nominations" class="faq-sidebar-link">Nominations</a>
                            <a href="#tickets"     class="faq-sidebar-link">Tickets &amp; Attendance</a>
                            <a href="#technical"   class="faq-sidebar-link">Technical</a>
                        </nav>
                    </div>
                    <div class="sidebar-widget faq-sidebar-box">
                        <h5 class="sidebar-title">Quick Links</h5>
                        <ul class="list-unstyled faq-sidebar-links mb-0">
                            <li><a href="nominees"><i class="fas fa-users me-2" style="color:#BF9E44;"></i>View Nominees</a></li>
                            <li><a href="nominees"><i class="fas fa-vote-yea me-2" style="color:#BF9E44;"></i>Vote Now</a></li>
                            <li><a href="tickets"><i class="fas fa-ticket-alt me-2" style="color:#BF9E44;"></i>Buy Tickets</a></li>
                            <li><a href="nominate"><i class="fas fa-user-plus me-2" style="color:#BF9E44;"></i>Submit a Nomination</a></li>
                            <li><a href="contact"><i class="fas fa-envelope me-2" style="color:#BF9E44;"></i>Contact Us</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</div>
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>
<?php include 'includes/footer-links.php'; ?>
<script>
/* ── Sidebar active link on scroll ── */
(function(){
    var links=document.querySelectorAll('.faq-sidebar-link');
    var sections=['general','voting','nominations','tickets','technical'];
    window.addEventListener('scroll',function(){
        var y=window.scrollY+150,active=sections[0];
        sections.forEach(function(id){var el=document.getElementById(id);if(el&&el.getBoundingClientRect().top+window.scrollY<=y)active=id;});
        links.forEach(function(l){l.classList.toggle('active',l.getAttribute('href')==='#'+active);});
    });
})();

/* ── Accordion toggle (BS5 attributes, BS4 JS loaded — fix with custom handler) ── */
(function () {
    document.querySelectorAll('.accordion-button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetSel = this.getAttribute('data-bs-target');
            var target    = document.querySelector(targetSel);
            if (!target) return;

            var accordion = this.closest('.accordion');
            var isOpen    = target.classList.contains('show');

            /* Close all items in this accordion group */
            accordion.querySelectorAll('.accordion-collapse').forEach(function (el) {
                el.classList.remove('show');
            });
            accordion.querySelectorAll('.accordion-button').forEach(function (b) {
                b.classList.add('collapsed');
            });

            /* Open the clicked one if it was closed */
            if (!isOpen) {
                target.classList.add('show');
                this.classList.remove('collapsed');
            }
        });
    });
})();
</script>
</body>
</html>
