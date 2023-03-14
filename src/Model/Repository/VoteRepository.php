<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Reponse;
use App\Feurum\Model\DataObject\Vote;

class VoteRepository {

    // construit un objet Vote à partir tableau
    static function construire(array $voteTab): Vote {
        return new Vote(
            $voteTab['iduser'],
            $voteTab['idreponse'],
            $voteTab['valeur'],
        );
    }

    // retourne tous les votes
    static function getAllVotes() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM vote");
        $res = [];
        foreach ($pdoStatement as $vote) {
            $res[] = static::construire($vote);
        }
        return $res;
    }

    // retourne le vote correspondant à l'id passer en paramètre

    static function getVoteByUserAndQuestionId(int $iduser, int $idQuestion) {
        $pdo = DatabaseConnection::getPdo();


        $pdoStatement = $pdo->prepare("SELECT * FROM vote WHERE iduser = :iduser AND idreponse IN (SELECT id FROM reponse WHERE idquestion = :idquestion)");
        $pdoStatement->execute(['iduser' => $iduser, 'idquestion' => $idQuestion]);
        $votes = [];
        foreach ($pdoStatement as $vote) {
            $votes[] = static::construire($vote);
        }



        return $votes;
    }

    // sauvegarde le vote passer en paramètre dans la base de données


    static function getVoteByReponseId(int $idreponse) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM vote WHERE idreponse = :idreponse");
        $pdoStatement->execute(['idreponse' => $idreponse]);
        $res = [];
        foreach ($pdoStatement as $vote) {
            $res[] = static::construire($vote);
        }
        return $res;
    }


    static function sauvegarder(Vote $vote) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO vote (iduser, idreponse, valeur) VALUES (:iduser, :idreponse, :valeur)");
        $pdoStatement->execute([
            'iduser' => $vote->getIdUser(),
            'idreponse' => $vote->getIdReponse(),
            'valeur' => $vote->getValeur(),
        ]);
    }

    static function supprimer(Vote $vote) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM vote WHERE iduser = :iduser AND idreponse = :idreponse");
        $pdoStatement->execute([
            'iduser' => $vote->getIdUser(),
            'idreponse' => $vote->getIdReponse(),
        ]);
    }

    static function getNbVote(Reponse $reponse) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT COUNT(*) FROM vote WHERE idreponse = :idreponse");
        $pdoStatement->execute([
            'idreponse' => $reponse->getId(),
        ]);
        $nbVote = $pdoStatement->fetch();
        return $nbVote ? $nbVote[0] : null;
    }

    static function getNbVoteByQuestion(Question $question) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT COUNT(*) FROM vote WHERE idreponse IN (SELECT id FROM reponse WHERE idquestion = :idquestion)");
        $pdoStatement->execute([
            'idquestion' => $question->getId(),
        ]);
        $nbVote = $pdoStatement->fetch();
        return $nbVote ? $nbVote[0] : null;
    }

    static function getResultat(Question $question) {

        $pdo = DatabaseConnection::getPdo();


        $pdoStatement = $pdo->prepare("SELECT * FROM reponse r WHERE r.id = (SELECT idReponse FROM resultat r WHERE r.idQuestion = :idQuestion)");

        $pdoStatement->execute([
            'idQuestion' => $question->getId(),
        ]);

        $resultat = $pdoStatement->fetch();

        if ($resultat) {
            return ReponseRepository::construire($resultat);
        }

        $reponses = ReponseRepository::getReponsesByQuestion($question);
        $tab = [];
        foreach ($reponses as $reponse) {
            $t = [
                "reponse" => static::getVoteByReponseId($reponse->getId()),
                "score" => 0,
            ];
            $votes = static::getVoteByReponseId($reponse->getId());
            $t["score"] = array_reduce($votes, function ($carry, $vote) {
                return $carry + $vote->getValeur();
            }, 0);
            $tab[] = $t;
        }

        $gagnant = array_reduce($tab, function ($carry, $item) {
            if ($carry["score"] < $item["score"]) {
                return $item;
            }
            return $carry;
        }, ["reponse" => [-1], "score" => -1]);
        if(count($gagnant["reponse"]) < 1){
            return null;
        }
        $rep = $gagnant["reponse"][0]?->getIdReponse();

        $pdoStatement = $pdo->prepare("INSERT INTO resultat (idQuestion, idReponse, nbVote) VALUES (:idQuestion, :idReponse, :nbVote)");

        $pdoStatement->execute([
            'idQuestion' => $question->getId(),
            'idReponse' => $rep,
            'nbVote' => $gagnant["score"],
        ]);

        $pdoStatement = $pdo->prepare("SELECT * FROM reponse r WHERE r.id = (SELECT idReponse FROM resultat r WHERE r.idQuestion = :idQuestion)");

        $pdoStatement->execute([
            'idQuestion' => $question->getId(),
        ]);

        $resultat = $pdoStatement->fetch();

        return ReponseRepository::construire($resultat);
    }
}
