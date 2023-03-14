<?php

namespace App\Feurum\Controller;

use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Reponse;
use App\Feurum\Model\DataObject\Section;
use App\Feurum\Model\HTTP\MESSAGE_TYPE;
use App\Feurum\Model\HTTP\MessageFlash;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\ReponseRepository;
use App\Feurum\Model\Repository\SectionRepository;
use App\Feurum\Model\Repository\UtilisateurRepository;
use App\Feurum\Model\Repository\VoteRepository;
use DateTime;

class ControllerQuestion {

    static function checkIsCollab() {
        $utilisateur = IndexController::isLogged();
        //var_dump($utilisateur->getRole());
        //header('Location: frontController.php?controller=question&action=all');

        if (!($utilisateur->getRole() == "1" || $utilisateur->getRole() == "2")) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour accéder à cette page");
            header('Location: frontController.php?controller=question&action=all');
            return;
        }
    }

    static function created() {
        // creer un objet question à partir des données du formulaire

        $utilisateur = IndexController::isLogged();

        self::checkIsCollab();

        isset($_POST['isPrivate']) ? $pv = $_POST['isPrivate'] : $pv = false;

        if (!(isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['dateDebutVote']) && isset($_POST['dateFinVote']) && isset($_POST['dateDebutReponse']) && isset($_POST['dateFinReponse']) && isset($_POST['sections']))) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Veuillez remplir tous les champs");
            header('Location: frontController.php?controller=question&action=create');
            exit;
        }

        $dateDebutVote = date_create($_POST['dateDebutVote']);
        $dateFinVote = date_create($_POST['dateFinVote']);

        $dateDebutReponse = date_create($_POST['dateDebutReponse']);
        $dateFinReponse = date_create($_POST['dateFinReponse']);


        if ($dateDebutVote > $dateFinVote || $dateDebutReponse > $dateFinReponse || $dateFinReponse > $dateDebutVote) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Les dates ne sont pas valides");
            header('Location: frontController.php?controller=question&action=create');
            return;
        }

        $question = new Question(
            null,
            $_POST['titre'],
            $_POST['description'],
            date_create($_POST['dateDebutVote'])->format('Y-m-d H:i:s'),
            date_create($_POST['dateFinVote'])->format('Y-m-d H:i:s'),
            date_create($_POST['dateDebutReponse'])->format('Y-m-d H:i:s'),
            date_create($_POST['dateFinReponse'])->format('Y-m-d H:i:s'),
            $pv
        );
        // sauvegarder la question dans la base de données
        QuestionRepository::sauvegarder($question);

        UtilisateurRepository::ajouterRole($utilisateur->getId(), 2, $question->getId());

        // creer un objet section à partir des données du formulaire et les ratache à la question créée
        $sections = $_POST["sections"];
        foreach ($sections as $ordre => $value) {
            $section = new Section(null, $value["titre"], $value["contenu"], $ordre, $question->getId());
            SectionRepository::sauvegarder($section);
        }

        $collaborateur = isset($_POST["collaborateurs"]) ? $_POST["collaborateurs"] : [];

        try {
            UtilisateurRepository::ajouterRole($utilisateur->getId(), 2, $question->getId());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        foreach ($collaborateur as $_ => $idCollaborateur) {
            try {
                UtilisateurRepository::ajouterRole($idCollaborateur, 3, $question->getId());
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $votants = isset($_POST["votants"]) ? $_POST["votants"] : [];
        foreach ($votants as $_ => $idVotant) {
            UtilisateurRepository::ajouterRole($idVotant, 5, $question->getId());
        }


        // afficher la page de la question en changeant l'URL
        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "La question a été créée avec succès");
        header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
        exit;
    }

    static function create() {

        IndexController::isLogged();

        self::checkIsCollab();

        IndexController::afficheVue("/questions/creerQuestion.php", [
            'pageTitle' => 'Créer une question'
        ]);
    }

    static function all() {

        $utilisateur = Session::getInstance()->getUtilisateur();

        if (!$utilisateur) $questionsRetrieved = QuestionRepository::getAllQuestionsNotPrivate();
        else if ($utilisateur->getRole() == "1") $questionsRetrieved = QuestionRepository::getAllQuestions();
        else $questionsRetrieved = QuestionRepository::getQuestionIsParticipantOrPublic($utilisateur->getId());

        $questions = [];

        foreach ($questionsRetrieved as $question) {
            $questions[] = ["question" => $question, "nbParticipants" => QuestionRepository::getNbParticipant($question->getId())];
        }

        IndexController::afficheVue("/questions/allQuestion.php", [
            'questions' => $questions,
            "pageTitle" => "Liste des questions"
        ]);
    }


    static function consulter() {


        $id = $_GET['id'] ?? null;

        if (!$id) return MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucune question n'a été sélectionnée");


        $utilisateur = Session::getInstance()->getUtilisateur();

        $question = QuestionRepository::getQuestionById($id);


        if ($question == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $id n'existe pas");
            header('Location: frontController.php?controller=question&action=all');
            exit;
            return;
        }



        if ($utilisateur?->getRole() != "1" &&  ($question->isPrivate() && !UtilisateurRepository::isParticipant($utilisateur->getId(), $question->getId()))) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas accès à cette question");
            header('Location: frontController.php?controller=question&action=all');
            exit;
            return;
        }

        $sections = SectionRepository::getAllSectionsByPropositionId($question->getId());

        $reponses = ReponseRepository::getReponsesByQuestion($question);

        $reponses = array_map(function ($reponse) {

            $auteur = UtilisateurRepository::getUsersByRoleAndText(2, $reponse->getId());
            $coauteur = UtilisateurRepository::getUsersByRoleAndText(4, $reponse->getId());
            $auteurs = array_merge($auteur, $coauteur);

            return ['reponse' => $reponse, 'auteurs' => $auteurs];
        }, $reponses);

        $gagnant = null;

        if (new DateTime($question->getDateFinVote()) < new DateTime()) {
            $gagnant = VoteRepository::getResultat($question);
        }

        IndexController::afficheVue("/questions/consulterQuestion.php", [
            'question' => $question,
            'sections' => $sections,
            'reponses' => $reponses,
            'organisateur' => UtilisateurRepository::getUsersByRoleAndText(2, $question->getId())[0]->getPseudo(),
            "pageTitle" => $question->getTitre(),
            "gagnant" => $gagnant,
        ]);
    }

    static function update() {

        $utilisateur = IndexController::isLogged();

        self::checkIsCollab();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucune question n'a été sélectionnée");
            header('Location: frontController.php?controller=question&action=all');
            return;
        }

        $question = QuestionRepository::getQuestionById($id);
        if ($question == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $id n'existe pas");
            header('Location: frontController.php?controller=question&action=all');
            return;
        }

        if (UtilisateurRepository::hasRole($utilisateur->getId(), 2, $question->getId()) == false) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour modifier cette question");
            header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
            return;
        }

        $sections = SectionRepository::getAllSectionsByPropositionId($question->getId());

        $reponse = ReponseRepository::getReponsesByQuestion($question);

        $collaborateurs = UtilisateurRepository::getUsersByRoleAndText(3, $question->getId());
        $votants = UtilisateurRepository::getUsersByRoleAndText(5, $question->getId());

        IndexController::afficheVue("/questions/modifierQuestion.php", [
            'question' => $question,
            'sections' => $sections,
            'reponse' => $reponse,
            'collaborateurs' => $collaborateurs,
            'votants' => $votants,
            "pageTitle" => $question->getTitre(),
        ]);
    }

    static function updated() {

        $utilisateur = IndexController::isLogged();


        $id = $_GET['id'] ?? null;

        if (!$id) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucune question n'a été sélectionnée");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        }

        $question = QuestionRepository::getQuestionById($id);
        if ($question == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $id n'existe pas");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        }

        if (!UtilisateurRepository::hasRole($utilisateur->getId(), 2, $question->getId())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour modifier cette question");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        };

        $dateDebutVote = date_create($_POST['dateDebutVote']);
        $dateFinVote = date_create($_POST['dateFinVote']);

        $dateDebutReponse = date_create($_POST['dateDebutReponse']);
        $dateFinReponse = date_create($_POST['dateFinReponse']);


        if ($dateDebutVote > $dateFinVote || $dateDebutReponse > $dateFinReponse || $dateFinReponse > $dateDebutVote) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Les dates ne sont pas valides");
            header('Location: frontController.php?controller=question&action=create');
            return;
        }

        isset($_POST['isPrivate']) ? $pv = $_POST['isPrivate'] : $pv = false;

        $question->setTitre($_POST['titre']);
        $question->setDescription($_POST['description']);
        $question->setDateDebutVote(date_create($_POST['dateDebutVote'])->format('Y-m-d H:i:s'));
        $question->setDateFinVote(date_create($_POST['dateFinVote'])->format('Y-m-d H:i:s'));
        $question->setDateDebutReponse(date_create($_POST['dateDebutReponse'])->format('Y-m-d H:i:s'));
        $question->setDateFinReponse(date_create($_POST['dateFinReponse'])->format('Y-m-d H:i:s'));
        $question->setPrivate($pv);

        QuestionRepository::update($question);

        $sections = $_POST["sections"];
        foreach ($sections as $ordre => $value) {

            $section = SectionRepository::getSectionById($value["id"]);
            if ($section == null) {
                $section = new Section(null, $value["titre"], $value["contenu"], $ordre, $question->getId());

                SectionRepository::sauvegarder($section);
            } else {
                $section->setTitre($value["titre"]);
                $section->setContenu($value["contenu"]);
                $section->setOrdre($ordre);

                SectionRepository::update($section);
            }
        }
        $collaborateursExistant = array_map(function ($collaborateur) {
            return $collaborateur->getId();
        }, UtilisateurRepository::getUsersByRoleAndText(3, $question->getId()));

        $votantsExistant = array_map(function ($votant) {
            return $votant->getId();
        }, UtilisateurRepository::getUsersByRoleAndText(5, $question->getId()));

        $collaborateur = isset($_POST["collaborateurs"]) ? $_POST["collaborateurs"] : [];
        $votants = isset($_POST["votants"]) ? $_POST["votants"] : [];

        foreach ($collaborateur as $_ => $idCollaborateur) {
            if (!in_array($idCollaborateur, $collaborateursExistant))
                UtilisateurRepository::ajouterRole($idCollaborateur, 3, $question->getId());
        }

        foreach ($collaborateursExistant as $_ => $idCollaborateur) {
            if (!in_array($idCollaborateur, $collaborateur))
                UtilisateurRepository::supprimerRole($idCollaborateur, 3, $question->getId());
        }

        $votants = $_POST["votants"];
        foreach ($votants as $_ => $idVotant) {
            if (!in_array($idVotant, $votantsExistant))
                UtilisateurRepository::ajouterRole($idVotant, 5, $question->getId());
        }

        foreach ($votantsExistant as $_ => $idVotant) {
            if (!in_array($idVotant, $votants))
                UtilisateurRepository::supprimerRole($idVotant, 5, $question->getId());
        }

        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "La question a été modifiée avec succès");
        header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
        exit;
    }

    static function delete() {

        $id = $_GET['id'] ?? null;

        if (!$id) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucune question n'a été sélectionnée");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        }

        $question = QuestionRepository::getQuestionById($id);

        if ($question == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $id n'existe pas");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        }

        $utilisateur = IndexController::isLogged();

        if ($utilisateur->getRole() != "1" && !UtilisateurRepository::hasRole($utilisateur->getId(), 2, $question->getId())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour supprimer cette question");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        }

        QuestionRepository::supprimer($question->getId());
        header('Location: frontController.php?controller=question&action=all');
    }
}
