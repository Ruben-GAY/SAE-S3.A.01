<?php

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

use App\Feurum\Controller\IndexController;
use App\Feurum\Controller\ControllerQuestion;
use App\Feurum\Controller\ControllerUtilisateur;
use App\Feurum\Controller\ControllerReponse;


$loader = new App\Feurum\Lib\Psr4AutoloaderClass();
$loader->addNamespace('App\Feurum', __DIR__ . '/../src');
$loader->register();

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'question';
$action =  isset($_GET['action']) ? $_GET['action'] : 'allQuestion';

$controllerClassName = "App\Feurum\Controller\Controller".ucfirst($controller);
if(!class_exists($controllerClassName)) return IndexController::error("$controllerClassName n'est pas un controller valide");

$classMethods = get_class_methods($controllerClassName);
if(!in_array($action, $classMethods)) return IndexController::error("$action n'est pas une action valide");

$pageTitle = "Feurum";

$controllerClassName::$action();