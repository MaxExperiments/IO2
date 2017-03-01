<?php

class Controller {

    /**
     * Le layout dans lequel la view vas etre rendu
     * @var String
     */
    protected $layout;

    /**
     * Constructeur qui appelle la fonction definie dans la route
     * @param Array|null $params Les paramètres a passer a la fonction
     */
    public function __construct(Array $params = []) {
        if (!method_exists($this, App::$request->func)) throw new NotFoundException("La méthode à appeler est introuvable");
        call_user_func_array([$this,App::$request->func], $params);
    }

    /**
     * Affiche le rendu définitif de la page
     * @param  String|null $layout Layout a appeller si on désire le changer pour le render
     */
    public function render (String $layout = null) {
        App::$response->prepare();
        $layout = ($layout===null) ? $this->layout : $layout;
        include APP . 'views' . DS . 'layouts' . DS . $layout . '.php';
    }

    /**
     * Setter pour le layout
     * @param String $layout chemin jusqu'au nouveau layout
     */
    public function setLayout(String $layout) {
        $this->layout = $layout;
    }
    
}