<?php

class PostsController extends BaseController {

    protected $models = ['Post','Reply'];

    /**
     * Affiche la page d'accueil
     */
    public function home() {
        $posts = $this->post->selectFillable()
                        ->order('!coalesce(posts.updated_at, posts.created_at)','DESC')
                        ->order('id', 'DESC')
                        ->limit(null,5)
                        ->findAll();
        App::$response->view ('posts.index',['posts'=>$posts,'headline'=>'Bienvenu sur Diderot.club !']);
    }

    /**
     * Affiche tous les posts avec une pagination
     */
    public function index() {
        $total = $this->post->count();
        $posts = $this->post->selectFillable()
                            ->order('!coalesce(posts.updated_at, posts.created_at)','DESC')
                            ->order('id','DESC')
                            ->paginate()
                            ->findAll();
        App::$response->view ('posts.index',['total'=>$total,'posts'=>$posts,'headline'=>'Tous les posts']);
    }

    /**
     * Affiche un post et toutes ses réponses
     * @param  int $id ID du post
     * @throws NotFoundException Si le post n'existe pas
     */
    public function show ($slug, $id) {
        $post = $this->post->selectFillable()->findFirst($id);
        if (empty($post)) throw new NotFoundException("Aucun post ne correspond à l'ID $id");
        if ($post->slug != $slug) App::$response->redirect('/posts/'.$post->slug.'-'.$id);
        $replies = $this->reply->selectFillable()
                                ->where('post_id',$id)
                                ->order('stars','desc')
                                ->order('!coalesce(replies.updated_at, replies.created_at)','DESC')
                                ->order('id', 'DESC')
                                ->limit(null, 30)
                                ->get();
        $this->reply->last = [];
        if (App::$request->isJson()) App::$response->json($post);
        App::$response->view('posts.show', ['post'=>$post,'replies'=>$replies],['Form'=>[$this->reply]]);
    }

    /**
     * Affiche la page pour ajouter un nouveau post
     */
    public function create () {
        App::$response->view('posts.create', ['method'=>'put'], ['Form'=>[$this->post]]);
    }

    /**
     * Ajoute un nouveau post
     */
    public function store () {
        if(empty(App::$request->post['photo']) || App::$request->post['photo']['error'] == 4) unset(App::$request->post['photo']);

        $this->validate ($this->post, App::$request->post);
        App::$request->filterPost();
        App::$request->post['slug'] = $this->post->slug(App::$request->post['title']);
        App::$request->post['user_id'] = Session::Auth()->id;
        
        if (isset(App::$request->post['photo'])) $this->post->moveFile('photo');
        $this->post->insert(App::$request->post);

        App::$session->addMessage('success', 'Post bien ajouté !');
        App::$response->redirect('/posts/');
    }

    /**
     * Affiiche la page pour editer un post
     * @param  int $id Id du post
     * @throws  NotFoundException Si le post n'existe pas
     * @throws ForbbidenException Si le post n'appartient pas à l'utilisateur connecté
     */
    public function edit ($id) {
        $post = $this->post->findFirst($id);
        if (empty($post)) throw new NotFoundException("Aucun post ne correspond à l'ID $id");
        if ($post->user_id != Session::Auth()->id) throw new ForbbidenException("Vous ne pouvez pas éditer ce post");
        
        App::$response->view('posts.create', ['method'=>'post'], ['Form'=>[$this->post]]);
    }

    /**
     * Modifie un post
     * @param  int $id ID du post a modifier
     */
    public function update ($id) {
        if(empty(App::$request->post['photo']) || App::$request->post['photo']['error'] == 4) unset(App::$request->post['photo']);

        $this->validate ($this->post, App::$request->post);
        App::$request->filterPost();
        App::$request->post['slug'] = $this->post->slug(App::$request->post['title']);
        if (isset(App::$request->post['photo'])) $this->post->moveFile('photo');
        
        $this->post->where('id',$id)->update(App::$request->post);
        App::$session->addMessage('success', 'Post bien modifié !');
        App::$response->redirect('/posts/'.$id);
    }

    /**
     * Supprime un post
     * @param  int $id Id du post à supprimer
     * @throws ForbbidenException Si les post n'appartient pas à l'utilisateur connecté
     */
    public function destroy ($id) {
        $post = $this->post->findFirst($id);
        if (empty($post) || Session::Auth()->id != $post->user_id) {
            throw new ForbbidenException("Vous ne pouvez pas supprimer ce post");
        }
        $this->post->last = [];
        $this->post->delete($id);
        if (App::$request->isJson()) App::$response->json(['success' => true,'message'=>'Post bien supprimé']);
        App::$session->addMessage('success', 'Post bien supprimé !');
        App::$response->redirect('/posts');
    }

    /**
     * Checher un post
     */
    public function search() {
        if (!isset(App::$request->get['q']) || empty(App::$request->get['q'])) $results = [];
        else $results = $this->post
            ->selectFillable()
            ->where('title','like','%'.App::$request->get['q'].'%')
            ->or('content','like','%'.App::$request->get['q'].'%')
            ->paginate()
            ->get();
        App::$response->view('posts.search',['results'=>$results]);
    }

}
