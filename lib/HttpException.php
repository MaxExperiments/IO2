<?php

class HttpException extends Exception {

    /**
     * Constructeur qui appelle le controlleur d'erreurs
     * @param String         $message  Message de l'erreur
     * @param integer        $code     Code de l'erreur
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 503, Exception $previous = null) {
        parent::__construct($message,$code,$previous);
        
        require_once APP . 'controllers' . DS . 'ErrorController.php';

        if (CONFIG['env']=='dev') App::$request->func = '__dev';
        else App::$request->func = 'err' . $code;
        App::$controller = new ErrorController($this->__toArray());
        App::$controller->render('err');
    }

    /**
     * Converti les attributes de la classe en tableau pour les passer au controlleur
     * @return Array Tableau associatif des attributs de l'exception
     */
    protected function __toArray() {
        return [
            'message' => $this->getMessage(),
            'code'    => $this->getCode(),
            'file'    => $this->getFile(),
            'line'    => $this->getLine(),
            'trace'   => $this->getTraceAsString()
        ];
    }

}