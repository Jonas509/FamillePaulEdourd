<?php
$pageTitle = "Détail du blog";
include 'partials/header.php';
include 'partials/navbar.php';

// Contrôle d'accès
$allowedPages = ['blog-details.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $allowedPages)) {
    header('Location: index.php');
    exit;
}
?>

<main class="main">

    <!-- Titre de la page -->
    <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Détail de l'article</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="blog.php">Blog familial</a></li>
                    <li class="current">Détail de l'article</li>
                </ol>
            </nav>
        </div>
    </div><!-- Fin Titre de la page -->

    <!-- Blog Details Section -->
    <section id="blog-details" class="blog-details section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 entries">
                    <article class="entry entry-single">

                        <div class="entry-img">
                            <img src="assets/img/blog/blog-1.jpg" alt="" class="img-fluid">
                        </div>

                        <h2 class="entry-title">
                            Un moment inoubliable partagé en famille
                        </h2>
                        <div class="entry-meta">
                            <ul>
                                <li><i class="bi bi-person"></i> <a href="#">Maria Dupont</a></li>
                                <li><i class="bi bi-clock"></i> <a href="#"><time datetime="2022-01-01">1 Janv.
                                            2022</time></a></li>
                                <li><i class="bi bi-chat-dots"></i> <a href="#">12 commentaires</a></li>
                            </ul>
                        </div>
                        <div class="entry-content">
                            <p>
                                Nous avons passé une journée extraordinaire entourés de toute la famille. Rires,
                                souvenirs et moments précieux étaient au rendez-vous.
                            </p>
                            <p>
                                Merci à tous pour votre présence et votre bonne humeur ! Ce sont ces instants qui
                                rendent notre famille si unique et soudée.
                            </p>
                        </div>

                    </article><!-- End blog entry -->

                    <!-- Comments Section (à adapter selon ton besoin) -->
                    <div class="blog-comments">
                        <h4>Commentaires</h4>
                        <!-- Exemple de commentaire -->
                        <div class="comment">
                            <div class="d-flex">
                                <div class="comment-img"><img src="assets/img/blog/comments-1.jpg" alt=""></div>
                                <div>
                                    <h5><a href="">Georges Martin</a> <a href="#" class="reply"><i
                                                class="bi bi-reply-fill"></i> Répondre</a></h5>
                                    <time datetime="2022-01-01">01 Janv. 2022</time>
                                    <p>
                                        Un superbe souvenir, merci à tous pour cette belle journée !
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Ajoutez d'autres commentaires ici -->
                    </div>
                    <!-- End Comments Section -->

                    <!-- Comment Form -->
                    <section id="comment-form" class="comment-form section">
                        <div class="container">
                            <form action="">
                                <h4>Laisser un commentaire</h4>
                                <p>Votre adresse e-mail ne sera pas publiée. Les champs obligatoires sont indiqués avec
                                    *</p>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <input name="name" type="text" class="form-control" placeholder="Votre nom*">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input name="email" type="text" class="form-control"
                                            placeholder="Votre e-mail*">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col form-group">
                                        <input name="website" type="text" class="form-control"
                                            placeholder="Votre site web">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col form-group">
                                        <textarea name="comment" class="form-control"
                                            placeholder="Votre commentaire*"></textarea>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Envoyer le commentaire</button>
                                </div>
                            </form>
                        </div>
                    </section>
                    <!-- End Comment Form -->

                </div><!-- End col-lg-8 -->

                <div class="col-lg-4 sidebar">
                    <!-- Widgets (auteur, recherche, catégories, etc.) à reprendre de ton code -->
                </div><!-- End sidebar -->

            </div><!-- End row -->
        </div><!-- End container -->
    </section><!-- End Blog Details Section -->

</main>

<?php
include 'partials/footer.php';
?>