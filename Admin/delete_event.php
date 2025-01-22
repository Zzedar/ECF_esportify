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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);

    try {
        // Débute une transaction
        $pdo->beginTransaction();

        // Supprime les messages liés à l'événement
        $stmt = $pdo->prepare("DELETE FROM messages WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);

        // Supprime l'événement
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = :event_id");
        $stmt->execute(['event_id' => $eventId]);

        // Valide la transaction
        $pdo->commit();

        // Redirige avec un message de confirmation
        header('Location: moderate_events.php?deleted=1');
        exit();

    } catch (PDOException $e) {
        // Annule la transaction en cas d'erreur
        $pdo->rollBack();
        die("Erreur lors de la suppression de l'événement : " . $e->getMessage());
    }
}
?>