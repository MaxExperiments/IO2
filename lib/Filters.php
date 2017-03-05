<?php

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

}