<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit();
}

// Vérification des données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = intval($_POST['event_id']);
    $userId = $_SESSION['user_id'];
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($message)) {
        // Insérer le message dans la base de données
        $stmt = $pdo->prepare("INSERT INTO messages (event_id, user_id, message) VALUES (:event_id, :user_id, :message)");
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId, 'message' => $message]);
    }
}

// Redirection vers la page de l'événement
header('Location: event_detail.php?id=' . $eventId);
exit();
?>