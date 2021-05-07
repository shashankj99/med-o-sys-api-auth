<?php

use Illuminate\Support\Facades\URL;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */

if (env('APP_ENV') == 'production')
    URL::forceScheme('https');

$router->get('/', function () {
    return response()->json([
        'status' => 200,
        'success' => true
    ], 200);
});

/* ******************************************
 * Role routes
 * ******************************************/
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

/* ******************************************
 * Permission Routes
 * ******************************************/
$router->get('permissions', [
    'uses' => 'PermissionController@index'
]);

$router->post('permission', [
    'uses' => 'PermissionController@store'
]);

$router->get('permission/{id:[0-9]+}', [
    'uses' => 'PermissionController@show'
]);

$router->put('permission/{id:[0-9]+}', [
    'uses' => 'PermissionController@update'
]);

$router->delete('permission/{id:[0-9]+}', [
    'uses' => 'PermissionController@destroy'
]);
