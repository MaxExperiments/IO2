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


}
