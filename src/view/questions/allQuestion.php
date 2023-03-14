<?php

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\UtilisateurRepository;

// $questions[] = ["question" => $question, "nbParticipants" => QuestionRepository::getNbParticipant($question->getId())];
?>


<body class="all-questions-body">

    <div class="title">
        Questions
    </div>
    <div class="top-infos flex-row">
        <div class="nb-questions">
            Il y a <?= count($questions) ?> questions
        </div>

        <?php
        if ($user != null && ($user->getRole() == "1" || UtilisateurRepository::hasRole($user->getId(), 2, $reponse->getId()))) {
        ?>

            <div class="link">
                <a href="frontController.php?action=create"> <button class="grey-btn"> Nouvelle question </button> </a>
            </div>

        <?php
        }
        ?>

    </div>

    <div class="div-container">


        <?php
        foreach ($questions as $question) :
        ?>
            <div class="questions-container">
                <a href="frontController.php?action=consulter&id=<?= $question["question"]->getId() ?>">
                    <div class="question-title"><?= $question["question"]->getTitre() ?></div>
                    <div class="infos">
                        <div class="question-icon">
                            <img src="./img/pplIco.svg" alt="">
                            <div><?= $question["nbParticipants"][0] ?> participants</div>
                        </div>
                        <div class="question-icon">
                            <img src="./img/calendar.svg" alt="">
                            <div>Vote en cours</div>
                        </div>
                        <div class="question-icon">
                            <img src="<?= $question["question"]->isPrivate() ? "./img/closedLock.svg" : "./img/lock.svg" ?> " alt="lock">
                            <div>
                                <?php
                                if ($question["question"]->isPrivate()) {
                                    echo "Privée";
                                } else {
                                    echo "Publique";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        <?php endforeach ?>

    </div>


</body>