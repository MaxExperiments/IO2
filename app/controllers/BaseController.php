<?php

class BaseController extends Controller {

    protected $layout = 'basics';

    protected $isModel = true;

    public function __construct(Array $params = []) {
        if ($this->isModel) $this->models[] = str_replace('sController', '', get_class($this));
        parent::__construct($params);
    }

    
}