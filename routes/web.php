<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

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

/* ******************************************
 * Role & Permission assignment Routes
 * ******************************************/
$router->post('assign-permissions-to-role', [
    'uses' => 'AssignRoleAndPermissionController@assignPermissionsToRole'
]);

$router->post('assign-roles-to-permission', [
    'uses' => 'AssignRoleAndPermissionController@assignRolesToPermission'
]);

/* ******************************************
 * Province Routes
 * ******************************************/
$router->get('provinces', [
    'uses' => 'ProvinceController@index'
]);

$router->post('province', [
    'uses' => 'ProvinceController@store'
]);

$router->get('province/{id:[0-9]+}', [
    'uses' => 'ProvinceController@show'
]);

$router->put('province/{id:[0-9]+}', [
    'uses' => 'ProvinceController@update'
]);

$router->delete('province/{id:[0-9]+}', [
    'uses' => 'ProvinceController@destroy'
]);

/* ******************************************
 * District Routes
 * ******************************************/
$router->get('districts', [
    'uses' => 'DistrictController@index'
]);

$router->group(['prefix' => 'province/{provinceId:[0-9]+}'], function () use ($router) {
    $router->post('district', [
        'uses' => 'DistrictController@store'
    ]);

    $router->get('district/{id:[0-9]+}', [
        'uses' => 'DistrictController@show'
    ]);

    $router->put('district/{id:[0-9]+}', [
        'uses' => 'DistrictController@update'
    ]);

    $router->delete('district/{id:[0-9]+}', [
        'uses' => 'DistrictController@destroy'
    ]);
});

/* ******************************************
 * City Routes
 * ******************************************/
$router->get('cities', [
    'uses' => 'CityController@index'
]);

$router->group(['prefix' => 'district/{districtId:[0-9]+}'], function () use ($router) {
    $router->post('city', [
        'uses' => 'CityController@store'
    ]);

    $router->get('city/{id:[0-9]+}', [
        'uses' => 'CityController@show'
    ]);

    $router->put('city/{id:[0-9]+}', [
        'uses' => 'CityController@update'
    ]);

    $router->delete('city/{id:[0-9]+}', [
        'uses' => 'CityController@destroy'
    ]);
});
