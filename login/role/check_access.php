<?php
require_once "../../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();

// Fonction pour vérifier le rôle
function checkAccess($roleRequired) {
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== $roleRequired) {
        header('Location: ../login.php'); // Redirection si non autorisé
        exit();
    }
}
?>