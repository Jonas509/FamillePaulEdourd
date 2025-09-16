<?php
// --- Démarrer la session (obligatoire pour la gestion utilisateur) ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    require_once 'partials/auth.php';
}

// --- Liste blanche des pages accessibles ---
$allowedPages = [
    'index.php', 
    'our-story.php', 
    'events.php', 
    'gallery.php', 
    'blog.php', 
    'blog-details.php', 
    'contact.php', 
    'register.php', 
    'login.php', 
    'logout.php',

    // pages réservées aux membres connectés
    'account.php', 
    'settings.php', 
    'starter-page.php'
];

// --- Récupérer le nom du fichier actuel de manière sécurisée ---
$currentPage = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));

// --- Vérifier si la page est autorisée ---
if (!in_array($currentPage, $allowedPages)) {
    header("Location: index.php");
    exit;
}

// --- Vérifier les pages protégées (accès uniquement si connecté) ---
$protectedPages = ['account.php', 'settings.php', 'starter-page.php'];
if (in_array($currentPage, $protectedPages)) {
    if (!isset($_SESSION['user_id'])) {
        // Redirige vers login si pas connecté
        header("Location: login.php");
        exit;
    }
}

// --- Exemple de contrôle des rôles (facultatif) ---
// if ($currentPage === 'settings.php' && $_SESSION['role'] !== 'admin') {
//     die("Accès interdit.");
// }

// Page d'accueil moderne pour le site Paul Edourd Familli
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
?>

