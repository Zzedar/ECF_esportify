<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'esportify';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des messages
$eventId = intval($_GET['event_id']);
$stmt = $pdo->prepare("SELECT m.message, u.username, m.created_at FROM messages m
                       JOIN users u ON m.user_id = u.id
                       WHERE m.event_id = :event_id
                       ORDER BY m.created_at ASC");
$stmt->execute(['event_id' => $eventId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Génération du HTML
foreach ($messages as $msg): ?>
    <p><strong><?= htmlspecialchars($msg['username']); ?>:</strong> <?= htmlspecialchars($msg['message']); ?> <em>(<?= $msg['created_at']; ?>)</em></p>
<?php endforeach; ?>