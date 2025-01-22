<?php
$db_url = getenv('JAWSDB_URL');
$url_parts = parse_url($db_url);

$db_host = 'q2gen47hi68k1yrb.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
$db_user = 'r92r3g8xxrrs5dfd';
$db_pass = 'fkadbr0r78s8j5iv';
$db_name = 'e3pqkuprmh1aj5nm';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>