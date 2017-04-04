<?php

class UsersController extends BaseController {

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
