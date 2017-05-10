<?php

class ErrorsController extends BaseController {

    protected $isModel = false;

    public function err404 () {
        App::$response->view('err.404');
    }

    public function err500() {
        App::$response->view('err.500');
    }

    public function __dev($message, $code, $file, $line, $trace) {
        App::$response->view('err.dev',[
            'message' => $message,
            'code'    => $code,
            'file'    => $file,
            'line'    => $line,
            'trace'   => $trace
        ]);
    }

}
