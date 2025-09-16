<?php
$pageTitle = "Blog";
require_once 'partials/auth.php';
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
include 'partials/db.php';
// Contrôle d'accès
$allowedPages = ['blog.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $allowedPages)) {
    header('Location: index.php');
    exit;
}

// Pagination dynamique
$postsPerPage = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $postsPerPage;
$totalPosts = $pdo->query("SELECT COUNT(*) FROM blog WHERE status = 'approved'")->fetchColumn();
$totalPages = ceil($totalPosts / $postsPerPage);
$posts = $pdo->query("SELECT id, title, category, content, author, author_img, image, created_at FROM blog WHERE status = 'approved' ORDER BY created_at DESC LIMIT $postsPerPage OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">
    <!-- Breadcrumb navigation -->
    <div class="page-title light-background position-relative">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Blog</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li class="current">Blog</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <!-- Titre de la page -->
    <section class="py-5" style="background: linear-gradient(120deg, #f8fafc 60%, #e3f0ff 100%);">
        <div class="container text-center py-3">
            <h1 class="display-5 fw-bold mb-2" style="color:#24325d;">Blog familial</h1>
            <p class="lead mb-0">Retrouvez les souvenirs, récits et actualités partagés par votre famille.</p>
        </div>
    </section>

    <!-- Section des articles du blog -->
    <section id="blog-posts" class="blog-posts section">
        <div class="container">
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-primary" onclick="document.getElementById('blog-modal').style.display='flex'">Ajouter un article</button>
            </div>
            <div id="blog-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;">
                <div style="background:#fff;padding:32px 24px;border-radius:12px;min-width:320px;max-width:90vw;box-shadow:0 2px 16px #000;position:relative;">
                    <form method="post" enctype="multipart/form-data">
                        <h5 class="mb-3">Créer un article</h5>
                        <div class="mb-2">
                            <label for="title" class="form-label">Titre *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-2">
                            <label for="category" class="form-label">Catégorie *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Choisir...</option>
                                <option value="Famille">Famille</option>
                                <option value="Sport">Sport</option>
                                <option value="Divertissement">Divertissement</option>
                                <option value="Voyage">Voyage</option>
                                <option value="Cuisine">Cuisine</option>
                                <option value="Événement">Événement</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="content" class="form-label">Contenu *</label>
                            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                        </div>
                        <div class="mb-2">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('blog-modal').style.display='none'">Annuler</button>
                            <button type="submit" class="btn btn-primary">Soumettre</button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('blog-modal').onclick = function(e) {
                    if (e.target === this) this.style.display = 'none';
                };
            });
            </script>
            <?php
            // Traitement ajout article
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['category'], $_POST['content'])) {
                $title = trim($_POST['title']);
                $category = trim($_POST['category']);
                $content = trim($_POST['content']);
                $author = $_SESSION['user']['name'] ?? 'Anonyme';
                $author_img = $_SESSION['user']['photo'] ?? 'assets/img/blog/blog-author.jpg';
                $imagePath = null;
                if (!empty($_FILES['image']['name'])) {
                    $uploadDir = 'assets/img/blog/';
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $maxSize = 2 * 1024 * 1024; // 2 Mo
                    $fileType = mime_content_type($_FILES['image']['tmp_name']);
                    $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $fileSize = $_FILES['image']['size'];
                    $isImage = getimagesize($_FILES['image']['tmp_name']);
                    if (!in_array($fileType, $allowedTypes) || !in_array($fileExt, $allowedExts) || !$isImage) {
                        echo '<div class="alert alert-danger mt-3">Format d\'image non autorisé.</div>';
                    } elseif ($fileSize > $maxSize) {
                        echo '<div class="alert alert-danger mt-3">Image trop volumineuse (max 2 Mo).</div>';
                    } else {
                        $filename = uniqid('blog_') . '_' . basename($_FILES['image']['name']);
                        $targetPath = $uploadDir . $filename;
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                            $imagePath = $targetPath;
                        }
                    }
                }
                if ($title && $category && $content) {
                    $stmt = $pdo->prepare('INSERT INTO blog (title, category, content, author, author_img, image, created_at, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), "pending")');
                    $stmt->execute([$title, $category, $content, $author, $author_img, $imagePath]);
                    echo '<div class="alert alert-success mt-3">Article soumis à validation !</div>';
                    echo '<script>setTimeout(function(){ location.reload(); }, 1200);</script>';
                } else {
                    echo '<div class="alert alert-danger mt-3">Tous les champs sont obligatoires.</div>';
                }
            }
            ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (count($posts)): ?>
                    <?php foreach ($posts as $p): ?>
                    <div class="col">
                        <div class="card border-0 shadow h-100">
                            <img src="<?= htmlspecialchars($p['image'] ?? 'assets/img/blog/blog-empty.jpg') ?>" alt="" class="card-img-top" style="object-fit:cover;height:220px;">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= htmlspecialchars($p['category'] ?? 'Blog') ?></span>
                                <h5 class="card-title mb-2"><a href="blog-details.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($p['title']) ?></a></h5>
                                <div class="d-flex align-items-center mt-3">
                                    <img src="<?= htmlspecialchars($p['author_img'] ?? 'assets/img/blog/blog-author.jpg') ?>" alt="" class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;">
                                    <div class="post-meta">
                                        <span class="fw-bold"><?= htmlspecialchars($p['author']) ?></span><br>
                                        <span class="text-muted" style="font-size:0.95em;">
                                            <?= date('d M Y', strtotime($p['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted">Aucun article pour le moment.</div>
                <?php endif; ?>
            </div>
        </div>
    </section><!-- /Blog Posts Section -->


    <!-- Pagination du blog dynamique -->
    <?php if ($totalPages > 1): ?>
    <section id="blog-pagination" class="blog-pagination section">
        <div class="container">
            <nav aria-label="Pagination blog">
                <ul class="pagination justify-content-center">
                    <li class="page-item<?= ($page <= 1 ? ' disabled' : '') ?>">
                        <a class="page-link" href="?page=<?= max(1, $page-1) ?>" tabindex="-1"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <?php
                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);
                    if ($start > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                        if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    for ($i = $start; $i <= $end; $i++) {
                        $active = $i == $page ? ' active' : '';
                        echo "<li class='page-item$active'><a class='page-link' href='?page=$i'>$i</a></li>";
                    }
                    if ($end < $totalPages) {
                        if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        echo "<li class='page-item'><a class='page-link' href='?page=$totalPages'>$totalPages</a></li>";
                    }
                    ?>
                    <li class="page-item<?= ($page >= $totalPages ? ' disabled' : '') ?>">
                        <a class="page-link" href="?page=<?= min($totalPages, $page+1) ?>"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
    <?php endif; ?>

</main>
<?php
include 'partials/footer.php';
?>