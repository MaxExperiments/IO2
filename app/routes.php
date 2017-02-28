<?php

/**
 * Routes
 * défini ce qu'il fait faire pour chaque url
 */

App::$route->get ('/','PostsController@home');

App::$route->err ('404','ErrorController@err404');