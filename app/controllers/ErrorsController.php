<?php

class ErrorsController extends BaseController {

    protected $isModel = false;

    /**
     * Page d'erreur 404
     */
    public function err404 () {
        App::$response->view('err.404');
    }

    /**
     * Page d'erreur 500
     */
    public function err500() {
        App::$response->view('err.500');
    }

    /**
     * Page d'erreur pour les développement
     * @param  String $message Message d'erreur
     * @param  int    $code    Code de l'erreur
     * @param  String $file    Fichier de l'erreur
     * @param  int    $line    Ligne de l'erreur
     * @param  Array  $trace   Trace de l'erreur
     */
    public function __dev($message, $code, $file, $line, $trace) {
        App::$response->view('err.dev',[
            'message' => $message,
            'code'    => $code,
            'file'    => $file,
            'line'    => $line,
            'trace'   => $trace
        ]);
    }

}