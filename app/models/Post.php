<?php

class Post extends Model {

    protected $table = 'posts';

    public $attributes = [
        'title'    => 'text',
        'content' => 'textarea'
    ];

    public $validation = [
        'title' => ['required','max:20'],
        'content' => ['required']
    ];

}
