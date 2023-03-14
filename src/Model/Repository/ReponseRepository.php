<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Reponse;


class ReponseRepository {

    public static function construire(array $reponse) {
        return new Reponse(
            $reponse['id'],
            $reponse['titre'],
            $reponse['idQuestion']
        );
    }


    public static function getReponsesByQuestion(Question $question): array {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM reponse WHERE idQuestion = :idQuestion");
        $pdoStatement->execute([
            "idQuestion" => $question->getId()
        ]);

        $res = [];
        foreach ($pdoStatement as $reponse) {
            $res[] = self::construire($reponse);
        }
        return $res;
    }

    public static function getReponseById(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM reponse WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
        $reponse = $pdoStatement->fetch();
        return $reponse ? self::construire($reponse) : null;
    }

    public static function sauvegarder(Reponse $reponse) {
        PropositionRepository::sauvegarder($reponse);
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO reponse (id, titre, idQuestion) VALUES (:id, :titre, :idQuestion)");
        $pdoStatement->execute([
            'id' => $reponse->getId(),
            'titre' => $reponse->getTitre(),
            'idQuestion' => $reponse->getIdQuestion()
        ]);
    }
    // table vote :
    //  1	iduser  Primaire	int(11)			
    // 	2	idreponse  PrimaireIndex	int(11)
    // 	3	valeur	int(11)	

    public static function getReponsesNonVoteByQuestion(Question $question, $iduser) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM reponse WHERE idQuestion = :idQuestion AND id NOT IN (SELECT idreponse FROM vote WHERE iduser = :iduser)");
        $pdoStatement->execute([
            "idQuestion" => $question->getId(),
            "iduser" => $iduser
        ]);

        $res = [];
        foreach ($pdoStatement as $reponse) {
            $res[] = self::construire($reponse);
        }
        return $res;
    }

    public static function supprimer(Reponse $reponse) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM reponse WHERE id = :id");
        $pdoStatement->execute([
            'id' => $reponse->getId()
        ]);
    }

    public static function update(Reponse $reponse) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE reponse SET titre = :titre, idQuestion = :idQuestion WHERE id = :id");
        $pdoStatement->execute([
            'id' => $reponse->getId(),
            'titre' => $reponse->getTitre(),
            'idQuestion' => $reponse->getIdQuestion()
        ]);
    }
}
