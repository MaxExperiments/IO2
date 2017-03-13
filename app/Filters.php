<?php

namespace Model {

/**
 * Trait contenant la collection de tous les filtres utilisables dans les models
 */
trait Filters {

    /**
     * Vérifie sir le champ n'est pas vide
     * @param  String $val Valeure du champ
     * @return Boolean
     */
    function required ($val) {
        return !empty($val);
    }

    /**
     * Verifie que la longeure du champ n'excede pas un entier donne
     * @param  String $val Valeur du champ
     * @param  int $max    Longueure maximale
     * @return Boolean
     */
    function max($val, $max) {
        return strlen($val) <= $max;
    }

    /**
     * Vérifie que la longuere du champ n'est pas trop petite
     * @param  String $val Valeure du champ
     * @param  int $min Taille minimum
     * @return Boolean
     */
    function min ($val, $min) {
        return strlen($val) >= $min;
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

}

}
