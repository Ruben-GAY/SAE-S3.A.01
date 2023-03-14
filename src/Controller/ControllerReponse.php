<?php

namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\DataObject\Reponse;
use App\Feurum\Model\DataObject\Section;
use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\HTTP\MESSAGE_TYPE;
use App\Feurum\Model\HTTP\MessageFlash;
use App\Feurum\Model\Repository\ReponseRepository;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\SectionRepository;
use App\Feurum\Model\Repository\UtilisateurRepository;
use DateTime;

class ControllerReponse {

    static function checkIsCollaborateur(string $idQuestion) {
        $utilisateur = IndexController::isLogged();
        if (UtilisateurRepository::hasRole($utilisateur->getId(), 3, $idQuestion)) {
            return true;
        }
        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour accéder à cette page");
        header("Location: frontController.php?controller=question&action=all");
        exit;
    }

    static function created() {
        // creer un objet reponse à partir des données du formulaire


        $utilisateur = IndexController::isLogged();

        $idQuestion = $_POST['idQuestion'] ?? null;

        if ($idQuestion == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous devez spécifier l'id de la question");
            return header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
        }


        $question = QuestionRepository::getQuestionById($idQuestion);

        if ($question == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $idQuestion n'existe pas");
            return header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
        }

        if (new DateTime($question->getDateDebutReponse()) > new DateTime()) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous ne pouvez pas répondre à cette question avant le " . $question->getDateDebutReponse());
            header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
            exit;
        }