<main class="main">


    <!-- Hero Section Moderne -->
    <section id="hero" class="hero-section d-flex align-items-center justify-content-center text-center position-relative" style="min-height: 60vh; background: linear-gradient(rgba(36,50,93,0.7),rgba(0,111,190,0.6)), url('assets/img/hero-carousel/hero-bg.jpg') center/cover no-repeat;">
        <div class="container position-relative z-2 py-5">
            <h1 class="display-2 fw-bold mb-3 text-white text-shadow">Famille Paul-Edourd</h1>
            <p class="lead mb-4 text-white-50 fs-4">Unis par le sang, liés par le cœur.<br>Découvrez, partagez et écrivez l’histoire de notre famille !</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="register.php" class="btn btn-primary btn-lg px-4 py-2 shadow hero-btn">Rejoindre la famille</a>
                <a href="gallery.php" class="btn btn-outline-light btn-lg px-4 py-2 hero-btn">Galerie</a>
                <a href="blog.php" class="btn btn-outline-info btn-lg px-4 py-2 hero-btn">Blog</a>
                <a href="view_family_links_graph.php" class="btn btn-success btn-lg px-4 py-2 hero-btn">Arbre graphique</a>
            </div>
        </div>
        <style>
        .hero-section {
            position: relative;
            overflow: hidden;
        }
        git add composer.json
        git commit -m "Ajout du composer.json pour gestion des dépendances PHP"
        git push        git add composer.json
        git commit -m "Ajout du composer.json pour gestion des dépendances PHP"
        git push        .hero-section:before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: inherit;
            z-index: 1;
            opacity: 0.7;
        }
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        .text-shadow {
            text-shadow: 0 4px 24px rgba(0,0,0,0.4), 0 1.5px 0 #24325d;
        }
        .hero-btn {
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .hero-btn:hover {
            transform: translateY(-4px) scale(1.04);
            box-shadow: 0 8px 32px rgba(0,111,190,0.18);
        }
        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2.2rem; }
            .hero-section .lead { font-size: 1.1rem; }
        }
        </style>
    </section>

    <!-- Section À propos personnalisée -->
    <section id="about" class="about section py-5">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <img src="assets/img/about.jpg" class="img-fluid rounded shadow" alt="Photo de famille">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3 text-wrap" style="color:#006fbe;word-break:break-word;">Bienvenue chez les Paul-Edourd</h2>
                    <p class="fst-italic">Ici, chaque membre compte et chaque souvenir est précieux. Notre site permet de garder le lien, d’organiser des événements et de transmettre notre histoire aux générations futures.</p>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><i class="bi bi-check2-all text-primary me-2"></i> Partage de photos et anecdotes familiales</li>
                        <li class="mb-2"><i class="bi bi-check2-all text-success me-2"></i> Arbre généalogique interactif</li>
                        <li class="mb-2"><i class="bi bi-check2-all text-info me-2"></i> Blog et actualités familiales</li>
                        <li class="mb-2"><i class="bi bi-check2-all text-warning me-2"></i> Organisation d’événements</li>
                    </ul>
                    <blockquote class="blockquote text-muted">“La famille, c’est là où la vie commence et où l’amour ne finit jamais.”</blockquote>
                </div>
            </div>
        </div>
    </section>

    <!-- Mise en avant des membres -->
    <?php
    include 'partials/db.php';
    $members = $pdo->query("SELECT id, firstname, lastname, photo FROM family_tree WHERE photo IS NOT NULL ORDER BY RAND() LIMIT 4")->fetchAll();
    if (!empty($members)):
    ?>
    <section id="members" class="members section py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold text-center mb-4 text-wrap" style="color:#24325d;word-break:break-word;">Membres à l’honneur</h2>
            <div class="row justify-content-center g-4">
                <?php foreach ($members as $m): ?>
                <div class="col-12 col-sm-6 col-md-3 text-center">
                    <img src="<?= htmlspecialchars($m['photo'] ?? 'assets/img/default-profile.png') ?>" class="img-fluid rounded-circle mb-2 shadow" style="width:100px;height:100px;object-fit:cover;">
                    <h5 class="mb-0" style="color:#006fbe;"><?= htmlspecialchars($m['firstname'] ?? 'Prénom inconnu') ?></h5>
                    <small class="text-muted"><?= htmlspecialchars($m['lastname'] ?? '') ?></small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <!-- Section Fonctionnalités -->
    <section id="features" class="features section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item position-relative">
                        <div class="icon">
                            <i class="bi bi-activity"></i>
                        </div>
                        <h3>Partage familial</h3>
                        <p>Publiez des souvenirs, des photos et des messages pour toute la famille.</p>
                    </div>
                </div><!-- Fin Élément Fonctionnalité -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-item position-relative">
                        <div class="icon">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <h3>Arbre généalogique</h3>
                        <p>Visualisez et enrichissez votre arbre familial de façon interactive.</p>
                    </div>
                </div><!-- Fin Élément Fonctionnalité -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-item position-relative">
                        <div class="icon">
                            <i class="bi bi-easel"></i>
                        </div>
                        <h3>Événements familiaux</h3>
                        <p>Planifiez et annoncez les anniversaires, mariages et réunions de famille.</p>
                    </div>
                </div><!-- Fin Élément Fonctionnalité -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-item position-relative">
                        <div class="icon">
                            <i class="bi bi-bounding-box-circles"></i>
                        </div>
                        <h3>Galerie privée</h3>
                        <p>Conservez et partagez vos plus belles photos de famille en toute sécurité.</p>
                    </div>
                </div><!-- Fin Élément Fonctionnalité -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-item position-relative">
                        <div class="icon">
                            <i class="bi bi-calendar4-week"></i>
                        </div>
                        <h3>Notifications</h3>
                        <p>Recevez des rappels pour ne rien manquer des moments importants.</p>
                    </div>
                </div><!-- Fin Élément Fonctionnalité -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-item position-relative">
                        <div class="icon">
                            <i class="bi bi-chat-square-text"></i>
                        </div>
                        <h3>Discussions privées</h3>
                        <p>Échangez en toute confidentialité avec les membres de votre famille.</p>
                    </div>
                </div><!-- Fin Élément Fonctionnalité -->
            </div>
        </div>
    </section><!-- /Section Fonctionnalités -->

    <!-- Carrousel d’images récentes -->
    <?php
    $recentPhotos = $pdo->query('SELECT filename FROM photos WHERE status = "active" ORDER BY updated_at DESC, id DESC LIMIT 8')->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($recentPhotos)):
    ?>
    <section id="recent-photos" class="recent-photos section py-5">
        <div class="container">
            <h2 class="fw-bold text-center mb-4 text-wrap" style="color:#24325d;word-break:break-word;">Souvenirs en images</h2>
            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
                {
                    "loop": true,
                    "speed": 600,
                    "autoplay": {
                        "delay": 5000
                    },
                    "slidesPerView": "auto",
                    "centeredSlides": true,
                    "pagination": {
                        "el": ".swiper-pagination",
                        "type": "bullets",
                        "clickable": true
                    },
                    "breakpoints": {
                        "320": {
                            "slidesPerView": 1,
                            "spaceBetween": 0
                        },
                        "768": {
                            "slidesPerView": 3,
                            "spaceBetween": 20
                        },
                        "1200": {
                            "slidesPerView": 5,
                            "spaceBetween": 20
                        }
                    }
                }
                </script>
                <div class="swiper-wrapper align-items-center">
                    <?php foreach ($recentPhotos as $img): ?>
                    <div class="swiper-slide d-flex justify-content-center align-items-center" style="height:220px;">
                        <a class="glightbox" data-gallery="images-gallery" href="<?= htmlspecialchars($img ?? 'assets/img/gallery/gallery-empty.png') ?>">
                            <div style="width:200px;height:200px;background:#f8f9fa;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
                                <img src="<?= htmlspecialchars($img ?? 'assets/img/gallery/gallery-empty.png') ?>" class="img-fluid" alt="Photo récente" loading="lazy" style="max-width:100%;max-height:100%;object-fit:cover;aspect-ratio:1/1;" onerror="this.src='assets/img/gallery/gallery-empty.png'">
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <!-- Section Derniers articles du blog -->
    <?php
    $posts = $pdo->query("SELECT id, title, content, image, created_at FROM blog WHERE status = 'approved' ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($posts)):
    ?>
    <section id="recent-blog" class="recent-blog section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Derniers articles du blog</h2>
            <p>Découvrez les dernières histoires et actualités partagées par votre famille.</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <?php foreach ($posts as $post): ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100">
                        <?php if (!empty($post['image'])): ?>
                        <img src="<?= htmlspecialchars($post['image'] ?? 'assets/img/blog/blog-default.png') ?>" class="card-img-top" alt="Image blog" style="height:180px;object-fit:cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title mb-2"><?= htmlspecialchars($post['title'] ?? 'Titre inconnu') ?></h5>
                            <p class="card-text" style="font-size:0.95em;">
                                <?= htmlspecialchars(mb_strimwidth(strip_tags($post['content']), 0, 120, '...')) ?>
                            </p>
                            <a href="blog-details.php?id=<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm">Lire la suite</a>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="bi bi-calendar-event me-1"></i> <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>


</main>
<?php
include 'partials/footer.php';
?>