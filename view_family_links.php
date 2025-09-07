<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Voir les liens familiaux";
include 'partials/header.php';
include 'partials/navbar.php';
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
// Récupérer tous les membres
$members = $pdo->query('SELECT * FROM family_tree')->fetchAll(PDO::FETCH_ASSOC);
// Indexer par id
$byId = [];
foreach ($members as $m) {
    $byId[$m['id']] = $m;
}
// Fonction pour afficher l'arbre d'un membre
function afficherArbreTexte($id, $byId, $niveau = 0, &$affiches = []) {
    if (!isset($byId[$id]) || in_array($id, $affiches)) return;
    $affiches[] = $id;
    $prefix = str_repeat('— ', $niveau);
    $m = $byId[$id];
    echo $prefix . htmlspecialchars($m['firstname'] . ' ' . $m['lastname']);
    if ($m['birthdate']) echo ' (' . htmlspecialchars($m['birthdate']) . ')';
    echo "<br>";
    // Afficher les enfants
    foreach ($byId as $child) {
        if ($child['father_id'] == $id || $child['mother_id'] == $id) {
            afficherArbreTexte($child['id'], $byId, $niveau + 1, $affiches);
        }
    }
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Liens familiaux sous forme d'arbre</h2>
        <div class="row">
            <?php
            // Afficher l'arbre pour chaque ancêtre (personne sans parent)
            $affiches = [];
            foreach ($byId as $id => $m) {
                if (empty($m['father_id']) && empty($m['mother_id'])) {
                    echo '<div class="col-md-6 mb-4"><div class="p-3 bg-light rounded">';
                    afficherArbreTexte($id, $byId, 0, $affiches);
                    echo '</div></div>';
                }
            }
            ?>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
