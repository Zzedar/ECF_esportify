<?php
require 'check_access.php'; // Inclut la fonction de vérification
checkAccess('admin'); // Vérifie que l'utilisateur est un administrateur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esportify - Administrateur</title>
    <link rel="stylesheet" href="../../style.css">

<body>
<header>
    <nav>
        <ul>
            <li><a href="../../Index.php">Accueil</a></li>
            <li><a href="../../events/events.php">Événements</a></li>
            <li><a href="../../contacts/contacts.php">Contact</a></li>
            <li><a href="organizer_dashboard.php">organisateur</a></li>
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
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> !</h1>
    <p style="text-align: center;">Vous êtes connecté en tant qu'<strong>Administrateur</strong>.</p>

    <div class="card">
        <h3>Gestion des utilisateurs</h3>
        <p>Ajouter, modifier ou supprimer des utilisateurs de la plateforme.</p>
        <a href="../../Admin/user_management.php" class="button">Gérer les utilisateurs</a>
    </div>

    <div class="card">
        <h3>Modération des événements</h3>
        <p>Approuvez ou suspendez les événements soumis par les organisateurs.</p>
        <a href="../../Admin/moderate_events.php" class="button">Modérer les événements</a>
    </div>
</main>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>
