<?php
$url = parse_url(getenv("JAWSDB_URL")); // Récupérer les infos depuis la config Heroku

$host = $url["q2gen47hi68k1yrb.chr7pe7iynqr.eu-west-1.rds.amazonaws.com"];
$username = $url["r92r3g8xxrrs5dfd"];
$password = $url["fkadbr0r78s8j5iv"];
$database = substr($url["e3pqkuprmh1aj5nm"], 1);

// Connexion à la base MySQL
$conn = new mysqli($host, $username, $password, $database);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
echo "Connexion réussie !";
?>