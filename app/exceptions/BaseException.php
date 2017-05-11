<?php

class BaseException extends HttpException {

    /**
     * Gère le cas où la requete est ajax
     * @param String         $message  Message d'erreur
     * @param int         $code        Code de l'erreur
     * @param Exception|null $previous Erreur parente
     */
    public function __construct($message, $code, Exception $previous = null) {
        $this->message = $message;
        $this->code = $code;
        if (App::$request->isJson()) App::$response->json([
            'message' => $this->message,
            'success' => false
        ]);
        parent::__construct($message,$code,$previous);
    }

}
