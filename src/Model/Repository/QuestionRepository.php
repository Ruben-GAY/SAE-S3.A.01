<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Question;

class QuestionRepository {

    // construit un objet Question à partir tableau
    static function construire(array $questionTab) : Question {
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
            'isPrivate' => (int)$question->getIsPrivate(),
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
            'isPrivate' => $question->getIsPrivate(),


        ]);
    }

    // supprime la question correspondant à l'id passer en paramètre
    static function supprimer(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM question WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
    }

}