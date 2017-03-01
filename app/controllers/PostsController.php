<?php

class PostsController extends BaseController {

    public function home() {
        App::$response->view('posts.home');
    }

    public function index() {
        $post = $this->post->findAll();
        dd($post);
        App::$response->view ('posts.index',['post'=>$post]);
    }

}