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
        App::$response->view('posts.show', ['post'=>$post]);
    }

    public function create () {
        App::$response->view('posts.create', ['method'=>'put'], ['Form'=>[$this->post]]);
    }

    public function store () {
        $this->validate ($this->post, App::$request->post);
        App::$request->filterPost();
        $this->post->insert(App::$request->post);
        App::$response->redirect('/posts/');
    }

    public function edit ($id) {
        if (empty($this->post->findFirst($id))) throw new NotFoundException("Aucun post ne correspond à l'ID $id");
        App::$response->view('posts.create', ['method'=>'post'], ['Form'=>[$this->post]]);
    }

    public function update ($id) {
        $this->validate ($this->post, App::$request->post);
        App::$request->filterPost();
        $this->post->where(['id'=>$id])->update(App::$request->post);
        App::$response->redirect('/posts/');
    }

    public function destroy () {
        
    }

}