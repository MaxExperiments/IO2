<?php

class BaseController extends Controller {

    protected $layout = 'basics';

    protected $isModel = true;

    protected $autoLoadHelpers = ['Html'];

    public function __construct(Array $params = []) {
        if ($this->isModel) $this->models[] = str_replace('sController', '', get_class($this));
        parent::__construct($params);
    }

    protected function validate (Model $model, $values) {
        if(!$model->validate($values)) {
            foreach ($values as $field => $value) {
                $message = [$value];
                if(array_key_exists($field, $model->messages)) $message[] = $model->messages[$field];
                App::$session->addMessage($field, $message);
            }
            App::$response->referer();
        }
    }


}
