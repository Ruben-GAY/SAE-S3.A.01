<?php

use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\UtilisateurRepository;
use App\Feurum\Model\DataObject\Vote;

?>

<body>
    <div class="page-container flex-col">
        <h3 class="page-title"> Votes </h3>

        <h2>Question :</h2>
        <div class="questions-container voteQuestion">
            <div class="question-title"> <?= $question->getTitre() ?></div>
            <div class="infos">
            </div>
        </div>

        <h2> Propositions : </h2>
        <form method="post" action="frontController.php?controller=vote&action=voted&id=<?= $question->getId() ?> ">
            <div class="white-block">
                <?php foreach ($reponses as $i => $reponse) : ?>
                    <div class="questions-container voteQuestion">
                        <input type="hidden" name="votes[<?= $i ?>][id]" value="<?= $reponse['reponse']->getId() ?>">
                        <div class="question-title"> <?= $reponse['reponse']->getTitre() ?></div>
                        <div class="infos">
                            <div class="ppl-info flex-col">
                                <div class="nb-vote flex-row">
                                    <div class='authors'>Nombres de votes : </div>
                                    <div class='authors'> <?= $reponse['nbVote'] ?> </div>
                                </div>
                                <div class="authors flex-row">
                                    <div> Auteurs et Co auteurs : </div>
                                    <div class='authors'> <?= array_reduce($reponse["auteurs"], function (?string $acc, mixed $auteur) {
                                                                return $acc . $auteur->getPseudo() . " ";
                                                            }) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="select-div">
                                <select name="votes[<?= $i ?>][valeur]">
                                    <option value="2">Très favorable</option>
                                    <option value="1">Favorable </option>
                                    <option value="0" selected="selected">Neutre</option>
                                    <option value="-1">Défavorable</option>/fr/iphone-14-pro/
                                    <option value="-2">Très défavorable</option>
                                </select>
                            </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                    </div>
                    <button class="grey-btn"> Voter </button>

                    <h2> Déjà votées : </h2>

                    <?php foreach ($votes as $i => $vote) : ?>
                    <div class="questions-container voteQuestion">
                        <div class="question-title"> <?= $vote['reponse']->getTitre() ?></div>
                        <div class="infos">
                            <div class="ppl-info flex-col">
                                <div class="authors flex-col">
                                    <div class="flex-row">
                                        <div> Auteurs et Co auteurs : </div>
                                        <div class=''> <?= array_reduce($vote["auteurs"], function (?string $acc, mixed $auteur) {
                                                                    return $acc . $auteur->getPseudo() . " ";
                                                                }) ?>
                                        </div>
                                    </div>
                                    <div class="flex-row"> Votre vote : <?= Vote::$nbToVote[$vote['vote']]  ?> </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    <?php endforeach ?>
        </form>
    </div>
</body>