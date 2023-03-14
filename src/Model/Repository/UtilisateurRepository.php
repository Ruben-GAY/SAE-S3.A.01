<?php



namespace App\Feurum\Model\Repository;

use App\Feurum\Model\DataObject\Question;
use App\Feurum\Model\DataObject\Utilisateur;

class UtilisateurRepository {

    // construit un objet Utilisateur à partir tableau
    static function construire(array $utilisateurTab): Utilisateur {
        return new Utilisateur(
            $utilisateurTab['id'] ? $utilisateurTab['id'] : null,
            $utilisateurTab['nom'],
            $utilisateurTab['prenom'],
            $utilisateurTab['pseudo'],
            $utilisateurTab['email'],
            $utilisateurTab['mot_de_passe'],
            $utilisateurTab['dateDeNaissance'],
            $utilisateurTab['role'] ? $utilisateurTab['role'] : null
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
    static function searchUtilisateurByUsername(string $pseudo) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM utilisateur WHERE pseudo LIKE :pseudo");
        $pdoStatement->execute(['pseudo' => "%$pseudo%"]);
        $res = [];
        foreach ($pdoStatement as $utilisateur) {
            $res[] = static::construire($utilisateur);
        }
        return $res;
    }

    // sauvegarde la utilisateur passer en paramètre dans la base de données
    static function sauvegarder(Utilisateur $utilisateur) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, pseudo, email, mot_de_passe, dateDeNaissance, role) VALUES (:nom, :prenom, :pseudo, :email, :mot_de_passe, :dateDeNaissance, :role)");
        $pdoStatement->execute([
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'pseudo' => $utilisateur->getPseudo(),
            'email' => $utilisateur->getEmail(),
            'mot_de_passe' => $utilisateur->getMotDePasse(),
            'dateDeNaissance' => $utilisateur->getDateDeNaissance(),
            'role' => $utilisateur->getRole(),
        ]);

        $utilisateur->setId($pdo->lastInsertId());
    }

    static function setRole(Utilisateur $utilisateur) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("UPDATE utilisateur SET role = :role WHERE id = :id");
        $pdoStatement->execute([
            'id' => $utilisateur->getId(),
            'role' => $utilisateur->getRole(),
        ]);
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
    // possede_role
    // 1	iduser  Primaire	int(11)			
    // 2	idtext  PrimaireIndex	int(11)			 
    // 3	idrole  PrimaireIndex	int(11)	

    static function ajouterRole(string $idUser, string $idRole, string $idText) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("INSERT INTO possede_role (iduser, idtext, idrole) VALUES (:iduser, :idtext, :idrole)");
        $pdoStatement->execute([
            'iduser' => $idUser,
            'idtext' => $idText,
            'idrole' => $idRole,
        ]);
    }

    static function hasRole(string $idUser, string $idRole, string $idText) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM possede_role WHERE iduser = :iduser AND idtext = :idtext AND idrole = :idrole");
        $pdoStatement->execute([
            'iduser' => $idUser,
            'idtext' => $idText,
            'idrole' => $idRole,
        ]);
        $res = $pdoStatement->fetch();
        return $res ? true : false;
    }

    // getUserByRoleAndQuestion

    static function getUsersByRoleAndText(string $idRole, string $idText) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT * FROM utilisateur u JOIN possede_role p ON u.id=p.iduser WHERE idrole = :idrole AND idtext = :idtext");
        $pdoStatement->execute([
            'idrole' => $idRole,
            'idtext' => $idText,
        ]);
        $res = [];
        foreach ($pdoStatement as $utilisateur) {
            $res[] = static::construire($utilisateur);
        }
        return $res;
    }

    static function isParticipant(string $idUser, string $idText) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT COUNT(*) FROM possede_role WHERE iduser = :iduser AND idtext = :idtext");
        $pdoStatement->execute([
            'iduser' => $idUser,
            'idtext' => $idText,
        ]);
        return $pdoStatement->fetchColumn() > 0;
    }

    // recupere tout les id possedant un certains role d'un text

    static function getRoleByUserAndText(string $idRole, string $idText) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("SELECT iduser FROM possede_role WHERE idrole = :idrole AND idtext = :idtext");
        $pdoStatement->execute([
            'idrole' => $idRole,
            'idtext' => $idText,
        ]);
        $res = [];
        foreach ($pdoStatement as $utilisateur) {
            $res[] = static::construire($utilisateur);
        }
        return $res;
    }

    // supprime le role d'un utilisateur

    static function supprimerRole(string $idUser, string $idRole, string $idText) {
        $pdo = DatabaseConnection::getPdo();
        $pdoStatement = $pdo->prepare("DELETE FROM possede_role WHERE iduser = :iduser AND idtext = :idtext AND idrole = :idrole");
        $pdoStatement->execute([
            'iduser' => $idUser,
            'idtext' => $idText,
            'idrole' => $idRole,
        ]);
    }
}
