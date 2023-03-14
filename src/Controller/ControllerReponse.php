<?php
namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Reponse;
use App\Feurum\Model\DataObject\Section;
use App\Feurum\Model\Repository\ReponseRepository;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\SectionRepository;

class ControllerReponse {
    static function created() {
        // creer un objet reponse à partir des données du formulaire
        
        $reponse = new Reponse(
            null,
            $_POST['titre'],
            $_POST['idQuestion'],
        );

        // sauvegarder la reponse dans la base de données
        ReponseRepository::sauvegarder($reponse);

        // creation sections de la reponse
        $sections = $_POST["sections"];



        foreach ($sections as $ordre => $value) {
            $section = new Section(null, $value["titre"],$value["contenu"], $ordre, $reponse->getId());
            SectionRepository::sauvegarder($section);
        }

        // afficher la page de la question en changeant l'URL
        header('Location: frontController.php?controller=question&action=consulter&id=' . $reponse->getIdQuestion());
    }

    static function create() {
        $idQuestion = $_GET['idQuestion'] ?? null;
        if($idQuestion == null) return IndexController::error("aucune question n'a été spécifiée");
        $question = QuestionRepository::getQuestionById($idQuestion);
        if($question == null) return IndexController::error("la question n'existe pas");

        $sections = SectionRepository::getAllSectionsByPropositionId($idQuestion);

        IndexController::afficheVue("/reponses/creerReponse.php",[
            "question" => $question,
            "sections" => $sections,
            "pageTitle" => "Répondre"    
        ]);
        
    }

    static function consulter() {
        $id = $_GET['id'] ?? null;

        $reponse = ReponseRepository::getReponseById($id);
        if($reponse == null) return IndexController::error("La réponse avec l'id $id n'existe pas");

        $sections = SectionRepository::getAllSectionsByPropositionId($reponse->getId());

        IndexController::afficheVue("/reponses/consulterReponse.php",[
            'reponse' => $reponse,
            'sections' => $sections,
            "pagetitle" => "Consulter une reponse",
        ]);
    
    }

    static function update() {
        $id = $_GET['id'] ?? null;

        $reponse = ReponseRepository::getReponseById($id);
        $sections = SectionRepository::getAllSectionsByPropositionId($reponse->getId());

        IndexController::afficheVue("/reponses/modifierReponse.php",[
            'reponse' => $reponse,
            'sections' => $sections,
            "pageTitle" => "Modifier une reponse",

        ]);
    
    }

    static function updated() {
        $reponse = new Reponse(
            $_POST['id'],
            $_POST['titre'],
            $_POST['idQuestion'],
        );

        ReponseRepository::update($reponse);

        $sections = $_POST["sections"];

        foreach ($sections as $ordre => $value) {
            $section = SectionRepository::getSectionById($value["id"]);
            if($section == null) {
                $section = new Section(null, $value["titre"],$value["contenu"], $ordre, $reponse->getId());
                SectionRepository::sauvegarder($section);
            } else {
                $section->setTitre($value["titre"]);
                $section->setContenu($value["contenu"]);
                $section->setOrdre($ordre);
                SectionRepository::update($section);
            }
        }

        header('Location: frontController.php?controller=question&action=consulter&id=' . $reponse->getIdQuestion());
    }
}

