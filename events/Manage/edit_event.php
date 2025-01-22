<?php
require_once "../../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['organizer', 'admin'])) {
    header('Location: ../../login/login.php');
    exit();
}


// Récupérer les données de l'événement à modifier
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $eventId = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND organizer = :organizer");
    $stmt->execute(['id' => $eventId, 'organizer' => $_SESSION['username']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Événement introuvable ou vous n'avez pas la permission de le modifier.";
        exit();
    }
}

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = intval($_POST['event_id']);
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $dateStart = $_POST['date_start'];
    $dateEnd = $_POST['date_end'];
    $maxPlayers = intval($_POST['max_players']);

    $stmt = $pdo->prepare("UPDATE events SET title = :title, description = :description, date_start = :date_start, date_end = :date_end, max_players = :max_players WHERE id = :id AND organizer = :organizer");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'date_start' => $dateStart,
        'date_end' => $dateEnd,
        'max_players' => $maxPlayers,
        'id' => $eventId,
        'organizer' => $_SESSION['username']
    ]);

    header('Location: manage_events.php?event_updated=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un événement</title>
    <link rel="stylesheet" href="../../Index.css">
</head>
<body>
<h1>Modifier l'événement</h1>
<form method="POST">
    <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($event['title']); ?>" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($event['description']); ?></textarea>

    <label for="date_start">Date de début :</label>
    <input type="datetime-local" id="date_start" name="date_start" value="<?= htmlspecialchars($event['date_start']); ?>" required>

    <label for="date_end">Date de fin :</label>
    <input type="datetime-local" id="date_end" name="date_end" value="<?= htmlspecialchars($event['date_end']); ?>" required>

    <label for="max_players">Nombre maximum de joueurs :</label>
    <input type="number" id="max_players" name="max_players" value="<?= $event['max_players']; ?>" required>

    <button type="submit">Enregistrer</button>
</form>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>

</body>
</html>