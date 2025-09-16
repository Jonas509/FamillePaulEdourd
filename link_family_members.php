<?php
require_once 'partials/auth.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Lier deux membres de la famille";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
include 'partials/db.php';
$success = null;
$error = null;
// Récupérer tous les membres
$members = $pdo->query('SELECT id, firstname, lastname, birthdate FROM family_tree ORDER BY lastname, firstname')->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $person1 = intval($_POST['person1_id']);
    $relation = $_POST['relation'] ?? '';
    $person2 = intval($_POST['person2_id']);
    if ($person1 && $person2 && $relation && $person1 != $person2) {
        if ($relation === 'father') {
            $stmt = $pdo->prepare('UPDATE family_tree SET father_id = ? WHERE id = ?');
            $stmt->execute([$person1, $person2]);
            $success = "Lien père ajouté.";
        } elseif ($relation === 'mother') {
            $stmt = $pdo->prepare('UPDATE family_tree SET mother_id = ? WHERE id = ?');
            $stmt->execute([$person1, $person2]);
            $success = "Lien mère ajouté.";
        } elseif ($relation === 'son' || $relation === 'daughter') {
            // Lier person2 comme parent de person1
            $stmt = $pdo->prepare('SELECT gender FROM family_tree WHERE id = ?');
            $stmt->execute([$person2]);
            $parent_gender = $stmt->fetchColumn();
            if ($parent_gender === 'M') {
                $stmt = $pdo->prepare('UPDATE family_tree SET father_id = ? WHERE id = ?');
                $stmt->execute([$person2, $person1]);
            } elseif ($parent_gender === 'F') {
                $stmt = $pdo->prepare('UPDATE family_tree SET mother_id = ? WHERE id = ?');
                $stmt->execute([$person2, $person1]);
            }
            $success = "Lien enfant ajouté.";
        }
    } else {
        $error = "Veuillez sélectionner deux membres différents et un lien.";
    }
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Lier deux membres de la famille</h2>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="post" class="row g-3">
            <div class="col-md-4">
                <label for="person1_id" class="form-label">Premier membre</label>
                <select class="form-select" id="person1_id" name="person1_id" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['firstname'].' '.$m['lastname']) ?><?php if($m['birthdate']) echo ' ('.$m['birthdate'].')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="relation" class="form-label">Lien</label>
                <select class="form-select" id="relation" name="relation" required>
                    <option value="">Choisir le lien</option>
                    <option value="father">est le père de</option>
                    <option value="mother">est la mère de</option>
                    <option value="son">est le fils de</option>
                    <option value="daughter">est la fille de</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="person2_id" class="form-label">Second membre</label>
                <select class="form-select" id="person2_id" name="person2_id" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['firstname'].' '.$m['lastname']) ?><?php if($m['birthdate']) echo ' ('.$m['birthdate'].')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Lier</button>
            </div>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
