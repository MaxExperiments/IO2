<?php

class Post extends Model {

    protected $table = 'posts';

    public $attributes = [
        'name'    => 'text',
        'content' => 'textarea'
    ];

    protected $validation = [
        'name' => ['required','max:20','unique'],
        'content' => ['required']
    ];

}
