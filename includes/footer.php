<footer class="main-footer" style="background:linear-gradient(160deg,#000000 0%,#0d0d0d 40%,#1a1a1a 100%);color:#ddd;position:relative;">

    <!-- Top accent line -->
    <div style="height:4px;background:linear-gradient(90deg,#BF9E44,#0d0d0d,#BF9E44);width:100%;position:relative;z-index:3;"></div>

    <!-- Static dot-grid overlay -->
    <div style="position:absolute;inset:0;pointer-events:none;z-index:0;opacity:0.55;background-image:radial-gradient(circle,rgba(191,158,68,0.18) 1px,transparent 1px);background-size:28px 28px;"></div>
    <!-- Diagonal gold accent strip top-left -->
    <div style="position:absolute;top:0;left:0;width:260px;height:260px;pointer-events:none;z-index:0;background:linear-gradient(135deg,rgba(191,158,68,0.12) 0%,transparent 60%);"></div>

    <!-- Widgets Section -->
    <div class="widgets-section" style="padding:70px 0 40px;position:relative;z-index:2;">
        <div class="auto-container">
            <div class="row">

                <!-- Col 1: About Tuqio -->
                <div class="footer-column col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-5 mb-lg-0">
                    <div class="footer-widget about-widget">
                        <div class="logo" style="margin-bottom:20px;">
                            <a href="<?php echo SITE_URL; ?>/">
                                <img src="<?php echo SITE_URL; ?>/assets/images/logo/dfa-logo-white.svg"
                                     alt="Baraka Awards Kenya" style="max-height:70px;width:auto;">
                            </a>
                        </div>
                        <p style="color:rgba(255,255,255,0.72);font-size:0.95rem;line-height:1.8;margin-bottom:20px;">
                            Celebrating cultural and entertainment excellence in Kenya. Organised by the Baraka Awards Kenya team — recognising outstanding achievements in 21 award categories.
                        </p>
                        <ul class="social-icon-two" style="list-style:none;padding:0;display:flex;gap:10px;flex-wrap:wrap;">
                            <?php
                            $socials = [
                                ['fab fa-facebook-f', SOCIAL_FACEBOOK],
                                ['fab fa-twitter',    SOCIAL_TWITTER],
                                ['fab fa-instagram',  SOCIAL_INSTAGRAM],
                                ['fab fa-tiktok',     SOCIAL_TIKTOK],
                                ['fab fa-linkedin-in',SOCIAL_LINKEDIN],
                            ];
                            foreach ($socials as [$icon,$href]): ?>
                            <li>
                                <a href="<?php echo $href; ?>" target="_blank"
                                   style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,0.1);color:#BF9E44;font-size:16px;text-decoration:none;transition:all .3s;"
                                   onmouseover="this.style.background='#BF9E44';this.style.color='#fff';"
                                   onmouseout="this.style.background='rgba(255,255,255,0.1)';this.style.color='#BF9E44';">
                                    <i class="<?php echo $icon; ?>"></i>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="footer-column col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-5 mb-lg-0">
                    <div class="footer-widget">
                        <h2 class="widget-title" style="color:#BF9E44;font-size:1.05rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid rgba(191,158,68,0.4);">Quick Links</h2>
                        <ul style="list-style:none;padding:0;margin:0;">
                            <?php
                            $links = [
                                ['Home',      SITE_URL . '/'],
                                ['Events',    SITE_URL . '/events'],
                                ['Nominees',  SITE_URL . '/nominees'],
                                ['Nominate',  SITE_URL . '/nominate'],
                                ['Gallery',   SITE_URL . '/gallery'],
                                ['Blog',      SITE_URL . '/blog'],
                                ['About',     SITE_URL . '/about'],
                                ['Contact',   SITE_URL . '/contact'],
                            ];
                            foreach ($links as [$label,$href]): ?>
                            <li style="margin-bottom:10px;">
                                <a href="<?php echo $href; ?>"
                                   style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.95rem;transition:color .3s;"
                                   onmouseover="this.style.color='#BF9E44';"
                                   onmouseout="this.style.color='rgba(255,255,255,0.75)';">→ <?php echo $label; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Col 3: Get Involved -->
                <div class="footer-column col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-5 mb-lg-0">
                    <div class="footer-widget">
                        <h2 class="widget-title" style="color:#BF9E44;font-size:1.05rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid rgba(191,158,68,0.4);">Get Involved</h2>
                        <ul style="list-style:none;padding:0;margin:0;">
                            <?php
                            $orgLinks = [
                                ['Vote for a Nominee',      SITE_URL . '/nominees'],
                                ['Submit a Nomination',     SITE_URL . '/nominate'],
                                ['Buy Tickets',             SITE_URL . '/tickets'],
                                ['Browse Gallery',          SITE_URL . '/gallery'],
                                ['FAQ',                     SITE_URL . '/faq'],
                                ['Contact Us',              SITE_URL . '/contact'],
                            ];
                            foreach ($orgLinks as [$label,$href]): ?>
                            <li style="margin-bottom:10px;">
                                <a href="<?php echo $href; ?>"
                                   style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.95rem;transition:color .3s;"
                                   onmouseover="this.style.color='#BF9E44';"
                                   onmouseout="this.style.color='rgba(255,255,255,0.75)';">→ <?php echo $label; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                        <!-- CTA block -->
                        <div style="margin-top:24px;background:rgba(191,158,68,0.15);border:1px solid rgba(191,158,68,0.3);border-radius:8px;padding:16px;text-align:center;">
                            <p style="color:#fff;font-size:.85rem;margin-bottom:10px;">30th May 2026 — Southfield Mall, Mombasa Road. Tickets from KES 500.</p>
                            <a href="<?php echo SITE_URL; ?>/tickets"
                               class="theme-btn btn-style-one"
                               style="font-size:.8rem;padding:8px 18px;">
                                <span class="btn-title">Get Tickets</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Contact -->
                <div class="footer-column col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-widget contact-widget">
                        <h2 class="widget-title" style="color:#BF9E44;font-size:1.05rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid rgba(191,158,68,0.4);">Get in Touch</h2>
                        <ul style="list-style:none;padding:0;margin:0;">
                            <li style="display:flex;gap:14px;margin-bottom:18px;align-items:flex-start;">
                                <span style="color:#BF9E44;font-size:18px;margin-top:2px;min-width:20px;"><i class="flaticon-location"></i></span>
                                <div>
                                    <span style="color:rgba(255,255,255,0.75);font-size:.9rem;line-height:1.6;">Southfield Mall, Mombasa Road, Nairobi</span><br>
                                    <span style="color:rgba(255,255,255,0.45);font-size:.8rem;">Gala Venue — 30th May 2026</span>
                                </div>
                            </li>
                            <li style="display:flex;gap:14px;margin-bottom:18px;align-items:flex-start;">
                                <span style="color:#BF9E44;font-size:18px;margin-top:2px;min-width:20px;"><i class="flaticon-email-1"></i></span>
                                <div>
                                    <a href="mailto:<?= ADMIN_EMAIL ?>"
                                       style="color:rgba(255,255,255,0.75);font-size:.9rem;text-decoration:none;"
                                       onmouseover="this.style.color='#BF9E44';"
                                       onmouseout="this.style.color='rgba(255,255,255,0.75)';"><?= ADMIN_EMAIL ?></a><br>
                                    <span style="color:rgba(255,255,255,0.45);font-size:.8rem;">Email Us</span>
                                </div>
                            </li>
                            <li style="display:flex;gap:14px;margin-bottom:18px;align-items:flex-start;">
                                <span style="color:#BF9E44;font-size:18px;margin-top:2px;min-width:20px;"><i class="flaticon-call-1"></i></span>
                                <div>
                                    <a href="tel:<?= SITE_PHONE ?>"
                                       style="color:rgba(255,255,255,0.75);font-size:.9rem;text-decoration:none;"
                                       onmouseover="this.style.color='#BF9E44';"
                                       onmouseout="this.style.color='rgba(255,255,255,0.75)';"><?= SITE_PHONE ?></a><br>
                                    <span style="color:rgba(255,255,255,0.45);font-size:.8rem;">Call Us</span>
                                </div>
                            </li>
                            <li style="display:flex;gap:14px;align-items:flex-start;">
                                <span style="color:#BF9E44;font-size:18px;margin-top:2px;min-width:20px;"><i class="flaticon-alarm-clock-1"></i></span>
                                <div>
                                    <span style="color:rgba(255,255,255,0.75);font-size:.9rem;">Mon – Fri: 9am – 6pm</span><br>
                                    <span style="color:rgba(255,255,255,0.45);font-size:.8rem;">Working Hours</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom">
        <div class="auto-container">
            <div class="inner-container">
                <div class="copyright-text">
                    <p>&copy; <?php echo date('Y'); ?> <a href="<?php echo SITE_URL; ?>/">Baraka Awards Kenya</a>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>

</footer>
