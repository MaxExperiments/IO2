<?php

class NotFoundException extends BaseException {

    public function __construct($message, Exception $previous = null) {
        App::$response->setStatusCode(404);
        parent::__construct($message, 404, $previous);
    }

}