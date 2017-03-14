<?php

/**
 * Routes
 * défini ce qu'il faut faire pour chaque url
 */

App::$route->setPattern('id','[0-9]+');

App::$route->get ('/','PostsController@home');

App::$route->get ('/posts', 'PostsController@index');
App::$route->get ('/posts/{id}', 'PostsController@show');

App::$route->filter(['!authenticate'], function () {
    App::$route->get ('/login', 'UsersController@login');
    App::$route->post ('/login', 'UsersController@connect');
    App::$route->get ('/register', 'UsersController@register');
    App::$route->post ('/register','UsersController@store');
});

App::$route->filter(['authenticate'], function () {
    App::$route->get ('/posts/create', 'PostsController@create');
    App::$route->put ('/posts/create', 'PostsController@store');
    App::$route->get ('/posts/{id}/edit', 'PostsController@edit');
    App::$route->post ('/posts/{id}/edit', 'PostsController@update');
    App::$route->get ('/posts/{id}/delete', 'PostsController@destroy');

    App::$route->get ('/logout', 'UsersController@logout');
});
