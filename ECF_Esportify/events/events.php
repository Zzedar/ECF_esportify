<?php
// Connexion à la base de données
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

// Construire la requête avec des filtres
$query = "SELECT * FROM events WHERE is_visible = 1";
$params = [];

// Si un organisateur est spécifié
if (!empty($_GET['organizer'])) {
    $query .= " AND organizer LIKE :organizer";
    $params['organizer'] = "%" . $_GET['organizer'] . "%";
}

// Si une date est spécifiée
if (!empty($_GET['date'])) {
    $query .= " AND DATE(date_start) = :date";
    $params['date'] = $_GET['date'];
}

// Trier par date de début (ordre croissant)
$query .= " ORDER BY date_start ASC";

// Préparer et exécuter la requête
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
session_start(); // Assure-toi que la session est démarrée
?>
<header>
    <nav>
        <ul>

            <?php if (isset($_SESSION['username'])): ?>
                <li>
                    <span>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)</span>
                </li>
                <li><a href="../login/logout.php">Déconnexion</a></li>
            <?php else: ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - Esportify</title>
    <link rel="stylesheet" href="styleE.css">
    <link rel="stylesheet" href="event_detail.php">
    <link rel="stylesheet" href=test_db.php>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../Index.php">Accueil</a></li>
            <li><a href="events.php">Événements</a></li>
            <li><a href="player_favorites.php">Mes Favoris</a></li>
            <li><a href="../contacts/contacts.php">Contact</a></li>
            <li><a href="../login/login.php"> connexion / inscription</a> </li>
            <li><a href="player_events.php">Events inscrit</a></li>

            <?php if (isset($_SESSION['username'])): ?>
                <!-- Affiche un lien vers la page du rôle -->
                <?php if ($_SESSION['role'] === 'organizer'): ?>
                    <li><a href="../login/role/organizer_dashboard.php">Organiser des événements</a></li>
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="../login/role/admin_dashboard.php">Administration</a></li>
                <?php elseif ($_SESSION['role'] === 'player'): ?>

                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <section id="events">
        <h1>Événements à venir</h1>

        <div class="filter">
            <form method="GET" action="events.php">
                <label for="organizer">Organisateur :</label>
                <input type="text" name="organizer" id="organizer">

                <label for="date">Date :</label>
                <input type="date" name="date" id="date">

                <button type="submit">Envoie</button>
            </form>
        </div>


        <?php foreach ($events as $event): ?>
            <li class="event-item">
                <h2><?= htmlspecialchars($event['title']); ?></h2>
                <p>Organisateur : <?= htmlspecialchars($event['organizer'] ?? 'Organisateur inconnu'); ?></p>
                <p>Début : <?= htmlspecialchars($event['date_start']); ?></p>
                <p>Places disponibles : <?= $event['max_players'] - $event['current_players']; ?></p>
                <a href="event_detail.php?id=<?= $event['id']; ?>">Voir plus</a>

                <!-- Bouton pour ajouter aux favoris -->
                <?php if (in_array($_SESSION['role'], ['player', 'organizer', 'admin'])): ?>
                    <form action="add_to_favorites.php" method="POST" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                        <button type="submit" class="button">Ajouter aux favoris</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <ul class="event-list">
            <?php if (empty($events)): ?>
                <p>Aucun événement ne correspond à vos critères.</p>
            <?php else: ?>
                <ul class="event-list">
                    <?php foreach ($events as $event): ?>
                        <li class="event-item">
                            <h2><?= htmlspecialchars($event['title']); ?></h2>
                            <p>Organisateur : <?= htmlspecialchars($event['organizer'] ?? 'Organisateur inconnu'); ?></p>
                            <p>Début : <?= htmlspecialchars($event['date_start']); ?></p>
                            <p>Places disponibles : <?= $event['max_players'] - $event['current_players']; ?></p>
                            <a href="event_detail.php?id=<?= $event['id']; ?>">Voir plus</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php foreach ($events as $event): ?>
                <li class="event-item">
                    <h2><?= htmlspecialchars($event['title']); ?></h2>
                    <p>Organisateur : <?= htmlspecialchars($event['organizer'] ?? 'Organisateur inconnu'); ?></p>
                    <p>Début : <?= htmlspecialchars($event['date_start']); ?></p>
                    <p>Places disponibles : <?= $event['max_players'] - $event['current_players']; ?></p>
                    <a href="event_detail.php?id=<?= $event['id']; ?>">Voir plus</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>
</body>
</html>


