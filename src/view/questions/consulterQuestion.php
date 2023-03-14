<body>
    <div class="page-container flex-col">
        <h3 class='page-title'> <?=$question->getTitre()?></h3>
        <div class="normal-text"> Organisateurs : H1T4CHI_69</div>
            <div class='info-question flex-col'>
                <div class="infoQ-title"> Description : </div>
                <div class="contenu-question">
                    <?=$question->getDescription()?>
                </div>

            </div>

            <?php foreach ($sections as $section): ?>
                <div class='info-question flex-col'>
                    <div class="infoQ-title"> <?=$section->getTitre()?>  </div>
                    <div class="contenu-question">
                        <?=$section->getContenu()?>
                    </div>
                </div>
            <?php endforeach?>

        <div class="reponse page-title"> Réponses (<?= count($reponses) ?>) </div>
    
            <?php foreach ($reponses as $reponse): ?>
                <a href= <?= "frontController.php?controller=reponse&action=consulter&id=" . $reponse->getId() ?> > <div class="reponse-container">
                    <div class="question-title"> <?= $reponse->getTitre() ?></div>
                        <div class="infos">
                            <div class="question-icon flex-row">
                                <div>Auteurs et Co auteurs : </div>
                                <div class='authors'> Geoffrey et Pierre </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach?>

        </div>

        <footer>
            <button class="grey-btn rep"> <a href="frontController.php?controller=reponse&action=create&idQuestion=<?= $question->getId() ?>" > Répondre </a></button>
            <button class="grey-btn rep"> <a href="frontController.php?controller=vote&action=vote&questionId=<?= $question->getId() ?>" > Voter </a></button>
            <button class="grey-btn rep"> <a href="frontController.php?controller=question&action=update&id=<?= $question->getId() ?>" > Modifier </a></button>
        </footer>
</body>