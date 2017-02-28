<?php

class Response {

    private $render;

    public function view ($path) {
        $path = APP . 'views' . DS . str_replace('.', DS, $path) . '.php';
        ob_start();
        require_once $path;
        $this->render = $this->render . ob_get_clean();
    }

}