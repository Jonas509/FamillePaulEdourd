<?php
$pageTitle = "Blog";
include 'partials/header.php';
include 'partials/navbar.php';

// Contrôle d'accès
$allowedPages = ['blog.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $allowedPages)) {
    header('Location: index.php');
    exit;
}
?>

<main class="main">

    <!-- Titre de la page -->
    <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Blog familial</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.php">Accueil</a></li>
                    <li class="current">Blog familial</li>
                </ol>
            </nav>
        </div>
    </div><!-- Fin Titre de la page -->

    <!-- Section des articles du blog -->
    <section id="blog-posts" class="blog-posts section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <article>
                        <div class="post-img">
                            <img src="assets/img/blog/blog-1.jpg" alt="" class="img-fluid">
                        </div>
                        <p class="post-category">Famille</p>
                        <h2 class="title">
                            <a href="blog-details.php">Un moment inoubliable partagé en famille</a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/blog/blog-author.jpg" alt=""
                                class="img-fluid post-author-img flex-shrink-0">
                            <div class="post-meta">
                                <p class="post-author">Maria Dupont</p>
                                <p class="post-date">
                                    <time datetime="2022-01-01">1 Janv. 2022</time>
                                </p>
                            </div>
                        </div>
                    </article>
                </div><!-- Fin article -->

                <div class="col-lg-4">
                    <article>
                        <div class="post-img">
                            <img src="assets/img/blog/blog-2.jpg" alt="" class="img-fluid">
                        </div>
                        <p class="post-category">Sport</p>
                        <h2 class="title">
                            <a href="blog-details.php">Tournoi de foot familial : souvenirs et rires</a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/blog/blog-author-2.jpg" alt=""
                                class="img-fluid post-author-img flex-shrink-0">
                            <div class="post-meta">
                                <p class="post-author">Alice Martin</p>
                                <p class="post-date">
                                    <time datetime="2022-06-05">5 Juin 2022</time>
                                </p>
                            </div>
                        </div>
                    </article>
                </div><!-- Fin article -->

                <div class="col-lg-4">
                    <article>
                        <div class="post-img">
                            <img src="assets/img/blog/blog-3.jpg" alt="" class="img-fluid">
                        </div>
                        <p class="post-category">Divertissement</p>
                        <h2 class="title">
                            <a href="blog-details.php">Soirée jeux de société : fous rires garantis</a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/blog/blog-author-3.jpg" alt=""
                                class="img-fluid post-author-img flex-shrink-0">
                            <div class="post-meta">
                                <p class="post-author">Marc Dubois</p>
                                <p class="post-date">
                                    <time datetime="2022-06-22">22 Juin 2022</time>
                                </p>
                            </div>
                        </div>
                    </article>
                </div><!-- Fin article -->

                <div class="col-lg-4">
                    <article>
                        <div class="post-img">
                            <img src="assets/img/blog/blog-4.jpg" alt="" class="img-fluid">
                        </div>
                        <p class="post-category">Sport</p>
                        <h2 class="title">
                            <a href="blog-details.php">Randonnée familiale au grand air</a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/blog/blog-author-4.jpg" alt=""
                                class="img-fluid post-author-img flex-shrink-0">
                            <div class="post-meta">
                                <p class="post-author">Lisa Bernard</p>
                                <p class="post-date">
                                    <time datetime="2022-06-30">30 Juin 2022</time>
                                </p>
                            </div>
                        </div>
                    </article>
                </div><!-- Fin article -->

                <div class="col-lg-4">
                    <article>
                        <div class="post-img">
                            <img src="assets/img/blog/blog-5.jpg" alt="" class="img-fluid">
                        </div>
                        <p class="post-category">Famille</p>
                        <h2 class="title">
                            <a href="blog-details.php">Anniversaire de Mamie : 80 ans d'amour</a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/blog/blog-author-5.jpg" alt=""
                                class="img-fluid post-author-img flex-shrink-0">
                            <div class="post-meta">
                                <p class="post-author">Denis Petit</p>
                                <p class="post-date">
                                    <time datetime="2022-01-30">30 Janv. 2022</time>
                                </p>
                            </div>
                        </div>
                    </article>
                </div><!-- Fin article -->

                <div class="col-lg-4">
                    <article>
                        <div class="post-img">
                            <img src="assets/img/blog/blog-6.jpg" alt="" class="img-fluid">
                        </div>
                        <p class="post-category">Divertissement</p>
                        <h2 class="title">
                            <a href="blog-details.php">Spectacle des enfants : souvenirs magiques</a>
                        </h2>
                        <div class="d-flex align-items-center">
                            <img src="assets/img/blog/blog-author-6.jpg" alt=""
                                class="img-fluid post-author-img flex-shrink-0">
                            <div class="post-meta">
                                <p class="post-author">Mika Lemoine</p>
                                <p class="post-date">
                                    <time datetime="2022-02-14">14 Fév. 2022</time>
                                </p>
                            </div>
                        </div>
                    </article>
                </div><!-- Fin article -->

            </div>
        </div>

    </section><!-- /Blog Posts Section -->

    <!-- Pagination du blog -->
    <section id="blog-pagination" class="blog-pagination section">
        <div class="container">
            <div class="d-flex justify-content-center">
                <ul>
                    <li><a href="#"><i class="bi bi-chevron-left"></i></a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#" class="active">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li>...</li>
                    <li><a href="#">10</a></li>
                    <li><a href="#"><i class="bi bi-chevron-right"></i></a></li>
                </ul>
            </div>
        </div>
    </section><!-- /Pagination du blog -->

</main>
<?php
include 'partials/footer.php';
?>