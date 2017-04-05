<?php

class Reply extends Model {

    protected $table = 'replies';

    public $attributes = [
        'content' => 'textarea'
    ];

    protected $validation = [
        'content' => ['required']
    ];


}
