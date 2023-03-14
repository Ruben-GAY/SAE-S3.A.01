<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Question;

class QuestionRepository {

    // construit un objet Question à partir tableau
    static function construire(array $questionTab): Question {
        return new Question(
            $questionTab['id'] ? $questionTab['id'] : null,
            $questionTab['titre'],
            $questionTab['description'],
            $questionTab['dateDebutVote'],
            $questionTab['dateFinVote'],
            $questionTab['dateDebutReponse'],
            $questionTab['dateFinReponse'],
            (bool)($questionTab['isPrivate']),
        );
    }
    // retourne toutes les questions
    static function getAllQuestions() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM question");
        $res = [];
        foreach ($pdoStatement as $question) {
            $res[] = static::construire($question);
        }
        return $res;
    }

    // retourne toutes les questions non privée 

    static function getAllQuestionsNotPrivate() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM question WHERE isPrivate = 0");
        $res = [];
        foreach ($pdoStatement as $question) {
            $res[] = static::construire($question);
        }
        return $res;
    }




    // retourne toutes les questions par ordre alphabétique
    static function getAllQuestionsByAlphab() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM question ORDER BY titre");
        $res = [];
        foreach ($pdoStatement as $question) {
            $res[] = static::construire($question);
        }
        return $res;
    }


    // retourne toutes les questions ou l'utilisateur est collaborateur ou créateur ou votant


    static function getQuestionIsParticipantOrPublic(string $idUser) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM question q WHERE q.id IN (SELECT idtext FROM possede_role WHERE iduser=:iduser) OR q.isPrivate = 0");
        $pdoStatement->execute([
            'iduser' => $idUser,
        ]);
        $res = [];
        foreach ($pdoStatement as $text) {
            $res[] = QuestionRepository::construire($text);
        }
        return $res;
    }

    // retourne les quesions par nombre de participant (collaborateur + créateur + votant)

    static function getAllQuestionsByNbParticipant() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM question ORDER BY (SELECT COUNT(DISTINCT iduser) FROM possede_role WHERE idtext = question.id)");
        $res = [];
        foreach ($pdoStatement as $question) {
            $res[] = static::construire($question);
        }
        return $res;
    }


    // retourne la question correspondant à l'id passer en paramètre
    static function getQuestionById(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM question WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
        $question = $pdoStatement->fetch();
        return $question ? static::construire($question) : null;
    }

    // recherche les questions correspondant au titre passer en paramètre
    static function searchQuestionByTitle(string $titre) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM question WHERE titre LIKE :titre");
        $pdoStatement->execute(['titre' => "%$titre%"]);
        $res = [];
        foreach ($pdoStatement as $question) {
            $res[] = static::construire($question);
        }
        return $res;
    }

    // sauvegarde la question passer en paramètre dans la base de données
    static function sauvegarder(Question $question) {
        $pdo = DatabaseConnection::getPdo();
        PropositionRepository::sauvegarder($question);
        $pdoStatement = $pdo->prepare("INSERT INTO question (id , titre ,description, dateDebutVote, dateFinVote, dateDebutReponse, dateFinReponse, isPrivate) VALUES (:id, :titre, :description, :dateDebutVote, :dateFinVote, :dateDebutReponse, :dateFinReponse, :isPrivate)");
        $pdoStatement->execute([
            'id' => $question->getId(),
            'titre' => $question->getTitre(),
            'description' => $question->getDescription(),
            'dateDebutVote' => $question->getDateDebutVote(),
            'dateFinVote' => $question->getDateFinVote(),
            'dateDebutReponse' => $question->getDateDebutReponse(),
            'dateFinReponse' => $question->getDateFinReponse(),
            'isPrivate' => (int)$question->isPrivate(),
        ]);
    }

    // met à jour la question passer en paramètre dans la base de données
    static function update(Question $question) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE question SET titre = :titre, description = :description, dateDebutVote = :dateDebutVote, dateFinVote = :dateFinVote, dateDebutReponse = :dateDebutReponse, dateFinReponse = :dateFinReponse, isPrivate = :isPrivate WHERE id = :id");
        $pdoStatement->execute([
            'id' => $question->getId(),
            'titre' => $question->getTitre(),
            'description' => $question->getDescription(),
            'dateDebutVote' => $question->getDateDebutVote(),
            'dateFinVote' => $question->getDateFinVote(),
            'dateDebutReponse' => $question->getDateDebutReponse(),
            'dateFinReponse' => $question->getDateFinReponse(),
            'isPrivate' => (int)$question->isPrivate(),
        ]);
    }

    static function getIdAuteur(Question $question) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT id FROM utilisateur u JOIN possede_role p ON u.id = p.iduser WHERE p.idtext=:idtext");
        $pdoStatement->execute(['idtext' => $question->getId()]);
        $resultat = $pdoStatement->fetch();
        try {
            return $resultat['id'];
        } catch (\Exception $e) {
        }
    }

    // change le attribut show pour supprimer
    static function updateAffiche(int $affiche, int $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE question SET affiche = :affiche WHERE id = :id");
        $pdoStatement->execute(['affiche' => $affiche, 'id' => $id]);
    }

    // supprime la question correspondant à l'id passer en paramètre
    static function supprimer(string $id) {

        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM resultat WHERE idQuestion=:id; DELETE FROM question WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
    }

    static function getNbParticipant(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT COUNT(DISTINCT iduser) FROM possede_role WHERE idtext = :id");
        $pdoStatement->execute(['id' => $id]);
        $nbParticipant = $pdoStatement->fetch();
        return $nbParticipant;
    }
}
