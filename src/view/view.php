<?php 

$username = 'Fan2Nadal';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/allQuestion.css">
    <link rel="stylesheet" href="./styles/creerQuestion.css">
    <link rel="stylesheet" href="./styles/inscription.css">
    <link rel="stylesheet" href="./styles/consulterQuestion.css">
    <link rel="stylesheet" href="./styles/voter.css">
    <title><?= $pageTitle ?></title>
</head>
<body>
<header>
    <nav id="navbar">
        <div id="logo-container">
            <img src="./img/feur.png" alt="logo">
        </div>
        <a href="frontController.php"> Questions </a>

        <a href="/"> Votes </a>
        <a href="/"> Résultats </a>
        <a id="connect" href="/"> <?= isset($username) ? "{$username}" : "Se connecter" ?> </a>
    </nav>
</header>
<main>
    <?php 
        require_once __DIR__ . $viewPath;
    ?>
</main>
</body>
</html>


