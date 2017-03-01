<?php

/**
 * Fichier contenant toutes les variables spécifiques à l'application
 */

define('CONFIG',[

    /**
     * Décrit l'état de l'application
     *  - dev en développement, afficheras les erreurs php pour débugger
     *  - prod en production, cacheras les erreurs php dans des logs
     */
    'env' => 'dev',

    /**
     * Défini les cléfs de connection à la base de donnée utilisé
     */
    'database' => [
        'driver'   => 'mysql',
        'host'     => 'localhost',
        'username' => 'homestead',
        'password' => 'secret',
        'port'     => '',
        'database' => 'homestead'
    ]

]);