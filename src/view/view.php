<?php

use App\Feurum\Model\HTTP\MessageFlash;
use App\Feurum\Model\HTTP\Session;

$user = Session::getInstance()->getUtilisateur();
$logged = $user != null;

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
    <link rel="stylesheet" href="./styles/profile.css">
    <link rel="stylesheet" href="./styles/allUser.css">
    <link rel="stylesheet" href="./styles/allVote.css">
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
</head>

<body>
    <header>
        <nav id="navbar">
            <div id="logo-container">
                <img src="./img/feur.png" alt="logo">
            </div>
            <a href="frontController.php"> Questions </a>

            <a href="frontController.php?controller=vote&action=all"> Votes </a>
            <?php if ($logged && $user->getRole() == "1") : ?><a href="frontController.php?controller=utilisateur&action=allUsers"> Utilisateurs </a> <?php endif; ?>

            <?php if ($logged) : ?>
                <a id="connect" href="frontController.php?controller=utilisateur&action=profile"> <?= Session::getInstance()->getUtilisateur()->getPseudo(); ?> </a>
            <?php else : ?>
                <a id="connect" href="frontController.php?controller=utilisateur&action=login"> Se connecter </a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="flash-container">
        <?php foreach (MessageFlash::lireTousMessages() as $message) : ?>
            <div class="flash <?= $message['type'] ?>">
                <?= $message['message'] ?>
            </div>
        <?php endforeach; ?>
    </div>
    <main>
        <?php
        require_once __DIR__ . $viewPath;
        ?>
    </main>
</body>
<script src="./scripts/collaborateurs.js"></script>
<script src="./scripts/votants.js"></script>
<script src="./scripts/coauteurs.js"></script>
<script src="./scripts/sections.js"></script>

</html>