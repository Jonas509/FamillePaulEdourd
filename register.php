<?php
$pageTitle = "Inscription";
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
?>
<main class="main">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prénom
    if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]{2,30}$/', $_POST['firstname'] ?? '')) {
        $errors[] = "Prénom invalide.";
    }
    // Nom
    if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]{2,30}$/', $_POST['lastname'] ?? '')) {
        $errors[] = "Nom invalide.";
    }
    // Pseudo
    if (!preg_match('/^[A-Za-z0-9_-]{3,20}$/', $_POST['username'] ?? '')) {
        $errors[] = "Pseudo invalide.";
    }
    // Email
    if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    // Mot de passe
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $_POST['password'] ?? '')) {
        $errors[] = "Mot de passe invalide (min. 8 caractères, au moins une lettre et un chiffre).";
    }
    // Confirmation
    if ($_POST['password'] !== ($_POST['confirm_password'] ?? '')) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    // Affiche les erreurs ou traite l'inscription
    if (empty($errors)) {
        // Connexion à la base de données
        try {
            include 'partials/db.php';
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $errors[] = "Erreur de connexion à la base de données.";
        }
        if (empty($errors)) {
            // Vérifie si l'email ou le pseudo existe déjà
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
            $stmt->execute([$_POST['email'], $_POST['username']]);
            if ($stmt->fetch()) {
                $errors[] = "Email ou pseudo déjà utilisé.";
            } else {
                // Hash du mot de passe
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                // Insertion
                $stmt = $pdo->prepare('INSERT INTO users (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([
                    $_POST['firstname'],
                    $_POST['lastname'],
                    $_POST['username'],
                    $_POST['email'],
                    $hashedPassword
                ]);
                $success = true;
                $_POST = [];
            }
        }
    }
}
             
?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-4 text-center">Créer un compte famille</h3>
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                        <div class="alert alert-success">Inscription réussie ! Vous pouvez maintenant vous connecter.
                        </div>
                        <?php endif; ?>
                        <form action="#" method="post" autocomplete="off">
                            <div class="mb-3">
                                <label for="firstname" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required
                                    pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]{2,30}$"
                                    title="Lettres uniquement, 2 à 30 caractères"
                                    value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" required
                                    pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]{2,30}$"
                                    title="Lettres uniquement, 2 à 30 caractères"
                                    value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Pseudo</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                    pattern="^[A-Za-z0-9_-]{3,20}$"
                                    title="3 à 20 caractères, lettres, chiffres, tiret ou underscore"
                                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                    title="Format email valide" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required
                                        pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                                        title="Min. 8 caractères, au moins une lettre et un chiffre"
                                        oninput="updateStrengthBar()">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" style="z-index:2;" onclick="togglePwd('password', this)">
                                        <i class="bi bi-eye" id="eye-password"></i>
                                    </button>
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div id="pwd-strength-bar" class="progress-bar" role="progressbar"
                                        style="width: 0%; background-color: #dc3545;"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                                        title="Min. 8 caractères, au moins une lettre et un chiffre">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" style="z-index:2;" onclick="togglePwd('confirm_password', this)">
                                        <i class="bi bi-eye" id="eye-confirm"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                        </form>
                        <div class="text-center mt-3">
                            <span>Déjà un compte ? <a href="login.php">Se connecter</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function togglePwd(fieldId, btn) {
        var input = document.getElementById(fieldId);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function updateStrengthBar() {
        var pwd = document.getElementById('password').value;
        var bar = document.getElementById('pwd-strength-bar');
        var score = 0;
        if (pwd.length >= 8) score++;
        if (/[A-Z]/.test(pwd)) score++;
        if (/[a-z]/.test(pwd)) score++;
        if (/[0-9]/.test(pwd)) score++;
        if (/[^A-Za-z0-9]/.test(pwd)) score++;
        // Score: 0-1 faible, 2-3 moyen, 4 fort, 5 très fort
        let percent = [0, 25, 50, 75, 100][score];
        let color = '#dc3545'; // rouge
        if (score >= 4) color = '#28a745'; // vert
        else if (score >= 2) color = '#ffc107'; // jaune
        bar.style.width = percent + '%';
        bar.style.backgroundColor = color;
    }
    </script>
</main>
<?php
include 'partials/footer.php';
?>