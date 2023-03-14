<?php

namespace App\Feurum\Config;


class Conf
{

    static private array $databases = array(
        // Le nom d'hote est webinfo a l'IUT
        // ou localhost sur votre machine
        //
        // ou webinfo.iutmontp.univ-montp2.fr
        // pour accéder à webinfo depuis l'extérieur
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        // A l'IUT, vous avez une BDD nommee comme votre login
        // Sur votre machine, vous devrez creer une BDD
        'database' => 'guemarr',
        // A l'IUT, c'est votre login
        // Sur votre machine, vous avez surement un compte 'root'
        'login' => 'guemarr',
        // A l'IUT, c'est votre mdp (INE par defaut)
        // Sur votre machine personelle, vous avez creez ce mdp a l'installation
        'password' => 'nQBlqylW4UgCJVkh'
    );

    static public function getHostname()
    {
        return static::$databases['hostname'];
    }

    static public function getDatabase()
    {
        return static::$databases['database'];
    }

    static public function getPassword()
    {
        return static::$databases['password'];
    }


    static public function getLogin(): string
    {
        return static::$databases['login'];
    }

}

?>