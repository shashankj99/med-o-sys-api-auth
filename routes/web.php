<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () {
    return response()->json([
        'status' => 200,
        'success' => true
    ], 200);
});

/*
 * Role routes
 */
$router->get('roles', [
    'uses' => 'RoleController@index'
]);

$router->post('role', [
    'uses' => 'RoleController@store'
]);

$router->get('role/{id:[0-9]+}', [
    'uses' => 'RoleController@show'
]);

$router->put('role/{id:[0-9]+}', [
    'uses' => 'RoleController@update'
]);

$router->delete('role/{id:[0-9]+}', [
    'uses' => 'RoleController@destroy'
]);
