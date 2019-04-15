<?php

/**
 * $router \Laravel\Lumen\Routing\Router
 */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('send_mail', 'UserController@sendMail');
$router->get('send_notification', 'UserController@sendNotification');
