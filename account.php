<?php
require_once 'partials/auth.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Contrôle d'accès à la page
$allowedPages = ['account.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $allowedPages)) {
    header('Location: index.php');
    exit;
}

// Traitement de l'upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $userId = $_SESSION['user']['id'];
    $uploadDir = 'assets/img/gallery/';
    $filename = uniqid('user_' . $userId . '_') . '_' . basename($_FILES['photo']['name']);
    $targetPath = $uploadDir . $filename;
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['photo']['type'], $allowedTypes)) {
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            include 'partials/db.php';
            $stmt = $pdo->prepare('INSERT INTO photos (user_id, filename) VALUES (?, ?)');
            $stmt->execute([$userId, $targetPath]);
            // Recharge la page pour afficher la nouvelle photo
            header('Location: account.php');
            exit;
        } else {
            $uploadError = 'Erreur lors de l’enregistrement du fichier.';
        }
    } else {
        $uploadError = 'Format de fichier non autorisé.';
    }
}

// Suppression d'une photo
if (isset($_GET['delete_photo'])) {
    $photoId = intval($_GET['delete_photo']);
    $userId = $_SESSION['user']['id'];
    include 'partials/db.php';
    $stmt = $pdo->prepare('SELECT filename FROM photos WHERE id = ? AND user_id = ?');
    $stmt->execute([$photoId, $userId]);
    $photo = $stmt->fetchColumn();
    if ($photo && file_exists($photo)) {
        unlink($photo);
    }
    $stmt = $pdo->prepare('DELETE FROM photos WHERE id = ? AND user_id = ?');
    $stmt->execute([$photoId, $userId]);
    header('Location: account.php');
    exit;
}

$pageTitle = "Mon compte";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
?>

