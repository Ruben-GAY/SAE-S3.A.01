<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Proposition;

class PropositionRepository {

    // sauvegarder un proposition dans la BD
    static function sauvegarder(Proposition $proposition) {
        TexteRepository::sauvegarder($proposition);
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO proposition (id) VALUES (:id)");
        $pdoStatement->execute(['id' => $proposition->getId()]);
        
    }

    static function updateProposition(Proposition $texte) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE texte SET id = :id WHERE id = :id");
        $pdoStatement->execute(['id' => $texte->getId()]);
    }

    static function getOrganisateurId(string $propositionId) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT organisateur FROM proposition WHERE id = :id");
        $pdoStatement->execute(['id' => $propositionId]);
        return $pdoStatement->fetch();
    }


}