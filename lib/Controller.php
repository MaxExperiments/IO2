<?php

class Controller {

    /**
     * Le layout dans lequel la view vas etre rendu
     * @var String
     */
    protected $layout;

    /**
     * Liste du nom de tous les modeles nessecaires dans la controller
     * @var array
     */
    protected $models = [];

    /**
     * Les helpers a charger dans toutes les vues
     * @var array
     */
    protected $autoLoadHelpers = [];

    /**
     * Constructeur qui appelle la fonction definie dans la route
     * @param Array|null $params Les paramètres a passer a la fonction
     */
    public function __construct(Array $params = []) {
        if (!method_exists($this, App::$request->func)) throw new NotFoundException("La méthode à appeler est introuvable");
        App::$controller = $this;
        foreach ($this->models as $model) {
            $modelPath = APP . 'models' . DS . $model . '.php';
            if (!file_exists($modelPath)) throw new InternalServerException("Le fichier $modelPath n'existe pas");
            
            require_once $modelPath;
            $model = strtolower($model);
            if (!class_exists($model)) throw new InternalServerException("La classe $model n'existe pas");
            
            $this->$model = new $model();
        }
        call_user_func_array([$this,App::$request->func], $params);
    }

    /**
     * Affiche le rendu définitif de la page
     * @param  String|null $layout Layout a appeller si on désire le changer pour le render
     */
    public function render (String $layout = null) {
        App::$response->prepare();
        $layout = ($layout===null) ? $this->layout : $layout;
        $layoutPath = APP . 'views' . DS . 'layouts' . DS . $layout . '.php';
        if (!file_exists($layoutPath)) throw new InternalServerException("Le layout $layout est introuvable");
        
        include $layoutPath;
    }

    /**
     * Setter pour le layout
     * @param String $layout chemin jusqu'au nouveau layout
     */
    public function setLayout(String $layout) {
        $this->layout = $layout;
    }

    public function getAutoLoadHelpers() {
        return $this->autoLoadHelpers;
    }
    
}