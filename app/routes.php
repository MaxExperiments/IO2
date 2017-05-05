<?php

/**
 * Routes
 * défini ce qu'il faut faire pour chaque url
 */

App::$route->setPattern('id','[0-9]+');

App::$route->get ('/','PostsController@home');

App::$route->get ('/posts', 'PostsController@index');
App::$route->get ('/posts/{id}', 'PostsController@show');

App::$route->get('/search','PostsController@search');

App::$route->filter(['!authenticate'], function () {
    App::$route->get ('/login', 'UsersController@login');
    App::$route->post ('/login', 'UsersController@connect');
    App::$route->get ('/register', 'UsersController@register');
    App::$route->post ('/register','UsersController@store');
}, function () {
    App::$route->get ('/posts/create', 'PostsController@create');
    App::$route->put ('/posts/create', 'PostsController@store');
    App::$route->get ('/posts/{id}/edit', 'PostsController@edit');
    App::$route->post ('/posts/{id}/edit', 'PostsController@update');
    App::$route->delete ('/posts/{id}/delete', 'PostsController@destroy');

    App::$route->put ('/replies', 'RepliesController@store');
    App::$route->get ('/replies/{id}/star', 'RepliesController@star');
    App::$route->delete('/replies/{id}/delete', 'RepliesController@destroy');

    App::$route->get('/users/', 'UsersController@index');
    App::$route->post('/users/', 'UsersController@update');
    App::$route->get('/users/{id}', 'UsersController@show');

    App::$route->get ('/logout', 'UsersController@logout');
});
