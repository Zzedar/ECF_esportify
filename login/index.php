<?php
session_start();
if (isset($_SESSION['username'])) {
    echo "<p>Bienvenue, " . htmlspecialchars($_SESSION['username']) . "!</p>";
    echo '<a href="logout.php">Se déconnecter</a>';
} else {
}
?>