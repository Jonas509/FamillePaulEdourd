$pageTitle = "Détail du blog";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}

// Contrôle d'accès
// Récupère l'id du blog
$blogId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$blogId) {
    header('Location: blog.php');
    exit;
}

include 'partials/db.php';
$stmt = $pdo->prepare('SELECT id, title, category, content, author, author_img, image, created_at FROM blog WHERE id = ? AND status = "approved"');
$stmt->execute([$blogId]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$blog) {
    echo '<div class="container py-5"><div class="alert alert-warning">Article introuvable ou non approuvé.</div></div>';
    include 'partials/footer.php';
    exit;
}
<?php
$pageTitle = "Détail du blog";
// Récupère l'id du blog
$blogId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$blogId) {
    header('Location: blog.php');
    exit;
}
include 'partials/db.php';
$stmt = $pdo->prepare('SELECT id, title, category, content, author, author_img, image, created_at FROM blog WHERE id = ? AND status = "approved"');
$stmt->execute([$blogId]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$blog) {
    include 'partials/header.php';
    include 'partials/navbar.php';
    echo '<div class="container py-5"><div class="alert alert-warning">Article introuvable ou non approuvé.</div></div>';
    include 'partials/footer.php';
    exit;
}
include 'partials/header.php';
include 'partials/navbar.php';
?>
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
                            <?php if (!empty($blog['image'])): ?>
                                <img src="<?= htmlspecialchars($blog['image']) ?>" alt="Image blog" class="img-fluid">
                            <?php endif; ?>
                        </div>
                        <h2 class="entry-title">
                            <?= htmlspecialchars($blog['title']) ?>
                        </h2>
                        <div class="entry-meta">
                            <ul>
                                <li><i class="bi bi-person"></i> <a href="#">Auteur inconnu</a></li>
                                <li><i class="bi bi-clock"></i> <a href="#"><time datetime="<?= htmlspecialchars($blog['created_at']) ?>"><?= date('d/m/Y', strtotime($blog['created_at'])) ?></time></a></li>
                                <!-- <li><i class="bi bi-chat-dots"></i> <a href="#">0 commentaires</a></li> -->
                            </ul>
                        </div>
                        <div class="entry-content">
                            <?= nl2br(htmlspecialchars($blog['content'])) ?>
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