<?php

class BaseException extends HttpException {

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
