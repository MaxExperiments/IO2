<?php

class PostsController extends BaseController {

    public function home() {
        App::$response->view('posts.home');
    }

    public function index() {
        $post = $this->post->findAll();
        App::$response->view ('posts.index',['posts'=>$post]);
    }

    public function show ($id) {
        $post = $this->post->findFirst($id);
        if (empty($post)) throw new NotFoundException("Aucun post ne correspond à l'ID $id");
    }

    public function create () {
        App::$response->view('posts.create', [], ['Form'=>[$this->post]]);
    }

    public function store () {
        dd($this);
    }

}