<?php
$pageTitle = "Events";
include 'partials/header.php';
include 'partials/navbar.php';
?>

<main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Events</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.html">Home</a></li>
                    <li class="current">Events</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <!-- Events Section -->
    <section id="events" class="events section">

        <div class="container" data-aos="fade-up">

            <div class="row">
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="card">
                        <div class="card-img">
                            <img src="assets/img/events-1.jpg" alt="...">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Lara's 1th Birthday</h5>
                            <p class="fst-italic text-center">Sunday, September 26th at 7:00 pm</p>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                ullamco laboris nisi ut aliquip ex ea commodo consequat</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="card">
                        <div class="card-img">
                            <img src="assets/img/events-2.jpg" alt="...">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">James 6th Birthday</h5>
                            <p class="fst-italic text-center">Sunday, November 15th at 7:00 pm</p>
                            <p class="card-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et
                                quasi architecto beatae vitae dicta sunt explicabo</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </section><!-- /Events Section -->

</main>

<footer id="footer" class="footer dark-background">
    <div class="container">
        <h3 class="sitename">Me &amp; Family</h3>
        <p>Et aut eum quis fuga eos sunt ipsa nihil. Labore corporis magni eligendi fuga maxime saepe commodi placeat.
        </p>
        <div class="social-links d-flex justify-content-center">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-skype"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
        <div class="container">
            <div class="copyright">
                <span>Copyright</span> <strong class="px-1 sitename">Me &amp; Family</strong> <span>All Rights
                    Reserved</span>
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </div>
    <?php
include 'partials/footer.php';
?>