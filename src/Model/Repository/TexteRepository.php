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

    // retourne vrai si l'utilisateur possède le role passé en paramètre 
    // pour le texte dont l'id passé en paramètre
    static function hasUserRoleInText(string $role, string $textId): bool {
        $pdo = DatabaseConnection::getPdo();
        $query = "SELECT COUNT(*) 
                  FROM possede_role p
                  JOIN utilisateur u ON p.iduser = utilisateur.id
                  JOIN texte t ON t.id = p.idtext
                  JOIN role r ON = r.idRole = p.idrole
                  WHERE r.nomRole = :role;
                  AND r.idtext = :textId";
            
        $pdoStatement = $pdo->prepare($query);
        $pdoStatement->execute(['role' => $role, 'textId' => $textId]);
        return $pdoStatement->fetch() > 0;
    }


}