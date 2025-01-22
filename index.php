<?php
require_once "config.php";
$database = new Database();
$pdo = $database->getConnection();

// Construire la requête avec des filtres
$query = "SELECT * FROM events WHERE is_visible = 1";
$params = [];

// Si un organisateur est spécifié
if (!empty($_GET['organizer'])) {
    $query .= " AND organizer LIKE :organizer";
    $params['organizer'] = "%" . $_GET['organizer'] . "%";
}

// Si une date est spécifiée
if (!empty($_GET['date'])) {
    $query .= " AND DATE(date_start) = :date";
    $params['date'] = $_GET['date'];
}

// Trier par date de début (ordre croissant)
$query .= " ORDER BY date_start ASC";

// Préparer et exécuter la requête
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
session_start(); // Assure-toi que la session est démarrée
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Esportify</title>
    <link rel="stylesheet" href="Index.css">
    <link rel="stylesheet" href="events/event_detail.php">
    <link rel="stylesheet" href=events/test_db.php>
    <link rel="stylesheet" href="transition.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="events/events.php">Événements</a></li>
            <li><a href="events/player_favorites.php">Mes Favoris</a></li>
            <li><a href="contacts/contacts.php">Contact</a></li>
            <?php if (isset($_SESSION['username'])): ?>

                <li><a href="login/logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login/login.php">Connexion / Inscription</a></li>
            <?php endif; ?>

            <li><a href="events/player_events.php">Events inscrit</a></li>

            <?php if (isset($_SESSION['username'])): ?>
                <!-- Affiche un lien vers la page du rôle -->
                <?php if ($_SESSION['role'] === 'organizer'): ?>
                    <li><a href="login/role/organizer_dashboard.php">Organiser des événements</a></li>
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="login/role/admin_dashboard.php">Administration</a></li>
                <?php elseif ($_SESSION['role'] === 'player'): ?>

                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>


<main>
    <section id="home" class="hero">
        <h1>Bienvenue sur Esportify</h1>
        <p>La plateforme dédiée à l'e-sport et aux compétitions de jeux vidéo. <br>
            Avec ce site nous pouvons enfin nous inscrire a des tournois, de participer à des compétitons
            de suivre vos performances, de gagner des récompenses et bien sur de pouvoir discuter entre passionnés.</p>
    </section>

    <section id="gallery">
        <h2>Galerie</h2>
        <div class="slideshow-container">
            <div class="slide fade">
                <img src="img/img.jpg" alt="Image 1" style="width:100%;">
            </div>
            <div class="slide fade">
                <img src="img/img1.jpg" alt="Image 2" style="width:100%;">
            </div>
            <div class="slide fade">
                <img src="img/img2.jpg" alt="Image 3" style="width:100%;">
            </div>
            <div class="slide fade">
                <img src="img/img3.jpg" alt="Image 4" style="width:100%;"></img>
            </div>
            <div class="slide fade">
                <img src="img/img4.jpg" alt="Image 5" style="width:100%;"></img>
            </div>
        </div>

        <div class="dots-container">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
        </div>
    </section>

    <section id="events">
        <h2>Événements à venir</h2>
        <ul>
            <li>Tournoi Valorant - 25/02/2025</li>
            <li>Compétition FIFA - 30/01/2025</li>
            <li>League of Legends - 05/01/2025</li>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2025 Esportify. Tous droits réservés.</p>
</footer>
<script src="index.js"></script>
</body>
</html>