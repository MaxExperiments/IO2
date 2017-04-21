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

    public function selectFillable() {
        return parent::select(['id'=> 'replies.id',
                      'pseudo'     => 'users.pseudo',
                      'content'    => 'replies.content',
                      'user_id'    => 'users.id',
                      'created_at' => 'replies.created_at',
                      'updated_at' => 'replies.updated_at']);
    }

}
