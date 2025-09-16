<?php
// Formulaire pour lier père, mère, enfants à son profil
include 'partials/db.php';
$user_id = $_SESSION['user']['id'];
// Récupérer le membre courant dans family_tree
$stmt = $pdo->prepare('SELECT * FROM family_tree WHERE id = ?');
$stmt->execute([$user_id]);
$self = $stmt->fetch(PDO::FETCH_ASSOC);
// Liste des membres pour sélection
$members = $pdo->query('SELECT id, firstname, lastname, birthdate FROM family_tree ORDER BY lastname, firstname')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="card mt-4">
    <div class="card-body">
        <h5 class="mb-3">Liens familiaux</h5>
        <form method="post" enctype="multipart/form-data">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="father_id" class="form-label">Père</label>
                    <select class="form-select" id="father_id" name="father_id">
                        <option value="">-- Aucun --</option>
                        <?php foreach ($members as $m): if ($m['id'] == $user_id) continue; ?>
                        <option value="<?= $m['id'] ?>"
                            <?= ($self && $self['father_id'] == $m['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['firstname'].' '.$m['lastname']) ?><?php if($m['birthdate']) echo ' ('.$m['birthdate'].')'; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="mother_id" class="form-label">Mère</label>
                    <select class="form-select" id="mother_id" name="mother_id">
                        <option value="">-- Aucune --</option>
                        <?php foreach ($members as $m): if ($m['id'] == $user_id) continue; ?>
                        <option value="<?= $m['id'] ?>"
                            <?= ($self && $self['mother_id'] == $m['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['firstname'].' '.$m['lastname']) ?><?php if($m['birthdate']) echo ' ('.$m['birthdate'].')'; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="add_child_id" class="form-label">Ajouter un enfant existant</label>
                    <select class="form-select" id="add_child_id" name="add_child_id">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($members as $m): if ($m['id'] == $user_id) continue; ?>
                        <option value="<?= $m['id'] ?>">
                            <?= htmlspecialchars($m['firstname'].' '.$m['lastname']) ?><?php if($m['birthdate']) echo ' ('.$m['birthdate'].')'; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row g-2 align-items-end mt-2">
                <div class="col-md-3">
                    <label for="new_child_firstname" class="form-label">Prénom nouvel enfant</label>
                    <input type="text" class="form-control" id="new_child_firstname" name="new_child_firstname">
                </div>
                <div class="col-md-3">
                    <label for="new_child_lastname" class="form-label">Nom nouvel enfant</label>
                    <input type="text" class="form-control" id="new_child_lastname" name="new_child_lastname">
                </div>
                <div class="col-md-3">
                    <label for="new_child_birthdate" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="new_child_birthdate" name="new_child_birthdate">
                </div>
                <div class="col-md-3">
                    <label for="new_child_photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="new_child_photo" name="new_child_photo"
                        accept="image/*">
                </div>
            </div>
            <div class="col-12 mt-2">
                <button type="submit" name="update_links" class="btn btn-primary">Mettre à jour les liens</button>
            </div>
        </form>
    </div>
</div>
<?php
function isDescendant($pdo, $descendant_id, $ancestor_id) {
    // Vérifie récursivement si $descendant_id descend de $ancestor_id
    $stmt = $pdo->prepare('SELECT father_id, mother_id FROM family_tree WHERE id = ?');
    $stmt->execute([$descendant_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) return false;
    if ($row['father_id'] == $ancestor_id || $row['mother_id'] == $ancestor_id) return true;
    $found = false;
    if ($row['father_id']) $found = $found || isDescendant($pdo, $row['father_id'], $ancestor_id);
    if ($row['mother_id']) $found = $found || isDescendant($pdo, $row['mother_id'], $ancestor_id);
    return $found;
}
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_links'])) {
    $error = null;
    // Empêcher d'être son propre parent
    if ($_POST['father_id'] && $_POST['father_id'] == $user_id) {
        $error = "Vous ne pouvez pas être votre propre père.";
    }
    if ($_POST['mother_id'] && $_POST['mother_id'] == $user_id) {
        $error = "Vous ne pouvez pas être votre propre mère.";
    }
    // Empêcher d'être son propre enfant
    if (!empty($_POST['add_child_id']) && $_POST['add_child_id'] == $user_id) {
        $error = "Vous ne pouvez pas être votre propre enfant.";
    }
    // Empêcher les boucles (ex: lier son ancêtre comme enfant)
    if (!$error && !empty($_POST['add_child_id']) && isDescendant($pdo, $user_id, $_POST['add_child_id'])) {
        $error = "Impossible : cela créerait une boucle dans l'arbre généalogique.";
    }
    // Empêcher les boucles pour père/mère
    if (!$error && $_POST['father_id'] && isDescendant($pdo, $_POST['father_id'], $user_id)) {
        $error = "Impossible : vous ne pouvez pas être l'ancêtre de votre propre père.";
    }
    if (!$error && $_POST['mother_id'] && isDescendant($pdo, $_POST['mother_id'], $user_id)) {
        $error = "Impossible : vous ne pouvez pas être l'ancêtre de votre propre mère.";
    }
    if ($error) {
        echo '<div class="alert alert-danger mt-2">'.$error.'</div>';
    } else {
        // Mettre à jour père/mère
        $stmt = $pdo->prepare('UPDATE family_tree SET father_id = ?, mother_id = ? WHERE id = ?');
        $stmt->execute([
            $_POST['father_id'] ?: null,
            $_POST['mother_id'] ?: null,
            $user_id
        ]);
        // Ajouter un enfant existant (mettre ce membre comme père ou mère de l'enfant)
        if (!empty($_POST['add_child_id'])) {
            $child_id = intval($_POST['add_child_id']);
            $stmt = $pdo->prepare('SELECT gender FROM family_tree WHERE id = ?');
            $stmt->execute([$user_id]);
            $gender = $stmt->fetchColumn();
            if ($gender === 'M') {
                $stmt = $pdo->prepare('UPDATE family_tree SET father_id = ? WHERE id = ?');
                $stmt->execute([$user_id, $child_id]);
            } elseif ($gender === 'F') {
                $stmt = $pdo->prepare('UPDATE family_tree SET mother_id = ? WHERE id = ?');
                $stmt->execute([$user_id, $child_id]);
            }
        }
        // Ajouter un nouvel enfant si prénom, nom et date de naissance sont fournis
        if (!empty($_POST['new_child_firstname']) && !empty($_POST['new_child_lastname']) && !empty($_POST['new_child_birthdate'])) {
            // Vérifier si un membre existe déjà avec même nom et date de naissance
            $stmt = $pdo->prepare('SELECT id FROM family_tree WHERE firstname = ? AND lastname = ? AND birthdate = ?');
            $stmt->execute([
                $_POST['new_child_firstname'],
                $_POST['new_child_lastname'],
                $_POST['new_child_birthdate']
            ]);
            $existing = $stmt->fetchColumn();
            if ($existing) {
                // Lier comme enfant
                $stmt = $pdo->prepare('SELECT gender FROM family_tree WHERE id = ?');
                $stmt->execute([$user_id]);
                $gender = $stmt->fetchColumn();
                if ($gender === 'M') {
                    $stmt = $pdo->prepare('UPDATE family_tree SET father_id = ? WHERE id = ?');
                    $stmt->execute([$user_id, $existing]);
                } elseif ($gender === 'F') {
                    $stmt = $pdo->prepare('UPDATE family_tree SET mother_id = ? WHERE id = ?');
                    $stmt->execute([$user_id, $existing]);
                }
            } else {
                // Ajouter le nouvel enfant
                $photoPath = null;
                if (!empty($_FILES['new_child_photo']['name'])) {
                    $uploadDir = 'assets/img/gallery/';
                    $filename = uniqid('child_') . '_' . basename($_FILES['new_child_photo']['name']);
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['new_child_photo']['tmp_name'], $targetPath)) {
                        $photoPath = $targetPath;
                    }
                }
                $stmt = $pdo->prepare('INSERT INTO family_tree (firstname, lastname, birthdate, gender, father_id, mother_id, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $_POST['new_child_firstname'],
                    $_POST['new_child_lastname'],
                    $_POST['new_child_birthdate'],
                    null,
                    ($self['gender'] === 'M' ? $user_id : null),
                    ($self['gender'] === 'F' ? $user_id : null),
                    $photoPath
                ]);
            }
        }
        echo '<div class="alert alert-success mt-2">Liens familiaux mis à jour ! <a href="settings.php">Actualiser</a></div>';
    }
}
?>