<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();

// Vérifie si l'utilisateur est un organisateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../login/login.php');
    exit();
}


// Traitement de la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);

    // Vérifie que l'événement appartient à l'organisateur et qu'il est dans l'état "pending"
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :event_id AND organizer = :organizer AND status = 'pending'");
    $stmt->execute(['event_id' => $eventId, 'organizer' => $_SESSION['username']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Vous n'avez pas la permission de démarrer cet événement ou l'événement n'est pas éligible.";
        exit();
    }

    // Met à jour le statut de l'événement à "ongoing"
    $stmt = $pdo->prepare("UPDATE events SET status = 'ongoing' WHERE id = :event_id");
    $stmt->execute(['event_id' => $eventId]);

    // Redirige vers la page de gestion des événements avec un message de succès
    header('Location: Manage/manage_events.php?event_started=1');
    exit();
}
?>