<?php

class BaseException extends HttpException {

    public function __construct($message, $code, Exception $previous = null) {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message,$code,$previous);
    }
    
}