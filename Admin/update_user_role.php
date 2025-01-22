<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <p style="color: green;">Le rôle de l'utilisateur a été mis à jour avec succès.</p>
<?php endif; ?>

<?php
require_once "../config.php";
$database = new Database();
$pdo = $database->getConnection();

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit();
}

// Vérifie les données envoyées
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['role'])) {
    $userId = intval($_POST['user_id']);
    $newRole = $_POST['role'];

    // Empêche la modification de l'administrateur principal (sécurité)
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['role'] === 'admin') {
        echo "Vous ne pouvez pas modifier le rôle d'un administrateur.";
        exit();
    }

    // Met à jour le rôle de l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :user_id");
    $stmt->execute(['role' => $newRole, 'user_id' => $userId]);

    // Redirige vers la gestion des utilisateurs avec un message de confirmation
    header('Location: user_management.php?updated=1');
    exit();
}
?>