<?php

namespace Model {

/**
 * Trait contenant la collection de tous les filtres utilisables dans les models
 * Tous les filtres du model prennent en premier paramètre le nom du champ, en second sa valeure.
 * Les paramètres supplémentaires qui peuvent exister sont spécifiquent a chaque filtres
 */
trait Filters {

    /**
     * Vérifie si le champ n'est pas vide
     * @param  String $field  Nom du champ
     * @param  String $val   Valeure du champ
     * @return Boolean
     */
    function required ($field, $val) {
        return !empty($val);
    }

    /**
     * Verifie que la longueur du champ n'excede pas un entier donne
     * @param  String $field  Nom du champ
     * @param  String $val Valeur du champ
     * @param  int $max    Longueure maximale
     * @return Boolean
     */
    function max($field, $val, $max) {
        return strlen($val) <= $max;
    }

    /**
     * Vérifie que la longueur du champ n'est pas trop petite
     * @param  String $field Nom du champ
     * @param  String $val   Valeure du champ
     * @param  int $min      Taille minimum
     * @return Boolean
     */
    function min ($field, $val, $min) {
        return strlen($val) >= $min;
    }

    /**
     * Vérifie que la valeure n'existe pas déja pour ce champ
     * @param  String $field Nom du champ
     * @param  String $val   Valeure du champ
     * @return Boolean
     */
    function unique ($field, $val) {
        $ret = $this->where($field, $val)->count() == 0;
        $this->last = [];
        return $ret;
    }

    function match ($fiels, $val, $regex) {
        return preg_match($regex,$val);
    }

}

}

namespace Router {

trait Filters {

    /**
     * Teste la connection d'un utilisateur
     * @return Boolean True si un utilisateur est connecté dans la session
     */
    function authenticate () {
        return \Session::isAuthenticate();
    }

    function json () {
        return \App::$request->getContentType() == 'application/json';
    }

}

}
