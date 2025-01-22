<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['player', 'admin' , 'organizer'])) {
    header('Location: ../login/login.php');
    exit();
}

// Traitement de l'ajout aux favoris
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $eventId = intval($_POST['event_id']);

    // Vérifie si l'événement est déjà en favoris
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);

    if ($stmt->fetch()) {
        echo "Cet événement est déjà dans vos favoris.";
        exit();
    }

    // Ajoute l'événement aux favoris
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, event_id) VALUES (:user_id, :event_id)");
    $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);

    // Redirige vers la page précédente
    header('Location: events.php');
    exit();
}
?>