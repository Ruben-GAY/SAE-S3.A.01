<?php

use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\UtilisateurRepository;


?>

<body>
    <div class="page-container flex-col">
        <h3 class='page-title'> <?= $question->getTitre() ?></h3>
        <div class="normal-text"> Organisateurs : <?= $organisateur ?></div>
        <div class='info-question flex-col'>
            <div class="infoQ-title"> Description : </div>
            <div class="contenu-question">
                <?= $question->getDescription() ?>
            </div>

        </div>

        <?php foreach ($sections as $section) : ?>
            <div class='info-question flex-col'>
                <div class="infoQ-title"> <?= $section->getTitre() ?> </div>
                <div class="contenu-question">
                    <?= $section->getContenu() ?>
                </div>
            </div>
        <?php endforeach ?>

        <div class="reponse"> Réponses (<?= count($reponses) ?>) </div>
        <?php foreach ($reponses as $reponse) : ?>
            <a href=<?= "frontController.php?controller=reponse&action=consulter&id=" . $reponse["reponse"]->getId() ?>>
                <div class="reponse-container">
                    <div class="question-title"> <?= $reponse["reponse"]->getTitre() ?></div>
                    <div class="infos">
                        <div class="question-icon flex-row">
                            <div>Auteurs et Co auteurs : </div>
                            <div class='authors'> <?= array_reduce($reponse["auteurs"], function (?string $acc, mixed $auteur) {
                                                        return $acc . $auteur->getPseudo() . " ";
                                                    }) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach ?>

    </div>

    <footer>
        <?php if (Session::getInstance()->isConnected() && UtilisateurRepository::hasRole($user->getId(), 3, $question->getId())) : ?>
            <button class="grey-btn rep"> <a href="frontController.php?controller=reponse&action=create&idQuestion=<?= $question->getId() ?>"> Répondre </a></button>
        <?php endif ?>

        <?php if (Session::getInstance()->isConnected()) : ?>
            <?php if (UtilisateurRepository::hasRole(Session::getInstance()->getUtilisateur()->getId(), 5, $question->getId()) && strtotime($question->getDateDebutVote()) < time() && strtotime($question->getDateFinVote()) > time()) : ?>
                <button class="grey-btn rep"> <a href="frontController.php?controller=vote&action=vote&questionId=<?= $question->getId() ?>"> Voter </a></button>
            <?php endif ?>
        <?php endif ?>


        <?php
        $utilisateur = Session::getInstance()->getUtilisateur();
        if (Session::getInstance()->isConnected()) {
            if ($utilisateur->getRole() == "1" || UtilisateurRepository::hasRole($utilisateur->getId(), 2, $question->getId())) {
        ?>
                <button class="grey-btn rep"> <a href="frontController.php?controller=question&action=update&id=<?= $question->getId() ?>"> Modifier </a></button>
                <button class="grey-btn rep" id="delete-btn"> Supprimer</button>

        <?php
            }
        }
        ?>

        <?php if ($gagnant != null) : ?>
            <div class="gagnant"> Gagnant : <?= $gagnant->getTitre() ?> </div>
        <?php endif ?>
    </footer>
    <script>
        const deleteBtn = document.getElementById("delete-btn");
        deleteBtn.addEventListener("click", (e) => {
            e.preventDefault();
            if (confirm("Voulez vous vraiment supprimer cette question ?")) {
                window.location.href = "frontController.php?controller=question&action=delete&id=<?= $question->getId() ?>";
            }
        })
    </script>
</body>