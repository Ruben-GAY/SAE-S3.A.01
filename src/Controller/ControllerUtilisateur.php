<?php

namespace App\Feurum\Controller;

use App\Feurum\Lib\MotDePasse;
use App\Feurum\Lib\Password;
use App\Feurum\Model\HTTP\MESSAGE_TYPE;
use App\Feurum\Model\HTTP\MessageFlash;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur {

    static function register() {
        IndexController::afficheVue("/utilisateurs/inscription.php", ["pageTitle" => "Inscription"]);
    }

    static function registered() {

        if (!isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['pseudo']) || !isset($_POST['email']) || !isset($_POST['mot_de_passe']) || !isset($_POST['date_de_naissance'])) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Tous les champs sont obligatoires");
            header("Location: frontController.php?controller=utilisateur&action=register");
            return;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "L'adresse email n'est pas valide");
            header("Location: frontController.php?controller=utilisateur&action=register");
            return;
        }

        if ($_POST['mot_de_passe'] != $_POST['mot_de_passe_confirm']) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "Les mots de passe ne correspondent pas");
            header("Location: frontController.php?controller=utilisateur&action=register");
            return;
        }

        $utilisateur = new Utilisateur(
            null,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['pseudo'],
            $_POST['email'],
            Password::hacher($_POST['mot_de_passe']),
            $_POST['date_de_naissance'],
            null,
        );
        UtilisateurRepository::sauvegarder($utilisateur);

        $serializedUtilisateur = serialize($utilisateur);
        Session::getInstance()->enregistrer("utilisateur", $serializedUtilisateur);

        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "Vous êtes bien inscrit");

        header("Location: frontController.php?controller=question&action=all");
        exit;
    }

    static function login() {
        IndexController::afficheVue("/utilisateurs/connexion.php", ["pageTitle" => "Connexion"]);
    }

    static function logged() {
        $pseudo = $_POST['pseudo'];
        if (strlen($pseudo) < 3) return MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Le pseudo doit faire au moins 3 caractères");
        $motDePasse = $_POST['mot_de_passe'];

        $utilisateur = UtilisateurRepository::getUtilisateurByPseudo($pseudo);
        if (!$utilisateur || !Password::verifier($motDePasse, $utilisateur->getMotDePasse())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Pseudo ou mot de passe incorrect");
            header("Location: frontController.php?controller=utilisateur&action=login");
            return;
        }

        $serializedUtilisateur = serialize($utilisateur);
        Session::getInstance()->enregistrer("utilisateur", $serializedUtilisateur);

        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "Vous êtes connecté");

        header("Location: frontController.php?controller=question&action=all");
        exit;
    }

    static function logout() {
        Session::getInstance()->supprimer("utilisateur");
        Session::getInstance()->detruire();
        Session::getInstance();
        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "Vous êtes déconnecté");
        header("Location: frontController.php?controller=question&action=all");
        exit;
    }

    static function update() {
        $utilisateur = Session::getInstance()->getUtilisateur();
        IndexController::afficheVue("/utilisateurs/updateProfil.php", ['utilisateur' => $utilisateur, "pageTitle" => "Modifier le Profil"]);
    }

    static function profile() {
        $utilisateur = Session::getInstance()->getUtilisateur();
        IndexController::afficheVue("/utilisateurs/profile.php", ['utilisateur' => $utilisateur, "pageTitle" => "Profil"]);
    }

    static function updated() {
        $utilisateur = Session::getInstance()->getUtilisateur();

        if (!Password::verifier($_POST['mot_de_passe'], $utilisateur->getMotDePasse())) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Mot de passe incorrect");
            header("Location: frontController.php?controller=utilisateur&action=update");
            return;
        }

        if (!isset($_POST['pseudo'])) {
        }


        if (!isset($_POST['new_mot_de_passe '])) {
            if ($_POST['new_mot_de_passe'] != $_POST['new_mot_de_passe_confirm']) {
                MessageFlash::ajouter(MESSAGE_TYPE::TYPE_WARNING, "Les mots de passe ne correspondent pas");
                header("Location: frontController.php?controller=utilisateur&action=update");
                return;
            } else {
                $utilisateur->setMotDePasse($_POST['new_mot_de_passe']);
            }
            $utilisateur->setMotDePasse($_POST['mot_de_passe']);
        }

        $serializedUtilisateur = serialize($utilisateur);
        Session::getInstance()->enregistrer('utilisateur', $serializedUtilisateur);

        UtilisateurRepository::sauvegarder($utilisateur);
    }


    static function allUsers() {
        $utilisateur = IndexController::isLogged();

        if ($utilisateur->getRole() != 1) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour accéder à cette page");
            header("Location: frontController.php?controller=question&action=all");
            return;
        }

        $utilisateurs = UtilisateurRepository::getAllUtilisateurs();
        IndexController::afficheVue("/utilisateurs/userList.php", ['utilisateurs' => $utilisateurs, "pageTitle" => "Tous les utilisateurs"]);
    }

    static function queryJSON() {
        $pseudoCherche = $_GET['pseudo'];
        if (!$pseudoCherche) return MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucun pseudo n'a été fourni");
        if (strlen($pseudoCherche) < 3) return MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Le pseudo doit faire au moins 3 caractères");
        $utilisateurs = UtilisateurRepository::searchUtilisateurByUsername($pseudoCherche);
        echo json_encode(array_map(function ($utilisateur) {
            return [
                'id' => $utilisateur->getId(),
                'pseudo' => $utilisateur->getPseudo(),
            ];
        }, $utilisateurs));
    }

    static function switchRole() {
        $utilisateur = IndexController::isLogged();
        if ($utilisateur->getRole() != "1") {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous n'avez pas les droits pour accéder à cette page");
            header("Location: frontController.php?controller=question&action=all");
            return;
        }

        $id = $_GET['id'];
        $role = $_GET['role'];

        if (!$id || !$role) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucun utilisateur n'a été trouvé");
            header("Location: frontController.php?controller=utilisateur&action=allUsers");
            return;
        }

        if ($role == "aucun") $role = null;

        $utilisateur = UtilisateurRepository::getUtilisateurById($id);
        if (!$utilisateur) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Aucun utilisateur n'a été trouvé");
            header("Location: frontController.php?controller=utilisateur&action=allUsers");
            return;
        }

        $utilisateur->setRole($role);
        UtilisateurRepository::sauvegarder($utilisateur);

        MessageFlash::ajouter(MESSAGE_TYPE::TYPE_SUCCESS, "Le rôle de l'utilisateur a été modifié");
        header("Location: frontController.php?controller=utilisateur&action=allUsers");
        exit;
    }
}
