<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
$pageTitle = "Tableau de bord admin";
include 'partials/header.php';
include 'partials/navbar.php';
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
// Statistiques
$nbUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$nbFamily = $pdo->query('SELECT COUNT(*) FROM family_tree')->fetchColumn();
$nbPhotos = $pdo->query('SELECT COUNT(*) FROM photos')->fetchColumn();
$nbEvents = $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$nbBlogs = $pdo->query('SELECT COUNT(*) FROM blog')->fetchColumn();
$nbPendingBlogs = $pdo->query("SELECT COUNT(*) FROM blog WHERE status = 'pending'")->fetchColumn();
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Tableau de bord administrateur</h2>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <div class="display-5 fw-bold text-primary mb-2"><?= $nbUsers ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Membres famille</h5>
                        <div class="display-5 fw-bold text-success mb-2"><?= $nbFamily ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Photos</h5>
                        <div class="display-5 fw-bold text-info mb-2"><?= $nbPhotos ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Événements</h5>
                        <div class="display-5 fw-bold text-warning mb-2"><?= $nbEvents ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Articles blog</h5>
                        <div class="display-5 fw-bold text-secondary mb-2"><?= $nbBlogs ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">Articles en attente</h5>
                        <div class="display-5 fw-bold text-danger mb-2"><?= $nbPendingBlogs ?></div>
                        <a href="admin_blog_validation.php" class="btn btn-outline-danger mt-2">Valider les articles</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
