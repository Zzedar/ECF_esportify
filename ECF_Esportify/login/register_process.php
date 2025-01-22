<?php
session_start();

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

// Vérification des données envoyées par le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));

    // Vérification des mots de passe
    if ($password !== $confirmPassword) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Hash du mot de passe
    $passwordHash = hash('sha256', $password);

    // Vérification si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        echo "Ce nom d'utilisateur est déjà utilisé.";
        exit();
    }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'player')");
    $stmt->execute([
        'username' => $username,
        'password' => $passwordHash
    ]);

    // Redirection après inscription réussie
    header('Location: login.php');
    exit();
}
?>