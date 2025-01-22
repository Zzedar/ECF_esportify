<?php
require_once "../../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../../login/login.php');
    exit();
}


// Vérifie les données envoyées par le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participant_id'], $_POST['status'])) {
    $participantId = intval($_POST['participant_id']);
    $status = $_POST['status']; // 'rejected', 'validated', etc.

    // Met à jour le statut de l'inscription
    $stmt = $pdo->prepare("
        UPDATE event_participants 
        SET status = :status 
        WHERE id = :participant_id
    ");
    $stmt->execute(['status' => $status, 'participant_id' => $participantId]);

    // Redirige vers la page de gestion des inscriptions
    header('Location: manage_registrations.php?event_id=' . $_GET['event_id'] . '&updated=1');
    exit();
}
?>