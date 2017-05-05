<?php

class Reply extends Model {

    protected $table = 'replies';

    protected $belongsTo = [
        'users' => ['user_id'=>'id']
    ];

    public $attributes = [
        'content' => 'textarea'
    ];

    protected $validation = [
        'content' => ['required']
    ];

    protected $protected = ['content'];

    public function selectFillable() {
        return parent::select(['id'=> 'replies.id',
                      'pseudo'     => 'users.pseudo',
                      'content'    => 'replies.content',
                      'user_id'    => 'users.id',
                      'stars'      => 'stars',
                      'created_at' => 'replies.created_at',
                      'updated_at' => 'replies.updated_at']);
    }

}
