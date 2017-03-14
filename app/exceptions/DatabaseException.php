<?php

class DatabaseException extends HttpException {

    public function __construct($message, $code, Exception $previous = null) {
        $this->code = $code;
        $this->message = $message;
        App::$response->setStatusCode(500);
        parent::__construct($message,$code,$previous);
    }

}
