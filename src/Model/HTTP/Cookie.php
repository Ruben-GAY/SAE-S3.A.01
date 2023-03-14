<?php

namespace App\Feurum\Model\HTTP;

class Cookie {
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = 0) {
        setcookie($cle, serialize($valeur), $dureeExpiration);
    }

    public static function contient(string $cle): bool {
        return isset($_COOKIE[$cle]);
    }

    public static function lire(string $cle): mixed {
        return isset($_COOKIE[$cle]) ? unserialize($_COOKIE[$cle]) : null;
    }

    public static function supprimer(string $cle) {
        unset($_COOKIE["TestCookie"]);
        setcookie($cle, '',  1);
    }
}