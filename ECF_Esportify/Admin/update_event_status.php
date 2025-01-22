<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
}

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

// Vérifie les données envoyées
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'], $_POST['status'])) {
    $eventId = intval($_POST['event_id']);
    $newStatus = $_POST['status'];

    // Liste des statuts autorisés
    $allowedStatuses = ['ongoing', 'rejected', 'completed'];

    // Vérifie si le statut est valide
    if (!in_array($newStatus, $allowedStatuses)) {
        echo "Statut invalide.";
        exit();
    }

    // Met à jour le statut de l'événement
    $stmt = $pdo->prepare("UPDATE events SET status = :status WHERE id = :event_id");
    $stmt->execute(['status' => $newStatus, 'event_id' => $eventId]);

    // Redirige vers la modération avec un message de confirmation
    header('Location: moderate_events.php?updated=1');
    exit();
}
?>