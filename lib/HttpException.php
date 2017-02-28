<?php

class HttpException extends Exception {

    public function __construct($message, $code = 503, Exception $previous = null) {
        parent::__construct($message,$code,$previous);
        if (CONFIG['env']) $this->__dev();
        else $this->prod();
    }

}