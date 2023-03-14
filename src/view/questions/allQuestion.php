<?php

use App\Feurum\Model\DataObject\Question;
?>


<body class="all-questions-body">

    <div class="title">
            Questions
    </div>
    <div class="top-infos flex-row">
        <div class="nb-questions">
           Il y a <?= count($questions) ?> questions
        </div>
        <div class="link">
           <a href="frontController.php?action=create">  <button class="grey-btn">  Poser une question  </button> </a>
        </div>
    </div>

    <div class="div-container">

        <?php foreach($questions as $question): ?>
            <div class="questions-container">
                <a href="frontController.php?action=consulter&id=<?= $question->getId() ?>">
                <div class="question-title"><?= $question->getTitre() ?></div>
                <div class="infos">
                    <div class="question-icon">
                        <img src="../../web/img/pplIco.svg" alt="">
                        <div>12 participants</div>
                    </div>
                    <div class="question-icon">
                        <img src="../../web/img/calendar.svg" alt="">
                        <div>Vote en cours</div>
                    </div>
                    <div class="question-icon">
                        <img src="<?= $question->getIsPrivate() ? "../../web/img/closedLock.svg" : "../../web/img/lock.svg" ?> " alt="lock">
                        <div>
                            <?php
                            if($question->getIsPrivate()){
                                echo "Privée";
                            }
                            else{
                                echo "Publique";
                            }
                        ?></div>
                    </div>
                </div>
                </a>
             </div>
        <?php endforeach ?>

    
</body>
