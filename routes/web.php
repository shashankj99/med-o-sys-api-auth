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
 * Auth Routes
 * ******************************************/
$router->post('register', [
    'uses' => 'AuthController@register'
]);

$router->post('mobile/verify', [
    'uses' => 'AuthController@verifyOtp'
]);

$router->post('email/verify', [
    'uses' => 'AuthController@verifyToken'
]);

$router->post('login', [
    'uses' => 'AuthController@login'
]);

$router->post('send/reset/password/link', [
    'uses' => 'AuthController@sendPasswordResetLink'
]);

$router->post('otp/reset', [
    'uses' => 'AuthController@resetPasswordViaOTP'
]);

$router->post('email/reset', [
    'uses' => 'AuthController@resetPasswordViaEmail'
]);

$router->post('check/reset/password/verification', [
    'uses' => 'AuthController@checkResetPasswordVerification'
]);

$router->post('/password/reset', [
    'uses' => 'AuthController@resetPassword'
]);

$router->get('provinces', [
    'uses' => 'ProvinceController@index'
]);

$router->get('districts', [
    'uses' => 'DistrictController@index'
]);

$router->get('cities', [
    'uses' => 'CityController@index'
]);

$router->get('get/address', 'ProvinceController@getLocationInfo');

$router->group(['middleware' => 'auth'], function () use ($router) {
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

    $router->post('assign-roles-to-user', [
        'uses' => 'AssignRoleAndPermissionController@assignRolesToUser'
    ]);

    /* ******************************************
     * Province Routes
     * ******************************************/
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

    /* ******************************************
     * User Routes
     * ******************************************/
    $router->get('users', 'UserController@index');
    $router->get('user', 'UserController@getUserByAccessToken');
    $router->get('user/{id:[0-9]+}', 'UserController@show');
    $router->put('user/{id:[0-9]+}', 'UserController@updateUser');
    $router->put('profile', 'UserController@updateProfile');
    $router->delete('user/{id:[0-9]+}', 'UserController@deleteUser');
    $router->get('user/serialize', 'UserController@get_serialized_user');
    $router->get('/user/permission/check', 'UserController@check_user_permission');

    /* ******************************************
     * add hospital to user routes
     * ******************************************/
    $router->post('/add/hospital/to/user', 'HospitalUserController@add_hospital_to_user');
    $router->get('/show/hospital/associated/to/user/{id:[0-9]+}', [
        'uses' => 'HospitalUserController@show_hospital_associated_to_user'
    ]);
    $router->put('/update/hospital/associated/to/user/{id:[0-9]+}', [
        'uses' => 'HospitalUserController@update_hospital_associated_to_user'
    ]);
    $router->delete('/delete/hospital/associated/to/user/{id:[0-9]+}', [
        'uses' => 'HospitalUserController@delete_hospital_associated_to_user'
    ]);
});
