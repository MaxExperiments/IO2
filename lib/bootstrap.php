<?php

/**
 * Fichier faisant les importations de fichiers
 * Structure de l'application
 * - /app tous les fichiers de l'application
 *     - /controllers Tous les controllers
 *     - /model Tous les models
 *     - /views Toutes le vues
 *         - /layout Les Layouts de base a importer dans les autres
 *     - config.php Fichier de configuration de l'application
 *     - routes.php Définis toutes les urls et appels de controller
 * - /lib Toutes le classes globale de l'application
 * - /public Les ressources client de l'application
 * - /tmp Fichier de debug, log...
 */

// quelques constantes utiles en plus
define('LIB', ROOT . DS . 'lib' . DS);
define('PUBLIC', ROOT . DS . 'public' . DS);
define('TMP', ROOT . DS . 'tmp' . DS);
define('APP', ROOT . DS . 'app' . DS);

require_once APP . 'config.php';

/**
 * Affiche les erreurs si l'application est en mode debug, les caches dans un fichier err.log sinon
 */
if (CONFIG['env'] === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
} else {
    error_reporting (E_ALL);
    ini_set ('display_errors','Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', TMP . 'err.log');
}

require_once LIB . 'HttpException.php';
require_once LIB . 'Request.php';
require_once LIB . 'Response.php';
require_once LIB . 'Helper.php';
require_once LIB . 'Model.php';
require_once LIB . 'Controller.php';
require_once APP . 'controllers' . DS . 'BaseController.php';
require_once LIB . 'Router.php';
require_once LIB . 'App.php';
require_once APP . 'exceptions' . DS . 'BaseException.php';
require_once APP . 'exceptions' . DS . 'NotFoundException.php';
require_once APP . 'exceptions' . DS . 'DatabaseException.php';

App::$request = new Request();
App::$route = new Router();
App::$response = new Response();

require_once APP . 'routes.php';

// lance l'application par l'évaluation de l'url
App::$route->run();
App::$controller->render();