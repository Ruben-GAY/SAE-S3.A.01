<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Utilisateur;

class UtilisateurRepository {

    // construit un objet Utilisateur à partir tableau
    static function construire(array $utilisateurTab) : Utilisateur {
        return new Utilisateur(
            $utilisateurTab['id'] ? $utilisateurTab['id'] : null,
            $utilisateurTab['nom'],
            $utilisateurTab['prenom'],
            $utilisateurTab['pseudo'],
            $utilisateurTab['email'],
            $utilisateurTab['mot_de_passe'],
            $utilisateurTab['dateDeNaissance'],
        );
    }
    // retourne toutes les utilisateurs
    static function getAllUtilisateurs() {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->query("SELECT * FROM utilisateur");
        $res = [];
        foreach ($pdoStatement as $utilisateur) {
            $res[] = static::construire($utilisateur);
        }
        return $res;
    }

    // retourne la utilisateur correspondant à l'id passer en paramètre
    static function getUtilisateurById(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM utilisateur WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
        $utilisateur = $pdoStatement->fetch();
        return $utilisateur ? static::construire($utilisateur) : null;
    }

    // retourne l'utilisateur correspondant au pseudo passer en paramètre

    static function getUtilisateurByPseudo(string $pseudo) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
        $pdoStatement->execute(['pseudo' => $pseudo]);
        $utilisateur = $pdoStatement->fetch();
        return $utilisateur ? static::construire($utilisateur) : null;
    }


    // recherche les utilisateurs correspondant au name passer en paramètre
    static function searchUtilisateurByUsername(string $name) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM utilisateur WHERE name LIKE :Username");
        $pdoStatement->execute(['name' => "%$name%"]);
        $res = [];
        foreach ($pdoStatement as $utilisateur) {
            $res[] = static::construire($utilisateur);
        }
        return $res;
    }

    // sauvegarde la utilisateur passer en paramètre dans la base de données
    static function sauvegarder(Utilisateur $utilisateur) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, pseudo, email, mot_de_passe, dateDeNaissance) VALUES (:nom, :prenom, :pseudo, :email, :mot_de_passe, :dateDeNaissance)");
        $pdoStatement->execute([
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'pseudo' => $utilisateur->getPseudo(),
            'email' => $utilisateur->getEmail(),
            'mot_de_passe' => $utilisateur->getMotDePasse(),
            'dateDeNaissance' => $utilisateur->getDateDeNaissance(),
        ]);

        $utilisateur->setId($pdo->lastInsertId());
        
    }

    // met à jour la utilisateur passer en paramètre dans la base de données
    static function update(Utilisateur $utilisateur) {
        $pdo = DatabaseConnection::getPdo();

        $pdoStatement = $pdo->prepare("UPDATE utilisateur SET nom = :nom, prenom = :prenom, pseudo = :pseudo, email = :email, mot_de_passe = :mot_de_passe, dateDeNaissance = :dateDeNaissance WHERE id = :id");
        $pdoStatement->execute([
            'id' => $utilisateur->getId(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'pseudo' => $utilisateur->getPseudo(),
            'email' => $utilisateur->getEmail(),
            'mot_de_passe' => $utilisateur->getMotDePasse(),
            'dateDeNaissance' => $utilisateur->getDateDeNaissance(),
        ]);
    }

    // supprime la utilisateur correspondant à l'id passer en paramètre
    static function supprimer(string $id) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM utilisateur WHERE id = :id");
        $pdoStatement->execute(['id' => $id]);
    }

}