        if (new DateTime($question->getDateFinReponse()) < new DateTime()) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous ne pouvez pas répondre à cette question après le " . $question->getDateFinReponse());
            header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
            exit;
        }



        self::checkIsCollaborateur($_POST['idQuestion']);




        $reponse = new Reponse(
            null,
            $_POST['titre'],
            $_POST['idQuestion'],
        );

        // sauvegarder la reponse dans la base de données
        ReponseRepository::sauvegarder($reponse);

        // creation sections de la reponse
        $sections = $_POST["sections"];
        $utilisateur = Session::getInstance()->getUtilisateur();
        UtilisateurRepository::ajouterRole($utilisateur->getId(), 2, $reponse->getId());

        foreach ($sections as $ordre => $value) {
            $section = new Section(null, $value["titre"], $value["contenu"], $ordre, $reponse->getId());
            SectionRepository::sauvegarder($section);
            // UtilisateurRepository::ajouterRole($value["idCoauteur"], 4, $section->getId());
        }

        // afficher la page de la question en changeant l'URL
        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "Votre réponse a bien été enregistrée");
        header('Location: frontController.php?controller=question&action=consulter&id=' . $reponse->getIdQuestion());
        exit;
    }

    static function create() {

        $idQuestion = $_GET['idQuestion'] ?? null;
        if ($idQuestion == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous devez spécifier l'id de la question");
            return header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
        }

        self::checkIsCollaborateur($idQuestion);


        $question = QuestionRepository::getQuestionById($idQuestion);
        if ($question == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La question avec l'id $idQuestion n'existe pas");
            return header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
        }

        if (new DateTime($question->getDateDebutReponse()) > new DateTime()) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous ne pouvez pas répondre à cette question avant le " . $question->getDateDebutReponse());
            header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
            exit;
        }

        if (new DateTime($question->getDateFinReponse()) < new DateTime()) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous ne pouvez pas répondre à cette question après le " . $question->getDateFinReponse());
            header('Location: frontController.php?controller=question&action=consulter&id=' . $idQuestion);
            exit;
        }

        $sections = SectionRepository::getAllSectionsByPropositionId($idQuestion);


        IndexController::afficheVue("/reponses/creerReponse.php", [
            "question" => $question,
            "sections" => $sections,
            "pageTitle" => "Répondre"
        ]);
    }

    static function consulter() {
        $id = $_GET['id'] ?? null;

        $reponse = ReponseRepository::getReponseById($id);
        if ($reponse == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La réponse avec l'id $id n'existe pas");
            return header('Location: frontController.php?controller=question&action=all');
        }

        $sections = SectionRepository::getAllSectionsByPropositionId($reponse->getId());
        $auteurs = UtilisateurRepository::getUsersByRoleAndText(2, $reponse->getId());
        $coauteur = UtilisateurRepository::getUsersByRoleAndText(4, $reponse->getId());

        $auteurs = array_merge($auteurs, $coauteur);

        IndexController::afficheVue("/reponses/consulterReponse.php", [
            'reponse' => $reponse,
            'sections' => $sections,
            'auteurs' => $auteurs,
            "pagetitle" => "Consulter une reponse",
        ]);
    }

    static function update() {
        $id = $_GET['id'] ?? null;

        if ($id == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous devez spécifier l'id de la réponse");
            return header('Location: frontController.php?controller=question&action=all');
        }

        $utilisateur = IndexController::isLogged();
        $reponse = ReponseRepository::getReponseById($id);

        if (!UtilisateurRepository::isParticipant($utilisateur->getId(), $reponse->getId())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour accéder à cette page");
            return header('Location: frontController.php?controller=question&action=all');
        }
        $sections = SectionRepository::getAllSectionsByPropositionId($reponse->getId());

        IndexController::afficheVue("/reponses/modifierReponse.php", [
            'reponse' => $reponse,
            'sections' => $sections,
            "pageTitle" => "Modifier une reponse",

        ]);
    }

    static function updated() {

        $utilisateur = IndexController::isLogged();
        $id = $_POST['id'] ?? null;

        $reponse = ReponseRepository::getReponseById($id);


        if ($reponse == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La réponse avec l'id $id n'existe pas");
            return header('Location: frontController.php?controller=question&action=all');
        }


        if (!UtilisateurRepository::isParticipant($utilisateur->getId(), $reponse->getId())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour accéder à cette page");
            return header('Location: frontController.php?controller=question&action=all');
        }

        if (isset($_POST['titre'])) $reponse->setTitre($_POST['titre']);
        if (isset($_POST['idQuestion'])) $reponse->setIdQuestion($_POST['idQuestion']);


        $sections = $_POST["sections"] ?? [];

        $coauteurs = $value["coauteurs"] ?? [];
        foreach ($coauteurs as $coauteur) {
            UtilisateurRepository::ajouterRole($coauteur, 4, $reponse->getId());
        }

        foreach ($sections as $ordre => $value) {
            $section = SectionRepository::getSectionById($value["id"]);
            if ($section == null) {
                $section = new Section(null, $value["titre"], $value["contenu"], $ordre, $reponse->getId());
                SectionRepository::sauvegarder($section);
            } else {
                $section->setTitre($value["titre"]);
                $section->setContenu($value["contenu"]);
                $section->setOrdre($ordre);
                SectionRepository::update($section);
            }
        }
        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "Votre réponse a bien été modifiée");
        header('Location: frontController.php?controller=question&action=consulter&id=' . $reponse->getIdQuestion());
        exit;
    }

    public static function delete() {
        $id = $_GET['id'] ?? null;

        if ($id == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous devez spécifier l'id de la réponse");
            return header('Location: frontController.php?controller=question&action=all');
        }

        $reponse = ReponseRepository::getReponseById($id);

        if ($reponse == null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "La réponse avec l'id $id n'existe pas");
            return header('Location: frontController.php?controller=question&action=all');
        }

        $utilisateur = IndexController::isLogged();

        if ($utilisateur->getRole() != "1" && !UtilisateurRepository::hasRole($utilisateur->getId(), 2, $reponse->getId())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour supprimer cette reponse");
            header('Location: frontController.php?controller=question&action=all');
            exit(0);
        }

        ReponseRepository::supprimer($id);
        header('Location: frontController.php?controller=question&action=all');
    }
}
