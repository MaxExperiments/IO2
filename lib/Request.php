<?php

/**
 * Class représentant la requete Http envoyée au server
 */
class Request {

    /**
     * Attribut static contant correspondant à l'url appelé
     * @var String, url
     */
    public $url;

    /**
     * Variables du $_GET
     * @var Array
     */
    public $get;

    /**
     * Variables du $_POST
     * @var Array
     */
    public $post;

    /**
     * Methode de la requete Http
     * Peut etre POST|GET|PUT|DELETE les autres methodes ne sont pas gere par les routes
     * @var String
     */
    public $method;

    /**
     * Nom du controlleur a appeller
     * @var String
     */
    public $controller;

    /**
     * Nom de la fonction a appeler par le controlleur
     * @var String
     */
    public $func;

    public function __construct() {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->get = $_GET;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }

}