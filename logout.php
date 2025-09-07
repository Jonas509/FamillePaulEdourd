<?php
session_start();
// Détruit la session
$_SESSION = [];
session_destroy();
// Supprime le cookie 'rememberme' si présent
if (isset($_COOKIE['rememberme'])) {
    setcookie('rememberme', '', time() - 3600, '/');
}
// Redirige vers la page de connexion
header('Location: login.php');
exit;
