<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['player' , 'organizer' , 'admin'])) {
    header('Location: ../login/login.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'esportify';
$username = 'root'; // Remplace si nécessaire
$password = ''; // Remplace si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupère les événements favoris de l'utilisateur
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT e.* FROM events e
                       JOIN favorites f ON e.id = f.event_id
                       WHERE f.user_id = :user_id");
$stmt->execute(['user_id' => $userId]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="styleE.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../Index.php">Accueil</a></li>
            <li><a href="events.php">Événements</a></li>
            <li><a href="player_favorites.php">Mes Favoris</a></li>
            <li><a href="../login/logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Mes Événements Favoris</h1>
    <?php if (empty($favorites)): ?>
        <p>Vous n'avez aucun événement en favoris.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($favorites as $event): ?>
                <li class="event-item">
                    <h2><?= htmlspecialchars($event['title']); ?></h2>
                    <p>Organisateur : <?= htmlspecialchars($event['organizer']); ?></p>
                    <p>Début : <?= htmlspecialchars($event['date_start']); ?></p>
                    <p>Places disponibles : <?= $event['max_players'] - $event['current_players']; ?></p>
                    <a href="event_detail.php?id=<?= $event['id']; ?>">Voir plus</a>

                    <!-- Bouton pour retirer des favoris -->
                    <form action="remove_from_favorites.php" method="POST" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                        <button type="submit">Retirer des favoris</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
</body>
</html>