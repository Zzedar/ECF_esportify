<?php
$nombre1 = 10;
$nombre2 = 10;
if($_POST){
    var_dump($_POST);
    $nombre1 = $_POST['nombre1'];
    $nombre2 = $_POST['nombre2'];
    var_dump($nombre1 + $nombre2);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>calculette</title>
    <link rel="stylesheet" href="calculette">
</head>
<body>

<form action="#" method="post">
    <label for="nombre1">nombre1</label>
    <input type="number" name="nombre1" id="nombre1">
    <input type="number" name="nombre2" id="nombre2">
    <label for="nombre2">nombre2</label>
    <button type="submit">calcule!</button>
</form>

</body>
</html>
