<?php

class User extends Model {

    protected $table = 'users';

    public $attributes = [
        'email' => 'email',
        'password' => 'password',
        'pseudo' => 'text',
        'photo' => 'file'
    ];

    public $validation = [
        'email' => ['required','min:3'],
        'password' => ['required','min:6'],
        'pseudo' => ['required','match:/^[a-zA-Z0-9]+$/'],
        'photo' => ['isImage','maxImageSize:50000000000','fileType:jpg,png,jpeg,gif']
    ];

    public function moveFile ($field) {
        $path = 'imgs' . DS . 'users' . DS . time() . rand(0,100) . '.' . pathinfo(App::$request->post[$field]['name'], PATHINFO_EXTENSION);
        move_uploaded_file(App::$request->post[$field]['tmp_name'], PUBLIC_DIR . $path);
        App::$request->post[$field] = '/' . $path;
    }

}
