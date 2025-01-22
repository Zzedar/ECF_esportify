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

// Traitement des données envoyées par le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = htmlspecialchars(trim($_POST['username']));
    $pass = hash('sha256', $_POST['password']);

    // Requête pour vérifier les identifiants
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $user, 'password' => $pass]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Création de la session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirection en fonction du rôle
        if ($user['role'] === 'admin') {
            header('Location: role/admin_dashboard.php');
        } elseif ($user['role'] === 'organizer') {
            header('Location: role/organizer_dashboard.php');
        } else {
            header('Location: ../events/player_favorites.php');
        }
        exit();
    } else {
        echo "<p>Identifiants incorrects.</p>";
    }
} else {
    echo "<p>Méthode non autorisée.</p>";
}
?>