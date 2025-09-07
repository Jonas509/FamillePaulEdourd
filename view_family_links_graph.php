<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$pageTitle = "Arbre familial graphique";
include 'partials/header.php';
include 'partials/navbar.php';
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
$members = $pdo->query('SELECT * FROM family_tree')->fetchAll(PDO::FETCH_ASSOC);
$byId = [];
foreach ($members as $m) {
    $byId[$m['id']] = $m;
}
function afficherArbreGraphique($id, $byId) {
    if (!isset($byId[$id])) return;
    $m = $byId[$id];
    echo '<li>';
    echo '<div class="person-node" onclick="showPersonInfo('.$m['id'].')">';
    if (!empty($m['photo'])) {
        echo '<img src="'.htmlspecialchars($m['photo']).'" alt="photo" class="person-photo"> ';
    }
    echo htmlspecialchars($m['firstname'].' '.$m['lastname']);
    if ($m['birthdate']) echo ' <span class="text-muted" style="font-size:0.9em;">('.htmlspecialchars($m['birthdate']).')</span>';
    echo '</div>';
    // Enfants
    $children = [];
    foreach ($byId as $child) {
        if ($child['father_id'] == $id || $child['mother_id'] == $id) {
            $children[] = $child['id'];
        }
    }
    if ($children) {
        echo '<ul>';
        foreach ($children as $cid) {
            afficherArbreGraphique($cid, $byId);
        }
        echo '</ul>';
    }
    echo '</li>';
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Arbre familial graphique</h2>
        <div class="tree-graph">
            <ul>
                <?php
                // Afficher l'arbre pour chaque ancêtre (personne sans parent)
                foreach ($byId as $id => $m) {
                    if (empty($m['father_id']) && empty($m['mother_id'])) {
                        afficherArbreGraphique($id, $byId);
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</main>
<style>
.tree-graph ul {
    padding-top: 20px;
    position: relative;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
.tree-graph li {
    list-style-type: none;
    text-align: center;
    position: relative;
    padding: 20px 5px 0 5px;
}
.tree-graph li::before, .tree-graph li::after {
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 1px solid #ccc; width: 50%; height: 20px;
}
.tree-graph li::after {
    right: auto; left: 50%; border-left: 1px solid #ccc;
}
.tree-graph li:only-child::before, .tree-graph li:only-child::after {
    display: none;
}
.tree-graph li:only-child { padding-top: 0; }
.tree-graph li:first-child::before, .tree-graph li:last-child::after {
    border: 0 none;
}
.tree-graph li:last-child::before {
    border-right: 1px solid #ccc;
    border-radius: 0 5px 0 0;
}
.tree-graph li:first-child::after {
    border-radius: 5px 0 0 0;
}
.person-node {
    display: inline-block;
    border: 1px solid #ccc;
    padding: 8px 16px;
    border-radius: 8px;
    background: #f9f9f9;
    font-weight: bold;
    min-width: 120px;
    margin-bottom: 8px;
}
.person-photo {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    margin-right: 6px;
    vertical-align: middle;
    border: 1px solid #bbb;
}
.person-node { cursor:pointer; }
.person-node:hover { background:#e6f0ff; border-color:#3399ff; }
</style>
<div id="person-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;">
    <div id="person-modal-content" style="background:#fff;padding:32px 24px;border-radius:12px;min-width:220px;max-width:90vw;box-shadow:0 2px 16px #000;position:relative;"></div>
</div>
<script>
function showPersonInfo(id) {
    const data = window.familyData[id];
    if (!data) return;
    let html = '<div style="font-size:1.2em;"><strong>' + data.firstname + ' ' + data.lastname + '</strong></div>';
    if (data.photo) html += '<img src="' + data.photo + '" style="width:80px;height:80px;object-fit:cover;border-radius:50%;margin:10px 0;display:block;">';
    if (data.birthdate) html += '<div><b>Date de naissance :</b> ' + data.birthdate + '</div>';
    if (data.gender) html += '<div><b>Genre :</b> ' + (data.gender==='M'?'Homme':(data.gender==='F'?'Femme':data.gender)) + '</div>';
    html += '<div><b>ID :</b> ' + data.id + '</div>';
    document.getElementById('person-modal-content').innerHTML = html;
    document.getElementById('person-modal').style.display = 'flex';
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('person-modal').onclick = function(e) {
        if (e.target === this) this.style.display = 'none';
    };
});
</script>
<script>
window.familyData = {};
<?php foreach ($byId as $id => $m): ?>
window.familyData[<?= $id ?>] = <?= json_encode($m) ?>;
<?php endforeach; ?>
</script>
<?php include 'partials/footer.php'; ?>
