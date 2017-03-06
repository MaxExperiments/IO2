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
     * Variables de la session
     * @var Session
     */
    public $session;

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

    /**
     * Tous les nom de champs passable dans une requete post qu'on veux supprimer dans le model
     * @var Array
     */
    protected static $global = [
        '__method',
        'submit'
    ];

    public function __construct() {
        $this->url = rtrim($_SERVER['REQUEST_URI'],'/');
        $this->get = $_GET;
        $this->post = $_POST;
        $this->session = new Session();
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        if ($this->method == 'post' && isset($this->post['__method'])) $this->method = $this->post['__method'];
    }

    public function filterPost () {
        foreach (self::$global as $key)
            if (isset($this->post[$key])) unset($this->post[$key]);
    }

}