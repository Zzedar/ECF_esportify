<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Vérifier si les champs sont remplis
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Envoi de l'email
        $to = 'support@esportify.com'; // Remplace par l'adresse email de destination
        $subject = "Nouveau message de contact de $name";
        $body = "Nom : $name\nEmail : $email\n\nMessage :\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            header('Location: thank_you.php');
            exit();
        } else {
            echo "<p>Une erreur s'est produite. Veuillez réessayer plus tard.</p>";
        }
    } else {
        echo "<p>Veuillez remplir tous les champs du formulaire.</p>";
    }
} else {
    echo "<p>Méthode non autorisée.</p>";
}
?>
