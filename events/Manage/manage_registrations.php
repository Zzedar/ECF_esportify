<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <p style="color: green;">L'inscription a été mise à jour avec succès.</p>
<?php endif; ?>

<?php
require_once "../../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();

// Vérifie si l'utilisateur est un organisateur
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['organizer', 'admin'])) {
    header('Location: ../../login/login.php');
    exit();
}


// Récupère l'ID de l'événement
if (!isset($_GET['event_id'])) {
    echo "Aucun événement spécifié.";
    exit();
}
$eventId = intval($_GET['event_id']);

// Vérifie que l'utilisateur est bien l'organisateur ou l'admin de cet événement
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :event_id AND organizer = :organizer");
$stmt->execute(['event_id' => $eventId, 'organizer' , 'admin' => $_SESSION['username']]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Vous n'avez pas la permission de gérer cet événement.";
    exit();
}

// Récupère les inscriptions pour cet événement
$eventId = intval($_GET['event_id']);
$stmt = $pdo->prepare("
    SELECT ep.id AS participant_id, u.username, ep.status 
    FROM event_participants ep
    JOIN users u ON u.id = ep.user_id
    WHERE ep.event_id = :event_id
");
$stmt->execute(['event_id' => $eventId]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Gérer les inscriptions </title>
        <link rel="stylesheet" href="../../Index.css">
    </head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="../../login/role/organizer_dashboard.php">Tableau de bord</a></li>
                <li><a href="manage_events.php">Mes événements</a></li>
                <li><a href="../../login/logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

<main>
    <h1>Gérer les inscriptions pour l'événement : <?= htmlspecialchars($event['title']); ?></h1>
<?php if (empty($participants)): ?>
    <p>Aucune inscription pour cet événement.</p>
<?php else: ?>
    <table>
    <thead>
    <tr>
        <th>Nom d'utilisateur</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($participants as $participant): ?>
        <tr>
            <td><?= htmlspecialchars($participant['username']); ?></td>
            <td><?= htmlspecialchars($participant['status']); ?></td>
            <td>
                <?php if ($participant['status'] !== 'rejected'): ?>
                    <form action="update_registration.php" method="POST" style="display:inline;">
                        <input type="hidden" name="participant_id" value="<?= $participant['participant_id']; ?>">
                        <button type="submit" name="status" value="rejected" class="button danger">Rejeter</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php endif; ?>
</main>
</body>
    </html>