<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupère tous les utilisateurs
$stmt = $pdo->prepare("SELECT id, username, role FROM users ORDER BY role, username ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
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
    <h1>Gestion des utilisateurs</h1>
    <table>
        <thead>
        <tr>
            <th>Nom d'utilisateur</th>
            <th>Rôle actuel</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
                <td>
                    <?php if ($user['role'] === 'organizer'): ?>
                        <!-- Bouton pour rétrograder en player -->
                        <form action="update_user_role.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="role" value="player" class="button danger">Rétrograder en joueur</button>
                        </form>
                    <?php elseif ($user['role'] === 'player'): ?>
                        <!-- Bouton pour promouvoir en organizer -->
                        <form action="update_user_role.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="role" value="organizer" class="button">Promouvoir en organisateur</button>
                        </form>
                    <?php else: ?>
                        <span>Aucun changement possible</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>