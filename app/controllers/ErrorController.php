<?php

class ErrorController extends BaseController {

    public function err404 () {
        App::$response->view('err.404',['message'=>'Page Introuvable']);
    }

    public function err500() {

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