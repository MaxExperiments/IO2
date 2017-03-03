<?php

class Post extends Model {

    protected $table = 'posts';

    public $attributes = [
        'name'    => 'text',
        'content' => 'textarea'
    ];

}