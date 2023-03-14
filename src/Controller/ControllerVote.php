<?php

namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Vote;
use App\Feurum\Model\HTTP\MessageFlash;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\ReponseRepository;
use App\Feurum\Model\Repository\SectionRepository;
use App\Feurum\Model\Repository\VoteRepository;
use App\Feurum\Model\HTTP\MESSAGE_TYPE;
use App\Feurum\Model\Repository\UtilisateurRepository;
use DateTime;

class ControllerVote {


    public static function all() {
        $questions = QuestionRepository::getAllQuestions();
        IndexController::afficheVue('/votes/allVotes.php', [
            'questions' => $questions,
            "pageTitle" => "Toutes les questions"
        ]);
    }

    public static function vote() {

        $utilisateur = IndexController::isLogged();

        $questionId = isset($_GET['questionId']) ? $_GET['questionId'] : null;
        if (!$questionId) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucune question n'a été sélectionnée");
            header('Location: frontController.php?controller=question&action=all');
            exit;
        }

        $question = QuestionRepository::getQuestionById($questionId);
        if (!$question) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $questionId n'existe pas");
            header('Location: frontController.php?controller=question&action=all');
            exit;
        }

        if ($utilisateur->getRole() != "1" &&  ($question->isPrivate() && !UtilisateurRepository::hasRole($utilisateur->getId(), 4, $question->getId()))) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas accès à cette page");
            header('Location: frontController.php?controller=question&action=all');
            exit;
        }

        if (new DateTime() < new DateTime($question->getDateDebutVote())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "La date de début de vote de la question n'est pas encore arrivée");
            header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
            exit;
        }

        if (new DateTime() > new DateTime($question->getDateFinVote())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "La date de fin de vote de la question est dépassée");
            header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
            exit;
        }


        $tab = [];
        $reponsesNonVote = ReponseRepository::getReponsesNonVoteByQuestion($question, $utilisateur->getId());

        if (count($reponsesNonVote) != 0) {
            foreach ($reponsesNonVote as $reponse) {
                $auteur = UtilisateurRepository::getUsersByRoleAndText(2, $reponse->getId());
                $coauteur = UtilisateurRepository::getUsersByRoleAndText(4, $reponse->getId());
                $auteurs = array_merge($auteur, $coauteur);
                $tab[] = [
                    'reponse' => $reponse,
                    'nbVote' => VoteRepository::getNbVote($reponse),
                    'auteurs' => $auteurs,
                ];
            }
        }



        $votes = VoteRepository::getVoteByUserAndQuestionId($utilisateur->getId(), $question->getId());

        $voteTab = [];


        if ($votes != null) {
            foreach ($votes as $vote) {
                $rep = ReponseRepository::getReponseById($vote->getIdReponse());
                $auteur = UtilisateurRepository::getUsersByRoleAndText(2, $rep->getId());
                $coauteur = UtilisateurRepository::getUsersByRoleAndText(4, $rep->getId());
                $auteurs = array_merge($auteur, $coauteur);
                $voteTab[] = [
                    "vote" => $vote->getValeur(),
                    "reponse" => $rep,
                    "auteurs" => $auteurs,
                ];
            }
        }


        IndexController::afficheVue('/votes/voter.php', [
            'question' => $question,
            'reponses' => $tab,
            'votes' => $voteTab,
            "pageTitle" => "Voter"
        ]);
    }

    public static function voted() {

        $utilisateur = IndexController::isLogged();

        $questionId = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$questionId) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucune question n'a été sélectionnée");
            header('Location: frontController.php?controller=question&action=all');
            exit;
        }

        $question = QuestionRepository::getQuestionById($questionId);

        if (!$question) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $questionId n'existe pas");
            header("Location: frontController.php?controller=question&action=all");
            exit;
        }

        if (new DateTime() < new DateTime($question->getDateDebutVote())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "La date de début de vote de la question n'est pas encore arrivée");
            header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
            exit;
        }

        if (new DateTime() > new DateTime($question->getDateFinVote())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "La date de fin de vote de la question est dépassée");
            header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
            exit;
        }

        $votes = $_POST['votes'];


        if ($votes == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "Vous n'avez pas voté pour aucune réponse");
            header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
            exit;
        }

        foreach ($votes as $vote) {
            $reponse = ReponseRepository::getReponseById($vote['id']);

            if (!$reponse) {
                MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La réponse avec l'id " . $vote['reponseId'] . " n'existe pas");
                header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
                exit;
            }

            $vote = new Vote($utilisateur->getId(), $reponse->getId(), (int)$vote['valeur']);
            VoteRepository::sauvegarder($vote);
        }

        header("Location: frontController.php?controller=question&action=consulter&id=" . $reponse->getIdQuestion());
        exit;
    }
}
