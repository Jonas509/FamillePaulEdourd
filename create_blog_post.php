<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Créer un article";
include 'partials/header.php';
include 'partials/navbar.php';
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
$success = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = $_SESSION['user']['name'] ?? '';
    $author_img = $_SESSION['user']['photo'] ?? 'assets/img/blog/blog-author.jpg';
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'assets/img/blog/';
        $filename = uniqid('blog_') . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }
    if ($title && $category && $content) {
        $stmt = $pdo->prepare('INSERT INTO blog_posts (title, category, content, author, author_img, image, created_at, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), "pending")');
        $stmt->execute([$title, $category, $content, $author, $author_img, $imagePath]);
        $success = "Article soumis à validation !";
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Créer un nouvel article</h2>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-6">
                <label for="title" class="form-label">Titre *</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="col-md-6">
                <label for="category" class="form-label">Catégorie *</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="col-12">
                <label for="content" class="form-label">Contenu *</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="col-12">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Soumettre l'article</button>
            </div>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
