<?php

class NotFoundException extends BaseException {

    protected $code = 404;

    public function __construct($message, Exception $previous = null) {
        App::$response->setStatusCode($this->code);
        parent::__construct($message, $this->code, $previous);
    }

}