<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['player', 'organizer', 'admin'])) {
    header('Location: ../login/login.php');
    exit();
}

$host = 'localhost';
$dbname = 'esportify';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupère les événements inscrits par le joueur
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT e.* FROM events e
                       JOIN event_participants ep ON e.id = ep.event_id
                       WHERE ep.user_id = :user_id
                       ORDER BY e.date_start ASC");
$stmt->execute(['user_id' => $userId]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php if (isset($_GET['unregistered']) && $_GET['unregistered'] == 1): ?>
    <p style="color: green;">Vous vous êtes désinscrit de l'événement avec succès.</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Événements Inscrits</title>
    <link rel="stylesheet" href="../Index.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../Index.php">Accueil</a></li>
            <li><a href="events.php">Événements</a></li>
            <li><a href="player_favorites.php">Favoris</a></li>
            <li><a href="../login/logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main>
    <h1>Mes Événements Inscrits</h1>
    <?php if (empty($events)): ?>
        <p>Vous n'êtes inscrit à aucun événement pour le moment.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($events as $event): ?>
                <li>
                    <h2><?= htmlspecialchars($event['title']); ?></h2>
                    <p>Début : <?= htmlspecialchars($event['date_start']); ?></p>
                    <p>Fin : <?= htmlspecialchars($event['date_end']); ?></p>
                    <a href="event_detail.php?id=<?= $event['id']; ?>">Voir les détails</a>

                    <!-- Bouton pour se désinscrire -->
                    <form action="unregister_event.php" method="POST" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                        <button type="submit" class="button danger">Se désinscrire</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</main>
</body>
</html>