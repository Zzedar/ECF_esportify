<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

if (!isset($_GET['id'])) {
    die("ID d'événement non spécifié.");
}

$id = intval($_GET['id']);
$query = "SELECT * FROM events WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Événement introuvable.");
}
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
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="event_detail.php">
    <link rel="stylesheet" href=test_db.php>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="events.php">Événements</a></li>
            <li><a href="player_favorites.php">Mes Favoris</a></li>
            <li><a href="../contacts/contacts.php">Contact</a></li>
            <li><a href="../login/login.php"> connexion / inscription</a> </li>

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
<body>



<main>
    <h1><?= htmlspecialchars($event['title']); ?></h1>
    <p><?= htmlspecialchars($event['description']); ?></p>
    <p>Organisateur : <?= htmlspecialchars($event['organizer'] ?? 'Organisateur inconnu'); ?></p>
    <p>Début : <?= htmlspecialchars($event['date_start']); ?></p>
    <p>Fin : <?= htmlspecialchars($event['date_end']); ?></p>
    <p>Participants : <?= $event['current_players']; ?> / <?= $event['max_players']; ?></p>
</main>

<?php if (in_array($_SESSION['role'], ['player', 'organizer', 'admin'])): ?>
    <form action="register_event.php" method="POST">
        <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
        <button type="submit" class="button">S’inscrire à l’événement</button>
    </form>
<?php endif; ?>

<section id="chat">
    <h2>Discussion pour cet événement</h2>




    <!-- Zone des messages -->
    <div id="chat-messages" style="background: #2c2c3e; padding: 10px; height: 300px; overflow-y: auto;">
        <?php
        // Récupérer les messages de la base de données pour cet événement
        $eventId = $_GET['id']; // ID de l'événement actuel
        $stmt = $pdo->prepare("SELECT m.message, u.username, m.created_at FROM messages m
                           JOIN users u ON m.user_id = u.id
                           WHERE m.event_id = :event_id
                           ORDER BY m.created_at ASC");
        $stmt->execute(['event_id' => $eventId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($messages as $msg): ?>
            <p><strong><?= htmlspecialchars($msg['username']); ?>:</strong> <?= htmlspecialchars($msg['message']); ?> <em>(<?= $msg['created_at']; ?>)</em></p>
        <?php endforeach; ?>
    </div>

    <!-- Formulaire d'envoi de message -->
    <form id="chat-form" method="POST" action="send_message.php" style="margin-top: 10px;">
        <input type="hidden" name="event_id" value="<?= $eventId; ?>">
        <textarea name="message" placeholder="Écrivez votre message..." required style="width: 100%; height: 50px;"></textarea>
        <button type="submit">Envoyer</button>
    </form>
</section>
</body>
<script>
    setInterval(() => {
        fetch('fetch_messages.php?event_id=<?= $eventId; ?>')
            .then(response => response.text())
            .then(data => {
                document.getElementById('chat-messages').innerHTML = data;
            });
    }, 3000); // Actualisation toutes les 3 secondes
</script>
</html>
