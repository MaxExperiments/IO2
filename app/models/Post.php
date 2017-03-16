<?php

class Post extends Model {

    protected $table = 'posts';

    public $attributes = [
        'name'    => 'text',
        'content' => 'textarea'
    ];

    public $validation = [
        'name' => ['required','max:20'],
        'content' => ['required']
    ];

}
