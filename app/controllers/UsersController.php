<?php

class UsersController extends BaseController {

    protected $models = ['User','Post'];

    public function index () {
        $this->user->findFirst(Session::Auth()->id);
        App::$response->view('users.index',['user'=>Session::Auth()],['Form'=>[$this->user]]);
    }

    public function update () {
        if(empty(App::$request->post['password'])) unset(App::$request->post['password']);
        if(empty(App::$request->post['photo']) || App::$request->post['photo']['error'] == 4) unset(App::$request->post['photo']);
        $this->validate ($this->user, App::$request->post);
        App::$request->filterPost();
        if (isset(App::$request->post['photo'])) $this->user->moveFile('photo');
        $this->user->where('id',Session::Auth()->id)->update(App::$request->post);
        Session::authUpdate(App::$request->post);
        App::$session->addMessage('success', 'Compte bien modifié !');
        App::$response->redirect('/users/');
    }

    public function show ($id) {
        $total = $this->post->where('user_id',$id)->count();
        $user = $this->user->findFirst($id);
        $posts = $this->post
                        ->selectFillable()
                        ->order('!coalesce(posts.updated_at, posts.created_at)','DESC')
                        ->order('id','DESC')
                        ->where('user_id',$id)
                        ->paginate()
                        ->get();
        if (empty($user)) throw new NotFoundException ("Aucun utilisateur ne correspond à cet identifiant");
        App::$response->view('users.show', ['total'=>$total,'user'=>$user,'posts'=>$posts]);
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
                App::$session->addMessage('success', 'Bonjour '.$users[0]->pseudo.' !');
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
        if (empty(App::$request->post['photo']['name'])) unset(App::$request->post['photo']);
        $this->validate($this->user, App::$request->post);
        App::$request->filterPost();

        if (isset(App::$request->post['photo'])) $this->user->moveFile('photo');

        $this->user->insert(App::$request->post);
        App::$session->addMessage('success', 'Vous avez été inscrit !');

        App::$response->redirect('/login');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout () {
        App::$session->disconnect();
        App::$session->addMessage('success', 'Au revoir '.Session::Auth()->pseudo.' !');
        App::$response->redirect('/login');
    }

}
