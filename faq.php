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
<link rel="canonical" href="https://digitallyfitawards.com/faq.php">

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
<meta property="og:url" content="https://digitallyfitawards.com/faq.php">
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
{"@context":"https://schema.org/","@type":"Organization","name":"Digitally Fit Awards","url":"https://digitallyfitawards.com","contactPoint":{"@type":"ContactPoint","telephone":"+254757140682","email":"<?= ADMIN_EMAIL ?>","contactType":"customer support"},"sameAs":["https://www.instagram.com/p/DV0RJ11ii-7/?igsh=MXNiemxwbXdzMzJ6aw==","https://www.facebook.com/share/p/1DJyLwtvqf/","https://twitter.com/digitallyfitawards","https://www.tiktok.com/@digitallyfitawardske"]}
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://digitallyfitawards.com/"},{"@type":"ListItem","position":2,"name":"FAQ","item":"https://digitallyfitawards.com/faq.php"}]}
</script>

<!-- JSON-LD: WebPage -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Frequently Asked Questions | Digitally Fit Awards","url":"https://digitallyfitawards.com/faq.php","description":"Find answers to common questions about Digitally Fit Awards events, voting, nominations, and tickets."}
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

<section class="page-title" style="background-image:url(<?= SITE_URL ?>/assets/images/digitaly-fit-gallery/digitaly-fit-awards.jpg);">
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
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#g1">What is Digitally Fit Awards?</button></h2><div id="g1" class="accordion-collapse collapse show" data-bs-parent="#faqGeneral"><div class="accordion-body">Digitally Fit Awards (DFA) is East Africa's premier platform for recognizing digital excellence. Organised by Oracom Group and KEOnline, we celebrate individuals, brands, and organizations that have built a significant and impactful digital presence.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g2">Why do we have Digitally Fit Awards?</button></h2><div id="g2" class="accordion-collapse collapse" data-bs-parent="#faqGeneral"><div class="accordion-body">In an increasingly digital world, being "Digitally Fit" is essential for any modern brand. DFA exists to highlight best practices, inspire innovation, and provide a benchmark for digital success across diverse industries in the region.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g3">When is the next Digitally Fit Awards event?</button></h2><div id="g3" class="accordion-collapse collapse" data-bs-parent="#faqGeneral"><div class="accordion-body">The schedule for our upcoming events is currently being finalized. Stay tuned to our website and social media channels for the official announcement of dates, venues, and nomination openings.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#g4">What are the main award categories?</button></h2><div id="g4" class="accordion-collapse collapse" data-bs-parent="#faqGeneral"><div class="accordion-body">DFA covers a wide spectrum of digital achievements including IT & Tech Excellence, Digital Marketing, Social Media Influence, E-commerce, Fintech, and more. A full list of categories for the next event will be published once nominations are announced.</div></div></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="faq-category-title" id="voting">Nominations &amp; Participation</div>
                    <div class="accordion" id="faqVoting">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#v1">How can I participate in DFA?</button></h2><div id="v1" class="accordion-collapse collapse show" data-bs-parent="#faqVoting"><div class="accordion-body">You can participate by nominating your brand or someone else when the nomination window opens, voting for your favorites during the voting phase, or attending our high-profile gala events.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#v2">How does the nomination process work?</button></h2><div id="v2" class="accordion-collapse collapse" data-bs-parent="#faqVoting"><div class="accordion-body">Once an event is live, you can submit nominations through our platform. All entries are reviewed by our panel to ensure they meet the criteria for the selected category before being shortlisted for public voting.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#v3">Is there a cost to nominate or vote?</button></h2><div id="v3" class="accordion-collapse collapse" data-bs-parent="#faqVoting"><div class="accordion-body">Submitting a nomination is typically free. Voting systems vary by event but often involve vote bundles that help support the platform and event organization. Specific details are provided for each event.</div></div></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="faq-category-title" id="tickets">Tickets &amp; Attendance</div>
                    <div class="accordion" id="faqTickets">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#o1">How do I buy tickets for events?</button></h2><div id="o1" class="accordion-collapse collapse show" data-bs-parent="#faqTickets"><div class="accordion-body">Tickets can be purchased directly through our integrated ticketing system on the Tickets page of this website. We accept M-Pesa and major cards. E-tickets are delivered instantly via email.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o2">What should I expect at a DFA event?</button></h2><div id="o2" class="accordion-collapse collapse" data-bs-parent="#faqTickets"><div class="accordion-body">Our gala events are premier networking opportunities featuring industry leaders, celebrating digital pioneers, and providing a platform for brands to showcase their digital fitness to a top-tier audience.</div></div></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="faq-category-title" id="technical">Technical &amp; Support</div>
                    <div class="accordion" id="faqTechnical">
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#t1">I'm having trouble using the platform. What should I do?</button></h2><div id="t1" class="accordion-collapse collapse show" data-bs-parent="#faqTechnical"><div class="accordion-body">If you encounter any technical issues while nominating, voting, or buying tickets, please try refreshing your page or clearing your cache. For persistent issues, contact our technical team through the contact page.</div></div></div>
                        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#t2">Is the platform secure for payments?</button></h2><div id="t2" class="accordion-collapse collapse" data-bs-parent="#faqTechnical"><div class="accordion-body">Yes. We use industry-standard encryption and professional payment gateways to ensure all transactions (voting and tickets) are safe and secure.</div></div></div>
                    </div>
                </div>

                <div class="faq-cta">
                    <h4>Still Have Questions?</h4>
                    <p>Our support team is happy to help you with anything related to the awards.</p>
                    <a href="contact" class="theme-btn btn-style-one"><span class="btn-title"><i class="fas fa-envelope me-2"></i>Contact Support</span></a>
                </div>
            </div>

            <div class="sidebar-side col-lg-4 col-md-12">
                <aside class="sidebar padding-left" style="position:sticky;top:100px;">
                    <div class="sidebar-widget mb-4">
                        <h5 class="sidebar-title">Quick Actions</h5>
                        <nav>
                            <a href="<?= SITE_URL ?>/nominees?event=dfa-gala-2026" class="faq-sidebar-link"><i class="fas fa-users me-2" style="color:#BF9E44;"></i>View Nominees</a>
                            <a href="<?= SITE_URL ?>/nominees?event=dfa-gala-2026" class="faq-sidebar-link"><i class="fas fa-vote-yea me-2" style="color:#BF9E44;"></i>Vote Now</a>
                            <a href="<?= SITE_URL ?>/tickets?event=dfa-gala-2026"  class="faq-sidebar-link"><i class="fas fa-ticket-alt me-2" style="color:#BF9E44;"></i>Buy Tickets</a>
                            <a href="<?= SITE_URL ?>/nominate?event=dfa-gala-2026" class="faq-sidebar-link"><i class="fas fa-user-plus me-2" style="color:#BF9E44;"></i>Submit a Nomination</a>
                            <a href="<?= SITE_URL ?>/contact"                       class="faq-sidebar-link"><i class="fas fa-envelope me-2" style="color:#BF9E44;"></i>Contact Us</a>
                        </nav>
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
/* ── Highlight sidebar link matching current page ── */
(function(){
    var path = window.location.pathname.replace(/\/$/, '');
    document.querySelectorAll('.faq-sidebar-link').forEach(function(l){
        var href = l.getAttribute('href') || '';
        var linkPath = href.split('?')[0].replace(/\/$/, '');
        if (linkPath && path.endsWith(linkPath)) l.classList.add('active');
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
