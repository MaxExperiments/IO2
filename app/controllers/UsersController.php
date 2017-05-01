<?php

class UsersController extends BaseController {

    public function index () {
        $this->user->findFirst(Session::Auth()->id);
        App::$response->view('users.index',['user'=>Session::Auth()],['Form'=>[$this->user]]);
    }

    public function update () {
        if(empty(App::$request->post['password'])) unset(App::$request->post['password']);
        $this->validate ($this->user, App::$request->post);
        App::$request->filterPost();
        $this->user->where('id',Session::Auth()->id)->update(App::$request->post);
        Session::authUpdate(App::$request->post);
        App::$response->redirect('/users/');
    }

    public function show ($id) {
        $user = $this->user->findFirst($id);
        if (empty($user)) throw new NotFoundException ("Aucun utilisateur ne correspond à cet identifiant");
        App::$response->view('users.show', ['user'=>$user]);
    }

    /**
     * Formulaire de connection
     */
    public function login () {
        App::$response->view('users.login', [], ['Form'=>[$this->user]]);
    }

    /**
     * Connecte l'utilisateur
     */
    public function connect () {
        $this->validate($this->user, App::$request->post);
        App::$request->filterPost();
        $users = $this->user->where('email',App::$request->post['email'])->findAll();
        if (!empty($users)) {
            if (password_verify(App::$request->post['password'],$users[0]->password)) {
                App::$session->connect($this->user);
                App::$response->redirect('/');
            } else {
                App::$session->addMessage('formValidation','Mauvais email ou mot de passe');
                App::$response->redirect('/login');
            }
        } else {
            App::$session->addMessage('formValidation','Mauvais email ou mot de passe');
            App::$response->redirect('/login');
        }
    }

    /**
     * Formulaire d'inscription
     */
    public function register () {
        App::$response->view('users.register', [], ['Form'=>[$this->user]]);
    }

    /**
     * Ajoute un nouvel utilisateur
     */
    public function store () {
        $this->user->validation['email'][] = 'unique';
        $this->validate($this->user, App::$request->post);
        App::$request->filterPost();
        $this->user->insert(App::$request->post);
        App::$response->redirect('/login');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout () {
        App::$session->disconnect();
        App::$response->redirect('/login');
    }

}
