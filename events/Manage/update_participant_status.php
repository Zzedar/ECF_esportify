<?php
require_once "../../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../../login/login.php');
    exit();
}

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participant_id'], $_POST['status'])) {
    $participantId = intval($_POST['participant_id']);
    $status = $_POST['status']; // 'validated' ou 'rejected'

    $stmt = $pdo->prepare("UPDATE event_participants SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $participantId]);

    header('Location: manage_events.php?updated=1');
    exit();
}
?>