<?php

class User extends Model {

    protected $table = 'users';

    public $attributes = [
        'email' => 'email',
        'password' => 'password'
    ];

    public $validation = [
        'email' => ['required','min:3'],
        'password' => ['required','min:6']
    ];

}
