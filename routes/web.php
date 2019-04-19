<?php


/** @var $router Laravel\Lumen\Routing\Router */

$router->group(['prefix' => 'api'], function ($router) {

    /** @var $router Laravel\Lumen\Routing\Router */
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->post('mail', 'UserController@mail');
    $router->get('notification', 'UserController@notification');
    $router->post('notification', 'UserController@notification');
});
