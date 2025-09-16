<?php
require_once 'partials/auth.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Ajouter un membre à la famille";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
include 'partials/db.php';
$success = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $birthdate = $_POST['birthdate'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $relation_type = $_POST['relation_type'] ?? '';
    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = 'assets/img/gallery/';
        $filename = uniqid('member_') . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photoPath = $targetPath;
        }
    }
    $user_id = $_SESSION['user']['id'];
    $father_id = null;
    $mother_id = null;
    // Déterminer le lien de parenté
    if ($relation_type === 'father') {
        $gender = 'M';
        $father_id = null;
        $mother_id = null;
    } elseif ($relation_type === 'mother') {
        $gender = 'F';
        $father_id = null;
        $mother_id = null;
    } elseif ($relation_type === 'son' || $relation_type === 'daughter') {
        // L'utilisateur devient le père ou la mère selon son genre
        $stmt = $pdo->prepare('SELECT gender FROM family_tree WHERE id = ?');
        $stmt->execute([$user_id]);
        $user_gender = $stmt->fetchColumn();
        if ($user_gender === 'M') {
            $father_id = $user_id;
        } elseif ($user_gender === 'F') {
            $mother_id = $user_id;
        }
        $gender = ($relation_type === 'son') ? 'M' : 'F';
    } elseif ($relation_type === 'brother' || $relation_type === 'sister') {
        // Même parents que l'utilisateur
        $stmt = $pdo->prepare('SELECT father_id, mother_id FROM family_tree WHERE id = ?');
        $stmt->execute([$user_id]);
        $parent = $stmt->fetch(PDO::FETCH_ASSOC);
        $father_id = $parent['father_id'] ?? null;
        $mother_id = $parent['mother_id'] ?? null;
        $gender = ($relation_type === 'brother') ? 'M' : 'F';
    }
    if ($firstname && $lastname && $relation_type) {
        $stmt = $pdo->prepare('INSERT INTO family_tree (firstname, lastname, birthdate, gender, father_id, mother_id, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$firstname, $lastname, $birthdate, $gender, $father_id, $mother_id, $photoPath]);
        $success = "Membre ajouté avec succès !";
    } else {
        $error = "Tous les champs obligatoires doivent être remplis.";
    }
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Ajouter un membre à la famille</h2>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-4">
                <label for="firstname" class="form-label">Prénom *</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="col-md-4">
                <label for="lastname" class="form-label">Nom *</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
            </div>
            <div class="col-md-4">
                <label for="birthdate" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate">
            </div>
            <div class="col-md-4">
                <label for="relation_type" class="form-label">Lien de parenté *</label>
                <select class="form-select" id="relation_type" name="relation_type" required>
                    <option value="">Choisir le lien</option>
                    <option value="father">Père</option>
                    <option value="mother">Mère</option>
                    <option value="son">Fils</option>
                    <option value="daughter">Fille</option>
                    <option value="brother">Frère</option>
                    <option value="sister">Sœur</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Ajouter le membre</button>
            </div>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>