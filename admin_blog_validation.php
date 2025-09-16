<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
$pageTitle = "Validation des articles";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
include 'partials/db.php';
// Validation ou suppression
if (isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    if ($_POST['action'] === 'approve') {
        $pdo->prepare('UPDATE blog SET status = "approved" WHERE id = ?')->execute([$id]);
    } elseif ($_POST['action'] === 'delete') {
        $pdo->prepare('DELETE FROM blog WHERE id = ?')->execute([$id]);
    }
}
// Récupérer les articles en attente
$pending = $pdo->query('SELECT id, title, category, content, author, author_img, image, created_at FROM blog WHERE status = "pending" ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Articles à valider</h2>
        <?php if (count($pending)): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($pending as $p): ?>
            <div class="col">
                <div class="card border-0 shadow h-100">
                    <img src="<?= htmlspecialchars($p['image'] ?? 'assets/img/blog/blog-empty.jpg') ?>" alt="" class="card-img-top" style="object-fit:cover;height:220px;">
                    <div class="card-body">
                        <span class="badge bg-warning mb-2">En attente</span>
                        <h5 class="card-title mb-2"><?= htmlspecialchars($p['title']) ?></h5>
                        <div class="mb-2 text-muted" style="font-size:0.95em;">
                            <?= htmlspecialchars($p['category'] ?? 'Blog') ?>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold">Auteur :</span> <?= htmlspecialchars($p['author']) ?>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold">Date :</span> <?= date('d M Y', strtotime($p['created_at'])) ?>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold">Contenu :</span> <?= nl2br(htmlspecialchars($p['content'])) ?>
                        </div>
                        <form method="post" class="d-flex gap-2 mt-3">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit" name="action" value="approve" class="btn btn-success">Approuver</button>
                            <button type="submit" name="action" value="delete" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info">Aucun article en attente de validation.</div>
        <?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
