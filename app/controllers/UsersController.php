<?php

class UsersController extends BaseController {

    protected $models = ['User','Post'];

    /**
     * Affiche la page de modification de l'utilisateur
     */
    public function index () {
        $this->user->findFirst(Session::Auth()->id);
        App::$response->view('users.index',['user'=>Session::Auth()],['Form'=>[$this->user]]);
    }

    /**
     * Modifie les données de l'utilisateur
     */
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

    /**
     * Affiche la page d'un utilisateur
     * @param  int $id ID de l'utilisateur à afficher
     */
    public function show ($pseudo) {
        $user = $this->user->where('pseudo',$pseudo)->get();
        if (empty($user)) throw new NotFoundException("Utilisateur inconnu");
        $user = $user[0];

        $total = $this->post->where('user_id',$user->id)->count();
        $posts = $this->post
                        ->selectFillable()
                        ->order('!coalesce(posts.updated_at, posts.created_at)','DESC')
                        ->order('id','DESC')
                        ->where('user_id',$user->id)
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
        $this->user->validation['pseudo'][] = 'unique';

        if (empty(App::$request->post['photo']['name'])) unset(App::$request->post['photo']);
        $this->validate($this->user, App::$request->post);
        App::$request->filterPost();

        if (isset(App::$request->post['photo'])) $this->user->moveFile('photo');

        $this->user->insert(App::$request->post);
        App::$session->addMessage('success', 'Vous avez été inscrit !');

        mail('maxime.flin@gmail.com','COUCOU','Vous vous êtes inscrits !');

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
