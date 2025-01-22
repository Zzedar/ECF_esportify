<?php
$host = 'localhost';
$dbname = 'esportify';
$username = 'root'; // Remplace par ton utilisateur MySQL
$password = ''; // Remplace par ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$query = "SELECT * FROM events WHERE is_visible = 1 ORDER BY date_start ASC";
$stmt = $pdo->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>