<?php
// --- Démarrer la session (obligatoire pour la gestion utilisateur) ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
include 'partials/navbar.php';
?>

<main class="main">

    <!-- Section Héros moderne -->
    <section id="hero" class="hero section py-5" style="background: linear-gradient(120deg, #f8fafc 60%, #e3f0ff 100%); min-height: 350px;">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-3" style="color:#24325d;">Bienvenue sur <span style="color:#006fbe;">Me &amp; Family</span></h1>
            <p class="lead mb-4" style="max-width:600px;margin:auto;">Retrouvez, partagez et célébrez l'histoire de votre famille dans un espace moderne, sécurisé et convivial.</p>
            <a href="register.php" class="btn btn-primary btn-lg shadow-sm me-2">Rejoindre la famille</a>
            <a href="gallery.php" class="btn btn-outline-primary btn-lg">Voir la galerie</a>
        </div>
    </section>

    <!-- Blocs modernes -->
    <section class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-diagram-3 display-3 mb-3 text-primary"></i>
                        <h5 class="card-title mb-2">Créer votre arbre généalogique</h5>
                        <p class="card-text">Ajoutez vos proches et visualisez les liens familiaux sous forme d'arbre graphique ou textuel.</p>
                        <a href="view_family_links_graph.php" class="btn btn-outline-primary">Voir l'arbre graphique</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-images display-3 mb-3 text-primary"></i>
                        <h5 class="card-title mb-2">Partagez vos souvenirs</h5>
                        <p class="card-text">Publiez des photos, des événements et des anecdotes pour enrichir la mémoire familiale.</p>
                        <a href="gallery.php" class="btn btn-outline-primary">Accéder à la galerie</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people display-3 mb-3 text-primary"></i>
                        <h5 class="card-title mb-2">Restez connectés</h5>
                        <p class="card-text">Discutez, commentez et organisez des événements pour renforcer les liens familiaux.</p>
                        <a href="events.php" class="btn btn-outline-primary">Voir les événements</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section id="about" class="about section">
        <!-- Titre de section -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Famille &amp; Moi</h2>
            <p>Un espace privé pour se retrouver, partager et renforcer les liens familiaux.</p>
        </div><!-- Fin Titre de section -->
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <img src="assets/img/about.jpg" class="img-fluid" alt="Photo de famille">
                </div>
                <div class="col-lg-6 content">
                    <h3>Un réseau social familial chaleureux</h3>
                    <p class="fst-italic">
                        Rejoignez votre famille, partagez vos souvenirs, photos et événements dans un espace sécurisé et
                        convivial.
                    </p>
                    <ul>
                        <li><i class="bi bi-check2-all"></i> <span>Partagez des photos et des histoires
                                familiales.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>Gardez le contact avec tous les membres, où qu'ils
                                soient.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>Organisez des événements et créez votre arbre
                                généalogique.</span></li>
                    </ul>
                    <p>
                        Notre plateforme est conçue pour rapprocher les familles et préserver leur histoire génération
                        après génération.
                    </p>
                </div>
            </div>
        </div>
    </section><!-- /Section À propos -->

    <!-- Section Fonctionnalités -->
    <section id="features" class="features section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
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

    <!-- Section Photos récentes -->
    <section id="recent-photos" class="recent-photos section">
        <!-- Titre de section -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Photos récentes</h2>
            <p>Découvrez les derniers souvenirs partagés par votre famille.</p>
        </div><!-- Fin Titre de section -->
        <div class="container" data-aos="fade-up" data-aos-delay="100">
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
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
// Récupère les 8 dernières photos actives
$stmt = $pdo->prepare('SELECT filename FROM photos WHERE status = "active" ORDER BY updated_at DESC, id DESC LIMIT 8');
$stmt->execute();
$recentPhotos = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
                <div class="swiper-wrapper align-items-center">
                    <?php if (!empty($recentPhotos)): ?>
                    <?php foreach ($recentPhotos as $img): ?>
                    <div class="swiper-slide d-flex justify-content-center align-items-center" style="height:220px;">
                        <a class="glightbox" data-gallery="images-gallery" href="<?= htmlspecialchars($img) ?>">
                            <div
                                style="width:200px;height:200px;background:#f8f9fa;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
                                <img src="<?= htmlspecialchars($img) ?>" class="img-fluid" alt="Photo récente"
                                    loading="lazy"
                                    style="max-width:100%;max-height:100%;object-fit:cover;aspect-ratio:1/1;"
                                    onerror="this.src='assets/img/gallery/gallery-empty.png'">
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="swiper-slide d-flex justify-content-center align-items-center" style="height:220px;">
                        <div
                            style="width:200px;height:200px;background:#f8f9fa;border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
                            <i class="bi bi-images text-muted" style="font-size:48px;"></i>
                        </div>
                        <div class="text-muted ms-3">Aucune photo récente</div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section><!-- /Section Photos récentes -->

    <!-- Section Derniers articles du blog -->
    <section id="recent-blog" class="recent-blog section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Derniers articles du blog</h2>
            <p>Découvrez les dernières histoires et actualités partagées par votre famille.</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
                $posts = $pdo->query("SELECT id, title, content, image, created_at FROM blog WHERE status = 'approved' ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow h-100">
                            <?php if (!empty($post['image'])): ?>
                            <img src="<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="Image blog" style="height:180px;object-fit:cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title mb-2"><?= htmlspecialchars($post['title']) ?></h5>
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
                <?php else: ?>
                    <div class="col-12 text-center text-muted">Aucun article récent</div>
                <?php endif; ?>
            </div>
        </div>
    </section><!-- /Section Derniers articles du blog -->

</main>
<?php
include 'partials/footer.php';
?>