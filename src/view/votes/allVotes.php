<?php
use App\Feurum\Model\Repository\VoteRepository;
?>

<body
        class="all-questions-body">
<div class="title">
            Votes
    </div>
    <div class="div-container">

        <?php foreach($questions as $question): ?>
            <div class="questions-container">
                <div class="question-title"><?= $question->getTitre() ?></div>
                <div class="infos">
                    <div class="question-icon">
                        <img src="./img/hourglass.svg" alt="">
                        <div><?php
                            $restant = round((strtotime($question->getDateFinVote()) - time()) / 86400);
                            if($restant > 0){
                                echo "$restant jours restants ";
                            }
                            else if($restant==0){
                                echo " 1 jours restant";
                            }
                            else{
                                echo "fin vote";
                            }?> </div>
                    </div>
                    <div class="question-icon">
                        <img src="./img/calendar.svg" alt="">
                        <div><?php echo VoteRepository::getNbVoteByQuestion($question) ?> votes </div>
                    </div>

                    <div class="vote-btn blue-round"> <a class='vote-link' href="frontController.php?controller=vote&action=vote&questionId=<?= $question->getId() ?>" > Voter </a> </div>
                </div>
             </div>
        <?php endforeach ?>

</body>