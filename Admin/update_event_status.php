<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
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