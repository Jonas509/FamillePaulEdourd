<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ==================== NAVBAR ====================
// Barre de navigation commune à toutes les pages.

// Définir le titre de la page si non déjà défini
if (!isset($pageTitle)) {
    $pageTitle = "Famille Paul-Edourd";
}

// Déterminer la page active
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="index.php" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="assets/img/logo.png" alt="Logo famille Paul-Edourd">
            <h4 class="sitename"> <strong>Famille</strong> Paul-Edourd</h4>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php"
                        class="<?php echo ($currentPage == 'home.php' || $currentPage == 'index.php') ? 'active' : ''; ?>">Accueil</a>
                </li>
                <li><a href="our-story.php"
                        class="<?php echo ($currentPage == 'our-story.php') ? 'active' : ''; ?>">Notre histoire</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <li><a href="admin_dashboard.php" class="<?php echo ($currentPage == 'admin_dashboard.php') ? 'active' : ''; ?>">Dashboard Admin</a></li>
                <?php endif; ?>
                <li><a href="events.php"
                        class="<?php echo ($currentPage == 'events.php') ? 'active' : ''; ?>">Événements</a></li>
                <li><a href="gallery.php"
                        class="<?php echo ($currentPage == 'gallery.php') ? 'active' : ''; ?>">Galerie</a></li>
                <li><a href="blog.php" class="<?php echo ($currentPage == 'blog.php') ? 'active' : ''; ?>">Blog</a></li>
                <?php endif; ?>
                <li class="dropdown"><a href="#"><span>Plus</span> <i
                            class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="add_family_member.php">Ajouter un membre</a></li>
                        <li><a href="link_family_members.php">Lier des membres</a></li>
                        <li><a href="view_family_links.php">Voir liens (texte)</a></li>
                        <li><a href="view_family_links_graph.php">Arbre graphique</a></li>
                        <?php endif; ?>
                        <li class="dropdown"><a href="#"><span>Arbre classique</span> <i
                                    class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="family_tree.php">Arbre généalogique</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="contact.php"
                        class="<?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
            </ul>
        </nav>

        <div class="header-actions d-flex align-items-center">
            <div class="top-bar-item dropdown me-3">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="langDropdown">
                    <i class="bi bi-translate me-2"></i>EN
                </a>
                <ul class="dropdown-menu" aria-labelledby="langDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-check2 me-2 selected-icon"></i>English</a>
                    </li>
                    <li><a class="dropdown-item" href="#">Español</a></li>
                    <li><a class="dropdown-item" href="#">Français</a></li>
                    <li><a class="dropdown-item" href="#">Deutsch</a></li>
                </ul>
            </div>
            <!-- Account -->
            <div class="top-bar-item dropdown me-3">
                <a href="#" class="dropdown-toggle header-action-btn" data-bs-toggle="dropdown" aria-expanded="false"
                    id="accountDropdown">
                    <i class="bi bi-person"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                    <li class="dropdown-header">
                        <?php if (isset($_SESSION['user'])): ?>
                        <h6><?php echo htmlspecialchars($_SESSION['user']['name']); ?></h6>
                        <p class="mb-0">Bienvenue sur votre espace familial</p>
                        <?php else: ?>
                        <h6>Welcome to <span class="sitename"><?php echo htmlspecialchars($pageTitle); ?></span></h6>
                        <p class="mb-0">Access your family account</p>
                        <?php endif; ?>
                    </li>
                    <?php if (isset($_SESSION['user'])): ?>
                    <li><a class="dropdown-item d-flex align-items-center" href="account.php">
                            <i class="bi bi-person-circle me-2"></i>
                            <span>My Profile</span>
                        </a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="gallery.php">
                            <i class="bi bi-images me-2"></i>
                            <span>My Photos</span>
                        </a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="events.php">
                            <i class="bi bi-calendar-event me-2"></i>
                            <span>My Events</span>
                        </a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="settings.php">
                            <i class="bi bi-gear me-2"></i>
                            <span>Settings</span>
                        </a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="logout.php" class="dropdown-item">Déconnexion</a></li>
                    <?php else: ?>
                    <li><a href="login.php" class="dropdown-item">Connexion</a></li>
                    <li><a href="register.php" class="dropdown-item">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </div>

    </div>
</header>