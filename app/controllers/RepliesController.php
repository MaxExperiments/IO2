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

}
