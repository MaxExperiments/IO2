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

    /**
     * Url de la requête précédente
     * @var String
     */
    private $referer;

    /**
     * Content type de la requête
     * @var String
     */
    private $content_type;

    /**
     * Tous les nom de champs passable dans une requete post qu'on veux supprimer dans le model
     * @var Array
     */
    protected static $global = [
        '__method',
        '__token',
        'submit'
    ];

    public function __construct() {
        $this->url = explode('?',rtrim($_SERVER['REQUEST_URI'],'/'))[0];
        $this->get = $_GET;
        $this->post = array_merge($_POST,$_FILES);

        App::$session = new Session();
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
        $this->content_type = (!empty($_SERVER['CONTENT_TYPE'])) ? $_SERVER['CONTENT_TYPE'] : 'text/html';
        if ($this->method == 'post' && isset($this->post['__method'])) $this->method = $this->post['__method'];
    }

    /**
     * Retire des champs de post les clefs globales définies
     */
    public function filterPost () {
        foreach (self::$global as $key)
            if (isset($this->post[$key])) unset($this->post[$key]);
    }

    /**
     * Retourne l'url de la requête précédente
     * @return String
     */
    public function getReferer () {
        return $this->referer;
    }

    public function isJson () {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

}
