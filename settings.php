<?php
require_once 'partials/auth.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Paramètres du compte";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
$user_id = $_SESSION['user']['id'];
// Récupérer les infos actuelles
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname'])) {
// Suppression de la photo de profil
if (isset($_POST['delete_profile_photo'])) {
    $oldPhoto = $user['profile_photo'] ?? '';
    if ($oldPhoto && file_exists($oldPhoto) && strpos($oldPhoto, 'assets/img/avatar-default.png') === false) {
        unlink($oldPhoto);
    }
    $stmt = $pdo->prepare('UPDATE users SET profile_photo = NULL WHERE id = ?');
    $stmt->execute([$user_id]);
    // Mettre à jour la session si besoin
    $_SESSION['user']['profile_photo'] = '';
    // Rafraîchir les données utilisateur
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<div class="alert alert-success mt-3">Photo de profil supprimée.</div>';
}
    $fields = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'birthdate' => $_POST['birthdate'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'country' => $_POST['country'],
        'gender' => $_POST['gender'],
        'privacy' => $_POST['privacy']
    ];
    // Photo de profil sécurisée
    if (!empty($_FILES['profile_photo']['name'])) {
        $uploadDir = 'assets/img/gallery/';
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['profile_photo']['tmp_name']);
        $fileSize = $_FILES['profile_photo']['size'];
        $isImage = getimagesize($_FILES['profile_photo']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            echo '<div class="alert alert-danger mt-3">Format de fichier non autorisé. (jpg, png, gif, webp)</div>';
        } elseif ($fileSize > $maxSize) {
            echo '<div class="alert alert-danger mt-3">La taille de la photo ne doit pas dépasser 2 Mo.</div>';
        } elseif ($isImage === false) {
            echo '<div class="alert alert-danger mt-3">Le fichier n\'est pas une image valide.</div>';
        } else {
            $filename = uniqid('profile_') . '_' . basename($_FILES['profile_photo']['name']);
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
                $fields['profile_photo'] = $targetPath;
            } else {
                echo '<div class="alert alert-danger mt-3">Erreur lors de l\'enregistrement du fichier.</div>';
            }
        }
    }
    // Mot de passe
    if (!empty($_POST['password'])) {
        $fields['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    // Générer la requête dynamique
    $set = [];
    foreach ($fields as $k => $v) {
        $set[] = "$k = :$k";
    }
    $sql = 'UPDATE users SET ' . implode(', ', $set) . ' WHERE id = :id';
    $fields['id'] = $user_id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($fields);
    echo '<div class="alert alert-success mt-3">Profil mis à jour !</div>';
    // Rafraîchir les données
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<main class="main">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-end mb-3">
                    <a href="add_family_member.php?parent_id=<?= urlencode($user_id) ?>" class="btn btn-success">
                        <i class="bi bi-person-plus"></i> Ajouter un membre à ma famille
                    </a>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-4">Paramètres du compte</h3>
                        <form action="#" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="firstname" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="firstname" name="firstname"
                                    value="<?= htmlspecialchars($user['firstname']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="lastname" name="lastname"
                                    value="<?= htmlspecialchars($user['lastname']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Pseudo</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?= htmlspecialchars($user['username']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate"
                                    value="<?= htmlspecialchars($user['birthdate'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Pays</label>
                                <input type="text" class="form-control" id="country" name="country"
                                    value="<?= htmlspecialchars($user['country'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Genre</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Sélectionner</option>
                                    <option value="Homme" <?= ($user['gender']==='Homme')?'selected':'' ?>>Homme
                                    </option>
                                    <option value="Femme" <?= ($user['gender']==='Femme')?'selected':'' ?>>Femme
                                    </option>
                                    <option value="Autre" <?= ($user['gender']==='Autre')?'selected':'' ?>>Autre
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Photo de profil</label>
                                <input type="file" class="form-control" id="profile_photo" name="profile_photo"
                                    accept="image/*">
                                <?php if (!empty($user['profile_photo'])): ?>
                                <img src="<?= htmlspecialchars($user['profile_photo']) ?>" alt="Photo de profil"
                                    class="rounded mt-2" style="max-width:100px;max-height:100px;">
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_profile_photo" value="1">
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Supprimer la photo de profil ?');">Supprimer</button>
                                </form>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" style="z-index:2;" onclick="togglePwd('password', this)">
                                        <i class="bi bi-eye" id="eye-settings"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="privacy" class="form-label">Confidentialité du profil</label>
                                <select class="form-select" id="privacy" name="privacy">
                                    <option value="public" <?= ($user['privacy']==='public')?'selected':'' ?>>Public
                                    </option>
                                    <option value="family" <?= ($user['privacy']==='family')?'selected':'' ?>>Famille
                                        uniquement</option>
                                    <option value="private" <?= ($user['privacy']==='private')?'selected':'' ?>>Privé
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            <a href="account.php" class="btn btn-outline-secondary ms-2">Annuler</a>
                        </form>
                        <div class="mb-4">
                            <a href="add_family_member.php?parent_id=<?= urlencode($user_id) ?>"
                                class="btn btn-success">
                                Ajouter un membre à ma famille
                            </a>
                        </div>
                        <hr>
                        <form action="#" method="post">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Se
                                déconnecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
include 'partials/footer.php';
include 'partials/family_links_form.php';
?>