<?php if (isset($_GET['event_started']) && $_GET['event_started'] == 1): ?>
    <p style="color: green;">L'événement a été démarré avec succès.</p>
<?php endif; ?>

<?php if (isset($_GET['created']) && $_GET['created'] == 1): ?>
    <p style="color: green;">Événement créé avec succès !</p>
<?php endif; ?>

<?php
session_start();

// Vérifie si l'utilisateur est connecté et a le rôle d'organisateur
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['organizer', 'admin'])) {
    header('Location: ../../login/login.php');
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


// Récupère les événements de l'organisateur et l'admin
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT * 
    FROM events 
    WHERE organizer_id = :user_id
    ORDER BY date_start ASC
");
$stmt->execute(['user_id' => $userId]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes événements</title>
    <link rel="stylesheet" href="../../Index.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="../events.php">Événements</a></li>
            <li><a href="../../login/role/organizer_dashboard.php">Mon tableau de bord</a></li>
            <li><a href="../../login/logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php if (empty($events)): ?>
        <p>Vous n'avez créé aucun événement pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Titre</th>
                <th>Date de début</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($events as $event) {
                if (!empty($event['date_start'])) {
                    $startTime = new DateTime($event['date_start']);
                } else {
                    echo "Date de début manquante pour l'événement : " . htmlspecialchars($event['title']);
                    continue; // Passe à l'événement suivant
                }

                // Autres manipulations
            }
            ?>

            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['title']); ?></td>
                    <td><?= htmlspecialchars($event['date_start']); ?></td>
                    <td><?= htmlspecialchars($event['status']); ?></td>
                    <td>
                        <!-- Exemple d'actions -->
                        <a href="edit_event.php?id=<?= $event['id']; ?>" class="button">Modifier</a>
                        <form action="delete_event.php" method="POST" style="display:inline;">
                            <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                            <button type="submit" class="button danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php
// Calcul de la différence de temps
$currentTime = new DateTime();
$startTime = new DateTime($event['date_start']);
$interval = $currentTime->diff($startTime);

// Vérifie si l'événement peut être démarré
if ($event['status'] === 'pending' && $currentTime <= $startTime && $interval->i <= 30 && $interval->invert === 0): ?>
    <form action="../start_event.php" method="POST">
        <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
        <button type="submit" class="button start">Démarrer l'événement</button>
    </form>
<?php endif; ?>

<main>
<h1>Validation des inscriptions</h1>
<?php if (empty($participants)): ?>
    <p>Aucune inscription en attente.</p>
<?php else: ?>
    <ul>
        <?php foreach ($participants as $participant): ?>
            <li>
                <p><strong><?= htmlspecialchars($participant['username']); ?></strong> pour l'événement <em><?= htmlspecialchars($participant['title']); ?></em></p>
                <form action="update_participant_status.php" method="POST" style="display:inline;">
                    <input type="hidden" name="participant_id" value="<?= $participant['participant_id']; ?>">
                    <button type="submit" name="status" value="validated">Valider</button>
                    <button type="submit" name="status" value="rejected">Rejeter</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>

</body>
</html>