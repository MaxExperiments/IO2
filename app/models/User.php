<?php

class User extends Model {

    protected $table = 'users';

    public $attributes = [
        'email' => 'email',
        'password' => 'password'
    ];

    protected $validation = [
        'email' => ['required','min:3'],
        'password' => ['required','min:6']
    ];

}
