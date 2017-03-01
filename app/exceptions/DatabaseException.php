<?php

class DatabaseException extends HttpException {

    public function __construct($message, $code, Exception $previous = null) {
        parent::__construct($message,$code,$previous);
        dd($this);
    }

}
