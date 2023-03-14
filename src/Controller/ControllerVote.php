<?php

namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Vote;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\ReponseRepository;
use App\Feurum\Model\Repository\SectionRepository;
use App\Feurum\Model\Repository\VoteRepository;

class ControllerVote {

    
        public static function vote() {
            $questionId = isset($_GET['questionId']) ? $_GET['questionId'] : null;
            if(!$questionId) IndexController::error("questionId undefined");

            $question = QuestionRepository::getQuestionById($questionId);
            if(!$question) IndexController::error("questionId undefined");


            $tab = [];
            $reponses = ReponseRepository::getReponsesByQuestion($question);

            foreach ($reponses as $reponse) {
                $tab[] = [
                    'reponse' => $reponse,
                    'nbVote' => VoteRepository::getNbVote($reponse)
                ];
            }

            IndexController::afficheVue('/votes/voter.php', [
                'question' => $question,
                'reponses' => $tab,
                "pageTitle" => "Voter"
            ]);
        }

        public static function voted() {
            $reponseId = isset($_POST['reponseId']) ? $_POST['reponseId'] : null;
            if(!$reponseId) IndexController::error("Aucune réponse n'a été sélectionnée");

            $valeurVote = isset($_POST['valeurVote']) ? $_POST['valeurVote'] : null;
            if(!$valeurVote) IndexController::error("Aucune valeur de vote n'a été sélectionnée");


            $reponse = ReponseRepository::getReponseById($reponseId);
            if(!$reponse) IndexController::error("La réponse avec l'id $reponseId n'existe pas");
            
            $vote = new Vote(1 ,$reponse->getId(), (int)$valeurVote);

            VoteRepository::sauvegarder($vote);

            header("Location: frontController.php?" . $reponse->getIdQuestion());
        }
    
        
}

