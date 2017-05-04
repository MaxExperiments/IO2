<?php

class Post extends Model {

    protected $table = 'posts';

    protected $belongsTo = [
        'users' => ['user_id'=>'id']
    ];

    public $attributes = [
        'title'    => 'text',
        'content' => 'textarea'
    ];

    public $validation = [
        'title' => ['required','max:20'],
        'content' => ['required']
    ];

    protected $protected = ['title','content'];

    public function selectFillable () {
        return parent::select ([
            'id' => 'posts.id',
            'title' => 'posts.title',
            'content' => 'posts.content',
            'created_at' => 'posts.created_at',
            'updated_at' => 'posts.updated_at',
            'user_id' => 'posts.user_id',
            'pseudo' => 'users.pseudo',
            'photo' => 'users.photo'
        ]);
    }

}
