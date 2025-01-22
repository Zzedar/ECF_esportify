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
$password = ''; // Remplace si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
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