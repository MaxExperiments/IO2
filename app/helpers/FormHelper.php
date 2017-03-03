<?php

class Form extends Helper {
    
    protected $model;

    protected $method;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function createForm ($method = 'GET', $attributes = []) {
        $this->method = $method;
        return Response::requireView('helpers.form.open', [
            'method'     => $method,
            'attributes' => $attributes
        ]);
    }

    public function input ($field, $label = '', $attributes = []) {
        $vars = ['label' => $label, 'attributes' => ['name' => $field, 'id' => [$field]]];
        if (array_key_exists($field, $this->model->attributes)) {
            $vars['attributes']['type'] = $this->model->attributes[$field];
        }
        $vars['attributes'] = array_merge_recursive($vars['attributes'],$attributes);
        return Response::requireView('helpers.form.input',$vars);
    }

    public function submit ($text) {
        return $this->input ('submit', '', [
            'type'=>'submit',
            'class' => ['button','primary']
        ]);
    }

    public function endForm()
    {
        return '</form>';
    }

}