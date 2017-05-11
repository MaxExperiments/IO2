<?php

class RepliesController extends BaseController {

    protected $isModel = false;

    protected $models = ['Reply'];

    /**
     * Insert un reply
     */
    public function store () {
        $this->validate($this->reply,App::$request->post);
        App::$request->filterPost();
        App::$request->post['user_id'] = Session::Auth()->id;
        $this->reply->insert(App::$request->post);
        if (App::$request->isJson()) {
            $reply = $this->reply->selectFillable()
                                ->where('user_id',Session::Auth()->id)
                                ->where('post_id',App::$request->post['post_id'])
                                ->order('id','desc')
                                ->limit(false,1)
                                ->get();
            App::$response->json(['success'=>true,'message'=>'Commentaire bien ajouté','reply'=>$reply[0]]);
        }
        App::$response->referer();
    }

    /**
     * Supprime un reply
     * @param  int $id Id du post à supprimer
     * @throws ForbbidenException Si le post n'existe pas ou si l'utilisateur n'a aps les droits
     */
    public function destroy ($id) {
        $r = $this->reply->select(['user_id'=>'user_id'])->findFirst($id);
        if (empty($r) || Session::Auth()->id != $r->user_id) {
            throw new ForbbidenException("Vous ne pouvez pas supprimer ce commentaire");
        }
        $this->reply->last = [];
        $this->reply->delete($id);
        if (App::$request->isJson()) App::$response->json(['success'=>true,'message'=>'Commentaire bien supprimé']);
        App::$response->referer();
    }

    /**
     * Ajoute une étoile à un reply
     * @param  int $id Id du reply
     */
    public function star ($id) {
        $r = $this->reply->where('id',$id)->update(['!stars'=>'stars+1']);
        App::$response->referer();
    }

}
