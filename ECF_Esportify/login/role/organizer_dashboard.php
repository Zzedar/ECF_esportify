<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['organizer', 'admin'])) {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esportify - Organisateur</title>
    <link rel="stylesheet" href="../../style.css">

<body>
<header>
    <nav>
        <ul>
            <li><a href="../../Index.php">Accueil</a></li>
            <li><a href="../../events/events.php">Événements</a></li>
            <li><a href="../../contacts/contacts.php">Contact</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li>
                    <span>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)</span>
                </li>
                <li><a href="../logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="../login.php" >Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <p>Vous êtes connecté en tant qu'administrateur. Vous avez accès à toutes les fonctionnalités de l'organisateur et à des privilèges supplémentaires.</p>
    <?php endif; ?>
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> !</h1>
    <p style="text-align: center;">Vous êtes connecté en tant qu'<strong>Organisateur</strong>.</p>


    <div class="card">
        <h3>Créer un événement</h3>
        <p>Organisez votre prochain événement e-sport.</p>
        <a href="../../events/Manage/create_event.php" class="button">Créer un événement</a>
    </div>

    <div class="card">
        <h3>Gérer vos événements</h3>
        <p>Modifiez ou supprimez vos événements existants.</p>
        <a href="../../events/Manage/manage_events.php" class="button">Gérer vos événements</a>
    </div>
</main>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>
