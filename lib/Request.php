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

    public $get;

    public $method;

    public function __construct() {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->get = $_GET;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }

}