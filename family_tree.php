<?php
$pageTitle = "Arbre généalogique";
include 'partials/header.php';
include 'partials/navbar.php';
$pdo = new PDO('mysql:host=localhost;dbname=mefamily;charset=utf8', 'root', '');
// Récupérer tous les membres
$members = $pdo->query('SELECT * FROM family_tree')->fetchAll(PDO::FETCH_ASSOC);
// Organiser par parent (père)
$tree = [];
foreach ($members as $m) {
    $tree[$m['father_id']][] = $m;
}
function afficherArbre($parent_id, $tree) {
    if (!isset($tree[$parent_id])) return;
    echo '<ul>';
    foreach ($tree[$parent_id] as $m) {
        echo '<li><strong>' . htmlspecialchars($m['firstname'] . ' ' . $m['lastname']) . '</strong>';
        if ($m['birthdate']) echo ' <span style="color:#888;font-size:0.9em;">(' . htmlspecialchars($m['birthdate']) . ')</span>';
        afficherArbre($m['id'], $tree);
        echo '</li>';
    }
    echo '</ul>';
}
?>
<main class="main">
    <div class="container py-5">
        <h2 class="mb-4">Arbre généalogique de la famille</h2>
        <div class="tree">
            <?php afficherArbre(null, $tree); ?>
        </div>
    </div>
</main>
<style>
.tree ul {
  padding-top: 20px; position: relative;
  transition: all 0.5s;
  display: flex;
  justify-content: center;
}
.tree li {
  list-style-type: none;
  text-align: center;
  position: relative;
  padding: 20px 5px 0 5px;
}
.tree li::before, .tree li::after {
  content: '';
  position: absolute; top: 0; right: 50%;
  border-top: 1px solid #ccc; width: 50%; height: 20px;
}
.tree li::after {
  right: auto; left: 50%; border-left: 1px solid #ccc;
}
.tree li:only-child::before, .tree li:only-child::after {
  display: none;
}
.tree li:only-child { padding-top: 0; }
.tree li:first-child::before, .tree li:last-child::after {
  border: 0 none;
}
.tree li:last-child::before {
  border-right: 1px solid #ccc;
  border-radius: 0 5px 0 0;
}
.tree li:first-child::after {
  border-radius: 5px 0 0 0;
}
.tree .person {
  display: inline-block;
  border: 1px solid #ccc;
  padding: 8px 16px;
  border-radius: 8px;
  background: #f9f9f9;
  font-weight: bold;
}
</style>
<?php include 'partials/footer.php'; ?>