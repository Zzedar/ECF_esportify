<?php
$db_url = getenv('JAWSDB_URL');
$url_parts = parse_url($db_url);

$db_host = $url_parts['e3pqkuprmh1aj5nm'];
$db_user = $url_parts['r92r3g8xxrrs5dfd'];
$db_pass = $url_parts['fkadbr0r78s8j5iv'];
$db_name = ltrim($url_parts['path'], '/');

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>