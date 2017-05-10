<?php

class Post extends Model {

    public static $posts_per_page = 5;

    protected $table = 'posts';

    protected $belongsTo = [
        'users' => ['user_id'=>'id']
    ];

    public $attributes = [
        'title'    => 'text',
        'content' => 'textarea',
        'photo' => 'file'
    ];

    public $validation = [
        'title' => ['required','max:50'],
        'content' => ['required'],
        'photo' => ['isImage','maxImageSize:50000000000','fileType:jpg,png,jpeg,gif']
    ];

    protected $protected = ['title','content'];

    public function selectFillable () {
        return parent::select ([
            'id' => 'posts.id',
            'title' => 'posts.title',
            'content' => 'posts.content',
            'created_at' => 'posts.created_at',
            'updated_at' => 'posts.updated_at',
            'user_id' => 'posts.user_id',
            'pseudo' => 'users.pseudo',
            'photo' => 'posts.photo',
            'user_photo' => 'users.photo'
        ]);
    }

    public function paginate () {
        $page = (!isset(App::$request->get['page'])) ? 1 : App::$request->get['page'];
        return parent::limit(($page-1)*self::$posts_per_page, self::$posts_per_page);
    }

    public function moveFile ($field) {
        $path = 'imgs' . DS .  'posts' . DS . time() . rand(0,100) . '.' . pathinfo(App::$request->post[$field]['name'], PATHINFO_EXTENSION);
        move_uploaded_file(App::$request->post[$field]['tmp_name'], PUBLIC_DIR . $path);
        App::$request->post[$field] = '/' . $path;
    }

}
