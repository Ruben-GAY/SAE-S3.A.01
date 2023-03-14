<?php

namespace App\Feurum\Model\Repository;

use App\Feurum\Config\Conf;
use PDO;

class DatabaseConnection
{

    private PDO $pdo;
    private static ?DatabaseConnection $instance = null;

    public static function getPdo()
    {
        return static::getInstance()->pdo;
    }

    private function __construct()
    {

        $hostname = Conf::getHostname();
        $database_name = Conf::getDatabase();
        $login = Conf::getLogin();
        $password = Conf::getPassword();


        $this->pdo = new PDO("mysql:host=$hostname;dbname=$database_name", $login, $password);
        //array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    public static function getInstance(): DatabaseConnection
    {
        if (is_null(static::$instance))
            static::$instance = new DatabaseConnection();
        return static::$instance;
    }


}