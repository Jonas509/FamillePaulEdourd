<?php
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            // Génère un token unique
            $token = bin2hex(random_bytes(32));
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['firstname'] . ' ' . $user['lastname'],
                'username' => $user['username'],
                'email' => $user['email'],
                'token' => $token,
                'role' => $user['role'] ?? 'user'
            ];
            // Cookie 'remember me' (30 jours)
            if ($remember) {
                setcookie('rememberme', $token, time() + 60*60*24*30, '/', '', false, true);
            }
            // Enregistre la connexion en base
            $stmt = $pdo->prepare('INSERT INTO user_logins (user_id, token, ip, user_agent, login_time) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([
                $user['id'],
                $token,
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            // Redirection selon le rôle
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $errors[] = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        $errors[] = "Erreur serveur.";
    }
}
$pageTitle = "Connexion";
include 'partials/header.php';
include 'partials/navbar.php';
?>
<main class="main">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-4 text-center">Connexion à votre compte</h3>
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <form action="#" method="post" autocomplete="off">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y"
                                    style="z-index:2;" onclick="togglePwd('password', this)">
                                    <i class="bi bi-eye" id="eye-login"></i>
                                </button>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="#">Mot de passe oublié ?</a>
                        </div>
                        <div class="text-center mt-2">
                            <span>Pas encore de compte ? <a href="register.php">Créer un compte</a></span>
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
    </script>
</main>
<?php
include 'partials/footer.php';
?>