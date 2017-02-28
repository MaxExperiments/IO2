<?php

/**
 * Classe agissant comme une facade de l'application
 * Donne un accèes rapide aux instances de classes importantes de l'application
 */
class App {

    /**
     * Requete Http envoyé au server sous forme d'objet php
     * @var Request
     */
    public static $request;

    /**
     * Reponse Http renvoye par le serveur sous forme d'objet
     * @var Response
     */
    public static $response;

    public static $route;

}