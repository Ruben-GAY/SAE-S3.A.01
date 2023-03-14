<?php

namespace App\Feurum\Controller;

use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\HTTP\MESSAGE_TYPE;
use App\Feurum\Model\HTTP\MessageFlash;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\Repository\QuestionRepository;

class IndexController {
    public static function afficheVue(string $value, array $parametres = []) {
        extract($parametres);
        $viewPath = $value;
        require("../src/view/view.php");
    }

    public static function error(string $message = "Une erreur est survenue") {
        self::afficheVue("/error.php", [
            "message" => $message,
            "pageTitle" => "Erreur",
        ]);
    }

    public static function isLogged(): Utilisateur {
        $utilisateur = Session::getInstance()->getUtilisateur();

        if ($utilisateur === null) {
            MessageFlash::ajouter(MESSAGE_TYPE::TYPE_DANGER, "Vous devez être connecté pour accéder à cette page");
            header("Location: frontController.php?controller=question&action=all");
            exit;
        }

        return $utilisateur;
    }
}
