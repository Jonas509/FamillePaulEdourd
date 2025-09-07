<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Galerie";
include 'partials/header.php';
include 'partials/navbar.php';
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
$maxPhotos = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPhotos = $pdo->query('SELECT COUNT(*) FROM photos WHERE status = "active"')->fetchColumn();
$totalPages = ceil($totalPhotos / $maxPhotos);
if (isset($_GET['page']) && (!is_numeric($_GET['page']) || $_GET['page'] < 1 || $_GET['page'] > $totalPages)) {
    header('Location: index.php');
    exit;
}
$offset = ($page - 1) * $maxPhotos;
$stmt = $pdo->prepare('SELECT filename FROM photos WHERE status = "active" ORDER BY id DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $maxPhotos, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$galleryPhotos = $stmt->fetchAll(PDO::FETCH_COLUMN);
// Compte total
$totalPhotos = $pdo->query('SELECT COUNT(*) FROM photos WHERE status = "active"')->fetchColumn();
$totalPages = ceil($totalPhotos / $maxPhotos);
$allowedPages = ['gallery.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $allowedPages)) {
    header('Location: index.php');
    exit;
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Galerie familiale</h2>
        <div class="row g-2 mb-4">
            <?php if (!empty($galleryPhotos)): ?>
            <?php foreach ($galleryPhotos as $img): ?>
            <div class="col-4">
                <div class="gallery-img-wrapper"
                    style="position:relative;overflow:hidden;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                    <img src="<?= htmlspecialchars($img) ?>" class="img-fluid w-100 gallery-clickable"
                        alt="Photo utilisateur" loading="lazy"
                        style="aspect-ratio:1/1;object-fit:cover;transition:transform 0.3s;cursor:pointer;"
                        onerror="this.src='assets/img/gallery/gallery-empty.png'" onclick="showLightbox(this.src)"
                        onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center text-muted" style="font-size:48px;">
                <i class="bi bi-images"></i>
                <div>Pas de photos</div>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Pagination galerie">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                    <a class="page-link" href="gallery.php?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</main>
<div id="gallery-lightbox"
    style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.85);align-items:center;justify-content:center;z-index:9999;">
    <img src="" style="max-width:90vw;max-height:90vh;border-radius:10px;box-shadow:0 2px 16px #000;">
    <span style="position:absolute;top:20px;right:40px;font-size:48px;color:#fff;cursor:pointer;z-index:10000;"
        onclick="document.getElementById('gallery-lightbox').style.display='none'">&times;</span>
</div>
<script>
function showLightbox(src) {
    let modal = document.getElementById('gallery-lightbox');
    if (modal) {
        modal.querySelector('img').src = src;
        modal.style.display = 'flex';
    }
}
document.getElementById('gallery-lightbox').onclick = function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
};
</script>
<?php
include 'partials/footer.php';
?>