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
        App::$response->view('posts.create', [], ['Form'=>[$this->post]]);
    }

    public function store () {
        if(!$this->post->validate(App::$request->post)) {
            foreach (App::$request->post as $field => $value) {
                $message = [$value];
                if(array_key_exists($field, $this->post->messages)) $message[] = $this->post->messages[$field];
                App::$request->session->addMessage($field, $message);
            }
            App::$response->redirect('/posts/create');
        }
        App::$request->filterPost();
        $this->post->insert(App::$request->post);
    }

    public function edit ($id) {
        if (empty($this->post->findFirst($id))) throw new NotFoundException("Aucun post ne correspond à l'ID $id");
        App::$response->view('posts.create', [], ['Form'=>[$this->post]]);
    }

    public function update ($id) {
    }

}