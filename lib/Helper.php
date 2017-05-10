<?php

class Helper {

    public static function insertAsAttr($data) {
        $str = '';
        foreach ($data as $k => $v){
            $str .= $k .'="';
            if (is_array($v)) {
            foreach ($v as $l) $str .= $l . ' ';
            } else $str .= $v;
            $str .= '" ';
        }
        return trim($str);
    }
    
}