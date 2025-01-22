<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['player' , 'organizer' , 'admin'])) {
    header('Location: ../login/login.php');
    exit();
}


// Traitement de la suppression des favoris
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $eventId = intval($_POST['event_id']);

    // Supprimer l'événement des favoris
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);

    // Redirige vers la page des favoris
    header('Location: player_favorites.php');
    exit();
}
?>