<?php

/**
 * Class contenant toutes le méthodes néssécaires pour parser les url
 */
class Router {

    /**
     * Liste de toutes le routes définies dans le fichier routes.php
     * triées par type de request
     * @var Array
     */
    private $routes = [
        'get' => [],
        'post' => [],
        'put' => [],
        'delete' => []
    ];

    public function get ($url, $method) { $this->routes['get'][$url] = $method; }
    public function post ($url, $method) { $this->routes['post'][$url] = $method; }
    public function put ($url, $method) { $this->routes['put'][$url] = $method; }
    public function delete ($url, $method) { $this->routes['delete'][$url] = $method; }
    public function err ($url, $method) { $this->routes['err'][$url] = $method; }
    public function when($url, $func) { $this->routes['func'] = $func; }

    /**
     * Appelle la méthode définie dans les routes.php
     * @param  String $method Chaine de charactères définissant le controlleur et la méthode a appeler
     * Sous la forme de NomDuController@nomDeLaMethode
     * @throws NotFoundException Dans le cas ou le controller n'existe pas
     */
    private function call ($method) {
        App::$request->controller = explode('@',$method)[0];
        App::$request->func = explode('@',$method)[1];
        require_once APP . 'controllers' . DS . App::$request->controller . '.php';
        if (!class_exists(App::$request->controller)) throw new NotFoundException("Le controller à appeler n'existe pas");
        
        App::$controller = new App::$request->controller();
    }

    /**
     * Fonction qui lance vraiment l'application en cherchant dans les routes
     * le controller et la méthode à appeler
     * @throws  NotFoundException Si l'url n'existe pas
     */
    public function run () {
        foreach ($this->routes[App::$request->method] as $url => $method)
            if ($url===App::$request->url) return $this->call($method);
        throw new NotFoundException("La route n'est pas définie");
    }

}