<?php
// Illustration avatar utilisateur et galerie vide
$profilePhoto = 'assets/img/avatar-default.png'; // Illustration style utilisateur
$galleryPhotos = [
    'assets/img/gallery/gallery-empty.png',
    'assets/img/gallery/gallery-empty.png',
    'assets/img/gallery/gallery-empty.png'
];
if (isset($_SESSION['user']['profile_photo']) && $_SESSION['user']['profile_photo']) {
    $profilePhoto = htmlspecialchars($_SESSION['user']['profile_photo']);
}
// Pagination pour la galerie
$maxPhotos = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $maxPhotos;
$userId = $_SESSION['user']['id'];
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
// Récupère uniquement les photos actives de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT id, filename FROM photos WHERE user_id = ? AND status = "active" LIMIT ? OFFSET ?');
$stmt->bindValue(1, $userId, PDO::PARAM_INT);
$stmt->bindValue(2, $maxPhotos, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$galleryPhotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Compte total
$stmt = $pdo->prepare('SELECT COUNT(*) FROM photos WHERE user_id = ? AND status = "active"');
$stmt->execute([$userId]);
$totalPhotos = $stmt->fetchColumn();
$totalPages = ceil($totalPhotos / $maxPhotos);
if (isset($_GET['page']) && (!is_numeric($_GET['page']) || $_GET['page'] < 1 || $_GET['page'] > $totalPages)) {
    header('Location: index.php');
    exit;
}
?>

<main class="main">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <?php if (isset($_SESSION['user']['profile_photo']) && $_SESSION['user']['profile_photo']): ?>
                            <img src="<?= htmlspecialchars($_SESSION['user']['profile_photo']) ?>" alt="Photo de profil"
                                class="rounded-circle me-3" style="width: 90px; height: 90px; object-fit: cover;">
                            <?php else: ?>
                            <span class="rounded-circle d-flex align-items-center justify-content-center bg-light me-3"
                                style="width:90px;height:90px;font-size:48px;">
                                <i class="bi bi-person-circle"></i>
                            </span>
                            <?php endif; ?>
                            <div>
                                <h3 class="mb-0"><?= htmlspecialchars($_SESSION['user']['name']) ?></h3>
                                <p class="mb-1 text-muted">Pseudo :
                                    <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                                <span class="badge bg-primary">Connecté</span>
                            </div>
                        </div>
                        <hr>
                        <h5>Informations personnelles</h5>
                        <ul class="list-unstyled mb-4">
                            <li><strong>Email :</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?></li>
                            <!-- Ajoute d'autres infos si disponibles dans la session -->
                        </ul>
                        <h5>Galerie photos</h5>
                        <div class="row g-2 mb-4">
                            <?php
                            // Récupère les id et chemins des photos pour affichage et suppression
                            $stmt = $pdo->prepare('SELECT id, filename FROM photos WHERE user_id = ? AND status = "active" LIMIT ? OFFSET ?');
                            $stmt->bindValue(1, $userId, PDO::PARAM_INT);
                            $stmt->bindValue(2, $maxPhotos, PDO::PARAM_INT);
                            $stmt->bindValue(3, $offset, PDO::PARAM_INT);
                            $stmt->execute();
                            $galleryPhotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php if (!empty($galleryPhotos)): ?>
                            <?php foreach ($galleryPhotos as $photo): ?>
                            <div class="col-6 col-md-3">
                                <div class="gallery-img-wrapper position-relative"
                                    style="overflow:hidden;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);max-width:180px;margin:auto;">
                                    <img src="<?= htmlspecialchars($photo['filename']) ?>"
                                        class="img-fluid w-100 gallery-clickable" alt="Photo utilisateur" loading="lazy"
                                        style="aspect-ratio:1/1;object-fit:cover;transition:transform 0.3s;max-height:180px;cursor:pointer;"
                                        onerror="this.src='assets/img/gallery/gallery-empty.png'"
                                        onclick="showLightbox(this.src)"
                                        onmouseover="this.style.transform='scale(1.08)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                    <a href="account.php?delete_photo=<?= $photo['id'] ?>"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                        style="z-index:3;" onclick="return confirm('Supprimer cette photo ?');"><i
                                            class="bi bi-trash"></i></a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php if ($offset + $maxPhotos < $totalPhotos): ?>
                            <div class="col-12 text-center">
                                <a href="account.php?page=<?= $page + 1 ?>" class="btn btn-outline-primary">Charger
                                    plus</a>
                            </div>
                            <?php endif; ?>
                            <?php else: ?>
                            <div class="col-12 text-center text-muted" style="font-size:48px;">
                                <i class="bi bi-images"></i>
                                <div>Pas de photos</div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($page > 1): ?>
                        <div class="col-12 text-center mb-2">
                            <a href="account.php" class="btn btn-outline-secondary">Retour</a>
                        </div>
                        <?php endif; ?>
                        <a href="settings.php" class="btn btn-outline-primary">Modifier mon profil</a>
                        <h5>Ajouter une photo à votre galerie</h5>
                        <form action="account.php" method="post" enctype="multipart/form-data" class="mb-4">
                            <div class="input-group">
                                <input type="file" name="photo" accept="image/*" class="form-control" required>
                                <button type="submit" class="btn btn-primary">Uploader</button>
                            </div>
                        </form>
                        <?php if (isset($uploadError)): ?>
                        <div class="alert alert-danger"><?= $uploadError ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="galleryLightbox" tabindex="-1" aria-labelledby="galleryLightboxLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img src="" class="img-fluid" id="lightboxImage" style="max-height:80vh;">
            </div>
        </div>
    </div>
</div>

<script>
function showLightbox(src) {
    let modal = document.getElementById('gallery-lightbox');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'gallery-lightbox';
        modal.style.position = 'fixed';
        modal.style.top = 0;
        modal.style.left = 0;
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.background = 'rgba(0,0,0,0.85)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = 9999;
        modal.innerHTML = '<img src="' + src +
            '" style="max-width:90vw;max-height:90vh;border-radius:10px;box-shadow:0 2px 16px #000;">' +
            '<span style="position:absolute;top:20px;right:40px;font-size:48px;color:#fff;cursor:pointer;z-index:10000;" onclick="document.getElementById(\'gallery-lightbox\').remove()">&times;</span>';
        document.body.appendChild(modal);
    } else {
        modal.querySelector('img').src = src;
        modal.style.display = 'flex';
    }
    modal.onclick = function(e) {
        if (e.target === modal) modal.remove();
    };
}
</script>

<?php
include 'partials/footer.php';
?>