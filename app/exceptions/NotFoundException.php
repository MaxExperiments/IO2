<?php

class NotFoundException extends BaseException {

    public function __construct($message, Exception $previous = null) {
        parent::__construct($message, 404, $previous);
    }

    public function __dev() {
        App::$response->view('err.dev');
    }

    public function __prod() {
        App::$response->view('err.404');
    }

}