<?php

/**
 * Routes
 * défini ce qu'il faut faire pour chaque url
 */

App::$route->setPattern('id','[0-9]+');

App::$route->get ('/','PostsController@home');

App::$route->get ('/posts', 'PostsController@index');
App::$route->get ('/posts/{id}', 'PostsController@show');
App::$route->get ('/posts/create', 'PostsController@create');
App::$route->put ('/posts/create', 'PostsController@store');
App::$route->get ('/posts/{id}/edit', 'PostsController@edit');
App::$route->post ('/posts/{id}/update', 'PostsController@update');
App::$route->delete ('/post/{id}', 'PostsController@destroy');