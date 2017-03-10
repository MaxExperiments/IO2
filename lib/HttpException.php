<?php

class HttpException extends Exception {

    /**
     * Constructeur qui appelle le controlleur d'erreurs
     * @param String         $message  Message de l'erreur
     * @param integer        $code     Code de l'erreur
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 503, Exception $previous = null) {
        ini_set ('display_errors','Off');
        
        require_once APP . 'controllers' . DS . 'ErrorsController.php';

        if (CONFIG['env']=='dev') App::$request->func = '__dev';
        else App::$request->func = 'err' . $code;
        App::$response->clear();
        App::$controller = new ErrorsController($this->__toArray());
        App::$controller->render('err');

        parent::__construct($message,$code,$previous);
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