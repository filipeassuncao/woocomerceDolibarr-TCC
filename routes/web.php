<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('register', 'AuthController@register');

$router->post('login', 'AuthController@login');

$router->post('webhook-product', ['uses' => 'ProductController@create']);

$router->post('webhook-customer', ['uses' => 'CustomerController@create']);

$router->post('webhook-order', ['uses' => 'OrderController@create']);

