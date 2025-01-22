<?php
session_start();

// Vérifie si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['organizer', 'admin'])) {
    header('Location: ../../login/login.php');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];
    $max_players = intval($_POST['max_players']);
    $organizer_id = $_SESSION['user_id']; // L'utilisateur connecté

    // Insérer l'événement dans la base de données
    $stmt = $pdo->prepare("
        INSERT INTO events (title, description, date_start, date_end, max_players, organizer_id, status)
        VALUES (:title, :description, :date_start, :date_end, :max_players, :organizer_id, 'pending')
    ");

    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'date_start' => $date_start,
        'date_end' => $date_end,
        'max_players' => $max_players,
        'organizer_id' => $organizer_id
    ]);

    // Rediriger après création
    header('Location: manage_events.php?created=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un événement</title>
    <link rel="stylesheet" href="../../Index.css">
</head>
<body>
<main>
    <h1>Créer un nouvel événement</h1>
    <form action="create_event.php" method="POST">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>

        <label for="date_start">Date de début :</label>
        <input type="datetime-local" id="date_start" name="date_start" required>

        <label for="date_end">Date de fin :</label>
        <input type="datetime-local" id="date_end" name="date_end" required>

        <label for="max_players">Nombre maximum de joueurs :</label>
        <input type="number" id="max_players" name="max_players" min="1" required>

        <button type="submit">Créer</button>
    </form>
</main>
</body>
</html>