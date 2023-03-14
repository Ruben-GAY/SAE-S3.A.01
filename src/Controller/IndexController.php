<?php
namespace App\Feurum\Controller;

use App\Feurum\Model\Repository\QuestionRepository;

class IndexController {
    public static function afficheVue(string $value, array $parametres = [])
    {
        extract($parametres);
        $viewPath = $value;
        require("../src/view/view.php");
    }

    public static function error(string $message = "Une erreur est survenue")
    {
        self::afficheVue("/error.php", [
            "message" => $message,
            "pageTitle" => "Erreur",
        ]);
    }
}
