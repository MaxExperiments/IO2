<?php

/**
 * Class contenant toutes le méthodes néssécaires pour parser les url
 */
class Router {

    use Router\Filters;

    protected $pattern = [];

    protected $params = [];

    /**
     * Liste de toutes le routes définies dans le fichier routes.php
     * triées par type de request
     * @var Array
     */
    private $routes = [
        'get' => [],
        'post' => [],
        'put' => []
    ];

    /**
     * Ajoute un filtre autour des routes générées dans la fonction $then
     * @param  Array   $filters  Liste des filtres a tester
     * @param  callable $then    Fonction a executer si tous les filtres passent
     */
    public function filter ($filters, callable $then) {
        foreach ($filters as $filter) {
            $f = explode (':', $filter);
            if ($f[0][0]=='!') {
                if (!method_exists($this,trim($f[0],'!'))) throw new InternalServerException("Le filtre $f[0] n'existe pas");
                if (call_user_func_array([$this,trim($f[0],'!')],array_slice($f,1))) return false;
            } else {
                if (!method_exists($this,$f[0])) throw new InternalServerException("Le filtre $f[0] n'existe pas");
                if (!call_user_func_array([$this,$f[0]],array_slice($f,1))) return false;
            }
        }
        $then();
    }

    public function get ($url, $method) { $this->routes['get'][$url] = $method; }
    public function post ($url, $method) { $this->routes['post'][$url] = $method; }
    public function put ($url, $method) { $this->routes['put'][$url] = $method; }

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

        new App::$request->controller($this->params);
    }

    /**
     * Test si l'url de la route correspond a l'url de la requete
     * @param  String $url Url de la route a tester
     * @return Boolean
     */
    private function match ($url) {
        foreach ($this->pattern as $key => $pattern) $url = str_replace('{' . $key . '}', '(?P<' . $key . '>' . $pattern . ')', $url);
        preg_match('#^' . $url . '$#', App::$request->url, $matches);
        foreach ($matches as $key => $val)
            if (array_key_exists($key,$this->pattern)) $this->params[$key] = $val;
        return !empty($matches);
    }

    /**
     * Fonction qui lance vraiment l'application en cherchant dans les routes
     * le controller et la méthode à appeler
     * @throws  NotFoundException Si l'url n'existe pas
     */
    public function run () {
        foreach ($this->routes[App::$request->method] as $url => $method)
            if ($this->match(rtrim($url,'/'))) return $this->call($method);
        throw new NotFoundException("La route n'est pas définie");
    }

    public function setPattern($key, $patt) {
        $this->pattern[$key] = $patt;
    }

    public function getRoutes () {
        return $this->routes;
    }

}
