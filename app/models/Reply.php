<?php

class Reply extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'replies';

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
        'content' => 'textarea'
    ];

    /**
     * Restriction lors de l'insertion dans la base de donnée
     * @var Array
     */
    protected $validation = [
        'content' => ['required']
    ];

    /**
     * champs a proteger contre les failles sql
     * @var Array
     */
    protected $protected = ['content'];

    /**
     * Selectionn les champs voulu
     * @return Model
     */
    public function selectFillable() {
        return parent::select(['id'=> 'replies.id',
                      'pseudo'     => 'users.pseudo',
                      'content'    => 'replies.content',
                      'user_id'    => 'users.id',
                      'stars'      => 'stars',
                      'created_at' => 'replies.created_at',
                      'updated_at' => 'replies.updated_at']);
    }

}
