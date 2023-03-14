<?php

namespace App\Feurum\Lib;

class Password {

    // Exécutez genererChaineAleatoire() et stockez sa sortie dans le poivre
    private static string $poivre = "E0TRFAiryjBzUI1GkWu5nU";

    public static function hacher(string $mdpClair): string {
        $mdpPoivre = hash_hmac("sha256", $mdpClair, Password::$poivre);
        $mdpHache = password_hash($mdpPoivre, PASSWORD_DEFAULT);
        return $mdpHache;
    }

    public static function verifier(string $mdpClair, string $mdpHache): bool {
        $mdpPoivre = hash_hmac("sha256", $mdpClair, Password::$poivre);
        return password_verify($mdpPoivre, $mdpHache);
    }
}

// Pour créer votre poivre (une seule fois)
