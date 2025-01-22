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
    <title>Contacts - Esportify</title>
    <link rel="stylesheet" href="contacts.css">
    <link rel="stylesheet" href="../events/event_detail.php">
    <link rel="stylesheet" href=../events/test_db.php>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../Index.php">Accueil</a></li>
            <li><a href="../events/events.php">Événements</a></li>
            <li><a href="../events/player_favorites.php">Mes Favoris</a></li>
            <li><a href="contacts.php">Contact</a></li>
            <li><a href="../login/login.php"> connexion / inscription</a> </li>
            <li><a href="../events/player_events.php">Events inscrit</a></li>

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
    <section id="contact">
        <h1>Contactez-nous</h1>
        <p>Vous avez une question ou besoin d'aide ? Envoyez-nous un message.</p>

        <form action="contact_process.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Envoyer</button>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>
</body>
</html>