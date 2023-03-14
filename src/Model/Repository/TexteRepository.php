<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Texte;

class TexteRepository {

    // sauvegarder un texte dans la BD
    static function sauvegarder(Texte $texte) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO texte (id) VALUES (NULL)");
        $pdoStatement->execute();
        $texte->setId($pdo->lastInsertId());
    }

    // met a jour le texte dans la BD

    static function update(Texte $texte) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE texte SET id = :id WHERE id = :id");
        $pdoStatement->execute(['id' => $texte->getId()]);
    }
}
