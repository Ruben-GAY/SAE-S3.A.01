<?php
namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\Repository\UtilisateurRepository;


class ControllerUtilisateur {

    static function register() {
        IndexController::afficheVue("/utilisateurs/inscription.php");
    }

    static function registered() {
        $utilisateur = new Utilisateur(
            null,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['pseudo'],
            $_POST['email'],
            $_POST['mot_de_passe'],
            $_POST['dateDeNaissance'],
        );
        UtilisateurRepository::sauvegarder($utilisateur);
        self::demarrerSession($utilisateur);
        header("Location: frontController.php?controller=question&action=all");

    }

    static function login() {
        IndexController::afficheVue("/utilisateurs/connexion.php");
    }

    static function loggedIn() {
        $pseudo = $_POST['pseudo'];
        $motDePasse = $_POST['mot_de_passe'];
        $utilisateur = UtilisateurRepository::getUtilisateurByPseudo($pseudo);
        if(!$utilisateur || $utilisateur->getMotDePasse() !== $motDePasse) {
            $errorMessage = "Pseudo ou mot de passe incorrect";
            IndexController::afficheVue("/utilisateurs/connexion.php", ['errorMessage' => $errorMessage]);
            return;
        }

        self::demarrerSession($utilisateur);
        header("Location: frontController.php?controller=question&action=all");
            
    }

    static function demarrerSession(Utilisateur $utilisateur) {
        // session
    }

}

