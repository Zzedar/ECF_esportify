<?php
require_once "../../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['organizer', 'admin'])) {
    header('Location: ../../login/login.php');
    exit();
}


// Vérifie les données envoyées
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);

    // Supprime l'événement
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = :event_id");
    $stmt->execute(['event_id' => $eventId]);

    // Redirige après suppression
    header('Location: manage_events.php?deleted=1');
    exit();
}
?>