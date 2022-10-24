<?php

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


/**
 * @var $router \Laravel\Lumen\Routing\Router
 */

$router->get('/', 'HomeController@home');

/*webhook*/
$router->group([
    'prefix' => 'webhook',
    'middleware' => ['json.result'],
], function($router) {
    /**
     * @var $router \Laravel\Lumen\Routing\Router
     */

    $router->post('{project}/callback', 'WebhookController@callback');
    $router->get('{project}/callback', 'WebhookController@callback');
});
