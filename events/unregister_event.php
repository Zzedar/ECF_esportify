<?php
session_start();

// Vérifie si l'utilisateur est connecté et a le rôle de joueur
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['player', 'organizer', 'admin'])) {
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

// Vérifie que la requête est bien envoyée en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $eventId = intval($_POST['event_id']);

    // Supprime l'inscription de l'utilisateur pour l'événement donné
    $stmt = $pdo->prepare("DELETE FROM event_participants WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->execute([
        'user_id' => $userId,
        'event_id' => $eventId
    ]);

    // Redirige vers la page des événements inscrits avec un message de confirmation
    header('Location: player_events.php?unregistered=1');
    exit();
}
?>