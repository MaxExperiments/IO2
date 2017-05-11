<?php

class Helper {

    /**
     * Ajoute les attributs dans une balise html
     * @param  Array $data Les attributs et leurs valeures dans un tableau associatif
     * @return String       Code des attributs
     */
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