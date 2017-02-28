<?php

/**
 * Défini des constantes utiles pour les importations de fichiers
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('URL', $_SERVER['REQUEST_URI']);

/**
 * définie une fonction permettant de débugger
 * @param  $v la variable dont on veux afficher la valeure
 */
function d($v) {
    ?><pre><?php var_dump($v) ?></pre><?php
}

/**
 * affiche une variable et tue l'execution après
 * @param  $v la variable qu'on veux afficher
 */
function dd($v) {
    d($v);
    die();
}

/**
 * importe le boostrap
 */
require_once ROOT . DS . 'lib' . DS . 'bootstrap.php';