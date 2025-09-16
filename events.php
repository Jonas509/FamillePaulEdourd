<?php
$pageTitle = "Événements familiaux";
require_once 'partials/auth.php';
include 'partials/header.php';
if (!defined('NAVBAR_INCLUDED')) {
    define('NAVBAR_INCLUDED', true);
    include 'partials/navbar.php';
}
include 'partials/db.php';
// Récupérer les anniversaires à venir
// Anniversaires dans les 5 prochains jours
$today = new DateTime();
$currentYear = $today->format('Y');
$anniversaires = $pdo->query('SELECT id, firstname, lastname, birthdate, photo FROM family_tree WHERE birthdate IS NOT NULL')->fetchAll(PDO::FETCH_ASSOC);
$annivEvents = [];
foreach ($anniversaires as $m) {
    $b = DateTime::createFromFormat('Y-m-d', $m['birthdate']);
    if (!$b) continue;
    $annivDate = DateTime::createFromFormat('Y-m-d', $currentYear . '-' . $b->format('m-d'));
    if (!$annivDate) continue;
    // Si l'anniversaire est déjà passé cette année, on prend l'année suivante
    if ($annivDate < $today) {
        $annivDate->modify('+1 year');
    }
    $diff = (int)$today->diff($annivDate)->format('%a');
    if ($diff <= 30) {
        $age = $annivDate->format('Y') - $b->format('Y');
        $annivEvents[] = [
            'title' => 'Anniversaire de ' . $m['firstname'] . ' ' . $m['lastname'],
            'date' => $annivDate->format('d/m/Y'),
            'desc' => $m['firstname'] . ' aura ' . $age . ' ans !',
            'photo' => $m['photo'] ?? null
        ];
    }
}
// Récupérer les événements programmés (si table events existe)
$customEvents = [];
try {
    $customEvents = $pdo->query('SELECT id, user_id, title, description, event_date, photo FROM events ORDER BY event_date ASC')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table events non présente
}
?>

<main class="main">
    <!-- Breadcrumb navigation -->
    <div class="page-title light-background position-relative">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Événements</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li class="current">Événements</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <!-- Titre moderne -->
    <section class="py-5" style="background: linear-gradient(120deg, #f8fafc 60%, #e3f0ff 100%);">
        <div class="container text-center py-3">
            <h1 class="display-5 fw-bold mb-2" style="color:#24325d;">Événements familiaux</h1>
            <p class="lead mb-0">Retrouvez ici tous les moments importants à venir ou passés de votre famille.</p>
        </div>
    </section>

    <!-- Section événements moderne -->
    <section id="events" class="events section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">Anniversaires à venir</h4>
                        </div>
                        <div class="card-body">
                            <?php if (count($annivEvents)): ?>
                                <?php foreach ($annivEvents as $ev): ?>
                                <div class="mb-4 pb-3 border-bottom">
                                    <?php if ($ev['photo']): ?>
                                        <img src="<?= htmlspecialchars($ev['photo']) ?>" alt="Photo" class="rounded-circle mb-2" style="object-fit:cover;width:60px;height:60px;border:2px solid #3399ff;">
                                    <?php endif; ?>
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="fst-italic mb-1">Le <?= htmlspecialchars($ev['date']) ?></p>
                                    <p class="card-text mb-0 text-muted" style="font-size:0.95em;"><?= htmlspecialchars($ev['desc']) ?></p>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted">Aucun anniversaire à venir.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100">
                        <div class="card-header bg-success text-white text-center">
                            <h4 class="mb-0">Événements programmés</h4>
                        </div>
                        <div class="card-body">
                            <!-- Formulaire ajout événement -->
                            <button type="button" class="btn btn-success mb-3" onclick="document.getElementById('event-modal').style.display='flex'">Ajouter un événement</button>
                            <div id="event-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;">
                                <div style="background:#fff;padding:32px 24px;border-radius:12px;min-width:320px;max-width:90vw;box-shadow:0 2px 16px #000;position:relative;">
                                    <form method="post" enctype="multipart/form-data">
                                        <h5 class="mb-3">Ajouter un événement</h5>
                                        <div class="mb-2">
                                            <label for="event_title" class="form-label">Titre de l'événement</label>
                                            <input type="text" class="form-control" id="event_title" name="event_title" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="event_date" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="event_date" name="event_date" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="event_desc" class="form-label">Description</label>
                                            <textarea class="form-control" id="event_desc" name="event_desc" rows="2" required></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="event_photo" class="form-label">Photo de l'événement</label>
                                            <input type="file" class="form-control" id="event_photo" name="event_photo" accept="image/*">
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('event-modal').style.display='none'">Annuler</button>
                                            <button type="submit" class="btn btn-success">Ajouter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.getElementById('event-modal').onclick = function(e) {
                                    if (e.target === this) this.style.display = 'none';
                                };
                            });
                            </script>
                            <?php
                            // Traitement ajout événement
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_title'], $_POST['event_date'], $_POST['event_desc'])) {
                                $title = trim($_POST['event_title']);
                                $date = $_POST['event_date'];
                                $desc = trim($_POST['event_desc']);
                                $photoPath = null;
                                if (!empty($_FILES['event_photo']['name'])) {
                                    $uploadDir = 'assets/img/events/';
                                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                                    $filename = uniqid('event_') . '_' . basename($_FILES['event_photo']['name']);
                                    $targetPath = $uploadDir . $filename;
                                    if (move_uploaded_file($_FILES['event_photo']['tmp_name'], $targetPath)) {
                                        $photoPath = $targetPath;
                                    }
                                }
                                $today = date('Y-m-d');
                                if ($title && $date && $desc) {
                                    if ($date <= $today) {
                                        echo '<div class="alert alert-warning">La date de l\'événement doit être dans le futur.</div>';
                                    } else {
                                        try {
                                            $stmt = $pdo->prepare('INSERT INTO events (title, event_date, description, photo) VALUES (?, ?, ?, ?)');
                                            $stmt->execute([$title, $date, $desc, $photoPath]);
                                            echo '<div class="alert alert-success">Événement ajouté !</div>';
                                            echo '<script>setTimeout(function(){ location.reload(); }, 1200);</script>';
                                        } catch (Exception $e) {
                                            echo '<div class="alert alert-danger">Erreur lors de l\'ajout.</div>';
                                        }
                                    }
                                } else {
                                    echo '<div class="alert alert-warning">Tous les champs sont obligatoires.</div>';
                                }
                            }
                            ?>
                            <?php
                            $today = date('Y-m-d');
                            $futureEvents = array_filter($customEvents, function($ev) use ($today) {
                                return $ev['event_date'] > $today;
                            });
                            ?>
                            <?php if (count($futureEvents)): ?>
                                <?php foreach ($futureEvents as $ev): ?>
                                <div class="mb-4 pb-3 border-bottom">
                                    <?php if (!empty($ev['photo'])): ?>
                                        <img src="<?= htmlspecialchars($ev['photo']) ?>" alt="Photo événement" class="rounded mb-2" style="object-fit:cover;width:80px;height:80px;border:2px solid #28a745;">
                                    <?php endif; ?>
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="fst-italic mb-1">Le <?= date('d/m/Y', strtotime($ev['event_date'])) ?></p>
                                    <p class="card-text mb-0 text-muted" style="font-size:0.95em;\"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted">Aucun événement programmé.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Events Section -->

</main>

<?php include 'partials/footer.php'; ?>