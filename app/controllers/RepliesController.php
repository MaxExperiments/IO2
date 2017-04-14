<?php

class RepliesController extends BaseController {

    protected $isModel = false;

    protected $models = ['Reply'];

    public function store () {
        $this->validate($this->reply,App::$request->post);
        App::$request->filterPost();
        App::$request->post['user_id'] = Session::Auth()->id;
        $this->reply->insert(App::$request->post);
        App::$response->referer();
    }

    public function destroy ($id) {
        $r = $this->reply->select(['user_id'=>'user_id'])->findFirst($id);
        if (empty($r) || Session::Auth()->id != $r->user_id) {
            throw new ForbbidenException("Vous ne pouvez pas supprimer ce commantaire");
        }
        $this->reply->last = [];
        $this->reply->delete($id);
        if (App::$request->isJson()) App::$response->json(['success'=>true,'message'=>'Commentaire bien supprimé']);
        App::$response->referer();
    }

}
