<?php
 // objet question dans $question
 // $reponses [
 //   "reponse" => objet reponse,
 //   "nbVote" => int
 // ]

?>

<body>
    <div class="page-container flex-col">
        <h3 class="page-title"> Votes </h3>

        <div class="questions-container voteQuestion">
            <div class="question-title"><?=$question->getTitre()?></div>
            <div class="infos">
                <div class="question-icon">
                    <img src="../../web/img/pplIco.svg" alt="">
                    <div>12 participants</div>
                </div>
            </div>
        </div>

        <h2> Propositions : </h2>
        <form method="post" action="frontController.php?controller=vote&action=voted">
            <div class="white-block">
                <?php foreach ($reponses as $reponse): ?>
                        <div class="questions-container voteQuestion">
                            <input type="hidden" name="reponseId" value="<?= $reponse['reponse']->getId()?>">
                            <div class="question-title"> <?= $reponse['reponse']->getTitre() ?></div>
                                <div class="infos">
                                    <div class="question-icon flex-row">
                                        <div>Auteurs et Co auteurs : </div>
                                        <div class='authors'> Geoffrey et Pierre </div>
                                    </div>
                                </div>
                                <div class="select-div">
                                    <select name="valeurVote" id="1">
                                        <option value="2">Très favorable</option>
                                        <option value="1">favorable </option>
                                        <option value="O">Neutre</option>
                                        <option value="-1">Défavorable</option>
                                        <option value="-2">Très défavorable</option>
                                    </select>
                                </div>
                        </div>
                <?php endforeach?>
                
            </div>
            <button class="grey-btn"> Voter </button>
        </form>
    </div>
</body>