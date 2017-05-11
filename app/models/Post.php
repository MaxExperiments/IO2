<?php

class Post extends Model {

    /**
     * Nombre de posts par page pour la pagination
     * @var integer
     */
    public static $posts_per_page = 5;

    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'posts';

    /**
     * appartient à un utilisateur avec la clef `user_id`
     * @var Array
     */
    protected $belongsTo = [
        'users' => ['user_id'=>'id']
    ];

    /**
     * Type de champs dans les formulaires
     * @var Array
     */
    public $attributes = [
        'title'    => 'text',
        'content' => 'textarea',
        'photo' => 'file'
    ];

    /**
     * Restriction lors de l'insertion dans la base de donnée
     * @var Array
     */
    public $validation = [
        'title' => ['required','max:50'],
        'content' => ['required'],
        'photo' => ['isImage','maxImageSize:50000000000','fileType:jpg,png,jpeg,gif']
    ];

    /**
     * champs a proteger contre les failles sql
     * @var Array
     */
    protected $protected = ['title','content'];

    /**
     * Selectionn les champs voulu
     * @return Model
     */
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

    /**
     * Insert la limite des champs pour la pagination
     * @return Model
     */
    public function paginate () {
        $page = (!isset(App::$request->get['page'])) ? 1 : App::$request->get['page'];
        return parent::limit(($page-1)*self::$posts_per_page, self::$posts_per_page);
    }

    /**
     * Ajoute un fichier
     * @param  String $field Nom du fichier dans la requete
     */
    public function moveFile ($field) {
        $path = 'imgs' . DS .  'posts' . DS . time() . rand(0,100) . '.' . pathinfo(App::$request->post[$field]['name'], PATHINFO_EXTENSION);
        move_uploaded_file(App::$request->post[$field]['tmp_name'], PUBLIC_DIR . $path);
        App::$request->post[$field] = '/' . $path;
    }

}
