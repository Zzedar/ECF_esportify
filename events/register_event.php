<?php
session_start();
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $userId = $_SESSION['user_id'];
    $eventId = intval($_POST['event_id']);

    // Vérifie si le joueur est déjà inscrit à cet événement
    $stmt = $pdo->prepare("SELECT id FROM event_participants WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);
    if ($stmt->fetch()) {
        echo "Vous êtes déjà inscrit à cet événement.";
        exit();
    }

    // Ajoute l'inscription à la table
    $stmt = $pdo->prepare("INSERT INTO event_participants (user_id, event_id, status) VALUES (:user_id, :event_id, 'pending')");
    $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);

    // Redirige après une inscription réussie
    header('Location: events.php?registered=1');
    exit();
}
?>