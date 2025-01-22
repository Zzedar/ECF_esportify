<?php
session_start(); // Assure-toi que la session est démarrée
?>
<header>
    <nav>
        <ul>

            <?php if (isset($_SESSION['username'])): ?>
                <li>
                    <span>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)</span>
                </li>
                <li><a href="logout.php">Déconnexion</a></li>
            <?php else: ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription - Esportify</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../events/events.php">Événements</a></li>
            <li><a href="../contacts/contacts.php">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="auth">
        <h1>Bienvenue sur Esportify</h1>

        <div class="tabs">
            <button onclick="showForm('register')">Inscription</button>
            <button onclick="showForm('login')">connexion</button>
        </div>

        <!-- Formulaire de connexion -->
        <div id="login-form" class="form">
            <form action="login_process.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Se connecter</button>
            </form>
        </div>

        <!-- Formulaire d'inscription -->
        <div id="register-form" class="form hidden">
            <form action="register_process.php" method="POST">
                <label for="new_username">Nom d'utilisateur :</label>
                <input type="text" id="new_username" name="username" required>

                <label for="new_password">Mot de passe :</label>
                <input type="password" id="new_password" name="password" required>

                <label for="confirm_password">Confirmez le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </section>
</main>

<script>
    function showForm(form) {
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('register-form').classList.add('hidden');
        document.getElementById(form + '-form').classList.remove('hidden');
    }
</script>

</body>
<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>
</body>
</html>
