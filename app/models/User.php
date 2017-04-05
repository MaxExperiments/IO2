<?php

class User extends Model {

    protected $table = 'users';

    public $attributes = [
        'email' => 'email',
        'password' => 'password',
        'pseudo' => 'text'
    ];

    public $validation = [
        'email' => ['required','min:3'],
        'password' => ['required','min:6'],
        'pseudo' => ['required']
    ];

}
