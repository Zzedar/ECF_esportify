<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'esportify';
$username = 'root'; // Remplace si nécessaire
$password = ''; // Remplace si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupère tous les événements
$stmt = $pdo->prepare("SELECT id, title, date_start, organizer, status FROM events ORDER BY date_start DESC");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <p style="color: green;">Le statut de l'événement a été mis à jour avec succès.</p>
<?php endif; ?>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <p style="color: red;">L'événement a été supprimé avec succès.</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modération des événements</title>
    <link rel="stylesheet" href="../Index.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../login/role/admin_dashboard.php">Tableau de bord</a></li>
            <li><a href="user_management.php">Gestion des utilisateurs</a></li>
            <li><a href="moderate_events.php">Modération des événements</a></li>
            <li><a href="../login/logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Modération des événements</h1>
    <?php if (empty($events)): ?>
        <p>Aucun événement à modérer pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Titre</th>
                <th>Date de début</th>
                <th>Organisateur</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['title'] ?? 'Titre inconnu'); ?></td>
                    <td><?= htmlspecialchars($event['date_start'] ?? 'Date inconnue'); ?></td>
                    <td><?= htmlspecialchars($event['organizer'] ?? 'Organisateur inconnu'); ?></td>
                    <td><?= htmlspecialchars($event['status']); ?></td>
                    <td>
                        <!-- Bouton pour approuver l'événement -->
                        <?php if ($event['status'] === 'pending'): ?>
                            <form action="update_event_status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                                <button type="submit" name="status" value="ongoing" class="button success">Approuver</button>
                            </form>
                            <form action="update_event_status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                                <button type="submit" name="status" value="rejected" class="button danger">Rejeter</button>
                            </form>
                        <?php endif; ?>

                        <!-- Bouton pour supprimer l'événement -->
                        <form action="update_event_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                            <button type="submit" name="status" value="rejected" class="button danger">Rejeter</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>