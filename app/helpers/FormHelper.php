<?php

/**
 * Classe permettant de faire des formulaire dans les vues
 */
class Form extends Helper {

    /**
     * Model auquel est attache le formulaire
     * @var Model
     */
    protected $model;

    /**
     * Methode d'envoie du formulaire
     * @var [type]
     */
    protected $method;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * Charge une vue qui ouvre le formulaire
     * @param  string $method     La methode du formulaire
     * @param  array  $attributes Les attributs HTML du formulaire
     * @return String             Contenu de la vuew charge
     */
    public function createForm ($method = 'GET', $attributes = []) {
        $this->method = $method;
        return Response::requireView('helpers.form.open', [
            'method'     => $method,
            'attributes' => $attributes
        ]);
    }

    /**
     * Ajout un input au formulaire
     * @param  String $field      Nom du champ dans le model
     * @param  string $label      Text a afficher dans la label pour l'input
     * @param  array  $attributes Les attributs HTML de la balise input
     * @return String             Le contenu de la vue charge
     */
    public function input ($field, $label = '', $attributes = []) {
        $vars = ['label' => $label, 'attributes' => ['name' => $field, 'id' => [$field],'type'=>'text']];
        if (!empty($this->model->last) && property_exists($this->model->last[0],$field)) $vars['attributes']['value'] = $this->model->last[0]->$field;
        if (array_key_exists($field, $this->model->attributes)) $vars['attributes']['type'] = $this->model->attributes[$field];
        if (App::$request->session->isMessageWithName($field)) {
            $message = App::$request->session->getMessage($field);
            $vars['attributes']['value'] = $message[0];
            $vars['messages'] = array_slice($message,1);
        }
        $vars['attributes'] = array_replace_recursive($vars['attributes'],$attributes);
        return Response::requireView('helpers.form.input',$vars);
    }

    /**
     * Ajoute un boutton submit dans le formulaire
     * @param  String $text Contenu du boutton
     * @return String       Le contenu de la vue charge
     */
    public function submit ($text) {
        return $this->input ('submit', '', [
            'type'=>'submit',
            'class' => ['button','primary'],
            'value' => $text
        ]);
    }

    /**
     * Ferme le fomulaire
     * @return String Ne prend pas la peine d'ajouter une vue pour un balise fermante
     */
    public function endForm() {
        return '</form>';
    }

}
