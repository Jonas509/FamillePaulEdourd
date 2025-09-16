<?php
// ==================== FOOTER ====================
// Pied de page commun à toutes les pages.
?>
<footer id="footer" class="footer premium-footer text-white pt-5 pb-2" style="background:linear-gradient(135deg,#1a2238 60%,#283e6d 100%);">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                <div class="footer-logo-badge mx-auto mb-2">
                    <span style="display:inline-block;width:60px;height:60px;border-radius:50%;box-shadow:0 2px 16px #006fbe44;background:#fff;line-height:60px;text-align:center;">
                        <i class="bi bi-people-fill" style="font-size:2.2rem;color:#0d6efd;vertical-align:middle;"></i>
                    </span>
                </div>
                <div class="fw-bold fs-5 mb-1">Famille Paul-Edourd</div>
                <div class="footer-quote fst-italic small text-light-emphasis">« La famille, c’est là où la vie commence et où l’amour ne finit jamais. »</div>
            </div>
            <div class="col-md-6 mb-3 mb-md-0">
                <ul class="list-inline mb-0 text-center">
                    <li class="list-inline-item mx-2"><a href="index.php" class="footer-link">Accueil</a></li>
                    <li class="list-inline-item mx-2"><a href="our-story.php" class="footer-link">Notre histoire</a></li>
                    <li class="list-inline-item mx-2"><a href="events.php" class="footer-link">Événements</a></li>
                    <li class="list-inline-item mx-2"><a href="gallery.php" class="footer-link">Galerie</a></li>
                    <li class="list-inline-item mx-2"><a href="blog.php" class="footer-link">Blog</a></li>
                    <li class="list-inline-item mx-2"><a href="contact.php" class="footer-link">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3 text-center text-md-end">
                <div class="social-links gap-2 d-flex justify-content-center justify-content-md-end">
                    <a href="#" class="social-icon-circle" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon-circle" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon-circle" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon-circle" title="Skype"><i class="bi bi-skype"></i></a>
                    <a href="#" class="social-icon-circle" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
        <hr class="border-light opacity-25 mb-3">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <span class="small">&copy; <?= date('Y') ?> <strong class="sitename">Famille Paul-Edourd</strong>. Tous droits réservés.</span>
            </div>
            <div class="col-md-6 text-center text-md-end small">
                Design par <span class="footer-link fw-bold">KaelWeb</span>
            </div>
        </div>
    </div>
    <style>
        .premium-footer {
            background: linear-gradient(135deg,#1a2238 60%,#283e6d 100%);
            box-shadow: 0 -2px 32px #1a223888;
            border-top-left-radius: 32px;
            border-top-right-radius: 32px;
        }
        .footer-logo-badge img {
            border-radius: 50%;
            border: 3px solid #fff;
            background: #fff;
        }
        .footer-link {
            color: #fff;
            opacity: 0.85;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: color 0.18s, opacity 0.18s;
        }
        .footer-link:hover {
            color: #ffd700;
            opacity: 1;
            text-decoration: underline;
        }
        .social-icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(0,111,190,0.12);
            color: #fff;
            font-size: 1.4rem;
            margin: 0 2px;
            box-shadow: 0 2px 8px #006fbe22;
            transition: background 0.18s, color 0.18s, transform 0.18s;
        }
        .social-icon-circle:hover {
            background: #ffd700;
            color: #1a2238;
            transform: scale(1.13) translateY(-2px);
        }
        .premium-footer hr {
            border-color: #fff;
        }
        .footer-quote {
            color: #e0e6f7;
            font-size: 0.95em;
            margin-top: 0.5em;
        }
        @media (max-width: 767px) {
            .premium-footer { border-radius: 0; }
            .footer-logo-badge img { width:44px;height:44px; }
        }
    </style>
</footer>
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>