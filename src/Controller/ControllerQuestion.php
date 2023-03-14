<?php
namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Section;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\ReponseRepository;
use App\Feurum\Model\Repository\SectionRepository;

class ControllerQuestion {
    static function created() {
        // creer un objet question à partir des données du formulaire
        if(isset($_POST)){
            $pv = false;
        }
        else{
            $pv= true;
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

        // creer un objet section à partir des données du formulaire et les ratache à la question créée
        $sections = $_POST["sections"];
        foreach ($sections as $ordre => $value) {
            $section = new Section(null, $value["titre"],$value["contenu"], $ordre, $question->getId());
            SectionRepository::sauvegarder($section);
        }


        // afficher la page de la question en changeant l'URL

        header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
    
    }

    static function create() {
        IndexController::afficheVue("/questions/creerQuestion.php", [
            'pageTitle' => 'Créer une question'
        ]);
    }

    static function allQuestion() {
        $questions = QuestionRepository::getAllQuestions();
        IndexController::afficheVue("/questions/allQuestion.php",[
            'questions' => $questions,
            "pageTitle" => "Liste des questions"
        ]);
    }

    static function consulter() {

        $id = $_GET['id'] ?? null;

        if(!$id) return IndexController::error("Aucune question n'a été sélectionnée");

        $question = QuestionRepository::getQuestionById($id);
        if($question == null) return IndexController::error("La question avec l'id $id n'existe pas");

        $sections = SectionRepository::getAllSectionsByPropositionId($question->getId());

        $reponses = ReponseRepository::getReponsesByQuestion($question);

        IndexController::afficheVue("/questions/consulterQuestion.php",[
            'question' => $question,
            'sections' => $sections,
            'reponses' => $reponses,
             "pageTitle" => $question->getTitre(),
        ]);
    }

    static function update() {

        $id = $_GET['id'] ?? null;

        if(!$id) return IndexController::error("Aucune question n'a été sélectionnée");

        $question = QuestionRepository::getQuestionById($id);
        if($question == null) return IndexController::error("La question avec l'id $id n'existe pas");

        $sections = SectionRepository::getAllSectionsByPropositionId($question->getId());

        $reponse = ReponseRepository::getReponsesByQuestion($question);

        IndexController::afficheVue("/questions/modifierQuestion.php",[
            'question' => $question,
            'sections' => $sections,
            'reponse' => $reponse,
            "pageTitle" => $question->getTitre(),
        ]);
    }

    static function updated() {
        $id = $_GET['id'] ?? null;

        if(!$id) return IndexController::error("Aucune question n'a été sélectionnée");

        

        $question = QuestionRepository::getQuestionById($id);
        if($question == null) return IndexController::error("La question avec l'id $id n'existe pas");

        $question->setTitre($_POST['titre']);
        $question->setDescription($_POST['description']);
        $question->setDateDebutVote(date_create($_POST['dateDebutVote'])->format('Y-m-d H:i:s'));
        $question->setDateFinVote(date_create($_POST['dateFinVote'])->format('Y-m-d H:i:s'));
        $question->setDateDebutReponse(date_create($_POST['dateDebutReponse'])->format('Y-m-d H:i:s'));
        $question->setDateFinReponse(date_create($_POST['dateFinReponse'])->format('Y-m-d H:i:s'));
        $question->setIsPrivate($_POST['isPrivate']);

        QuestionRepository::update($question);

        $sections = $_POST["sections"];
        foreach ($sections as $ordre => $value) {
            
            $section = SectionRepository::getSectionById($value["id"]);
            if($section == null) {
                $section = new Section(null, $value["titre"],$value["contenu"], $ordre, $question->getId());

                SectionRepository::sauvegarder($section);
            } else {
                $section->setTitre($value["titre"]);
                $section->setContenu($value["contenu"]);
                $section->setOrdre($ordre);
                
                SectionRepository::update($section);
            }
        }
        
        header('Location: frontController.php?controller=question&action=consulter&id=' . $question->getId());
    }
}

