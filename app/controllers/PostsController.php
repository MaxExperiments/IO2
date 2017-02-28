<?php

class PostsController extends BaseController {
    
    public function index () {
        App::$response->view('posts.index');
    }

    public function home() {
        App::$response->view('posts.index');
    }

    public function show($id) {
        echo 'show';
    }

}