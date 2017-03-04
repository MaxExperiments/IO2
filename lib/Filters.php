<?php

trait Filters {

    function required ($name) {
        return !empty($name);
    }

    function max($name, $max) {
        return strlen($name) <= $max;
    }

}