<?php
$api = app('Dingo\Api\Routing\Router');
/** @var \Laravel\Lumen\Routing\Router $router */



$api->version('v1', function ($api) {

    $api->post('login', [
        'uses' => 'App\Http\Controllers\UserController@login'
    ]);
    $api->post('register', [
        'as' => 'user.create',
        'uses' => 'App\Http\Controllers\UserController@register'
    ]);
    $api->get('logout', [
        'uses' => 'App\Http\Controllers\UserController@logout'
    ]);
    $api->group(['prefix' => '/user', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        
        $api->get('profile', [
            'as' => 'user.view',
            'uses' => 'UserController@showInfo'
        ]);
        $api->put('update', [
            'as' => 'user.update',
            'uses' => 'UserController@updateInfo'
        ]);
        $api->put('add-role/{id:[0-9]+}', [
            'as' => 'user.add-role',
            'uses' => 'UserController@updateRoles'
        ]);
        $api->get('listUsers', [
            'as' => 'user.view',
            'uses' => 'UserController@listUsers'
        ]);
        $api->get('detailUser/{id:[0-9]+}', [
            'as' => 'user.view',
            'uses' => 'UserController@detailUser'
        ]);
    });

    $api->group(['prefix' => '/role', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        $api->get('/', [
            'as' => 'role.view',
            'uses' => 'RoleController@index'
        ]);
        $api->get('/{id:[0-9]+}', [
            'as' => 'role.view',
            'uses' => 'RoleController@detail'
        ]);
        $api->post('/', [
            'as' => 'role.create',
            'uses' => 'RoleController@create'
        ]);
        $api->put('/{id:[0-9]+}', [
            'as' => 'role.update',
            'uses' => 'RoleController@update'
        ]);
        $api->delete('/{id:[0-9]+}', [
            'as' => 'role.delete',
            'uses' => 'RoleController@delete'
        ]);
        $api->put('addPermission/{roleId:[0-9]+}', [
            'as' => 'role.update',
            'uses' => 'RoleController@addPermissionForRoles'
        ]);
    });

    $api->group(['prefix' => '/permission', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        $api->get('/', [
            'as' => 'permission.view',
            'uses' => 'PermissionController@index'
        ]);
        $api->post('/', [
            'as' => 'permission.create',
            'uses' => 'PermissionController@create'
        ]);
        $api->put('/{id:[0-9]+}', [
            'as' => 'permission.update',
            'uses' => 'PermissionController@update'
        ]);
        $api->delete('/{id:[0-9]+}', [
            'as' => 'permission.delete',
            'uses' => 'PermissionController@delete'
        ]);
    });

    $api->group(['prefix' => '/service', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        $api->get('/', [
            'as' => 'service.view',
            'uses' => 'ServiceController@index'
        ]);
        $api->post('/', [
            'as' => 'service.create',
            'uses' => 'ServiceController@create'
        ]);
        $api->put('/{roleId:[0-9]+}', [
            'as' => 'service.update',
            'uses' => 'ServiceController@update'
        ]);
        // $api->delete('/{roleId:[0-9]+}', [
        //     'as' => 'service.delete',
        //     'uses' => 'ServiceController@delete'
        // ]);
    });

    $api->group(['prefix' => '/guild', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        $api->get('/', [
            'as' => 'guild.view',
            'uses' => 'GuildController@index'
        ]);
        $api->post('/', [
            'as' => 'guild.create',
            'uses' => 'GuildController@create'
        ]);
        $api->put('/{roleId:[0-9]+}', [
            'as' => 'guild.update',
            'uses' => 'GuildController@update'
        ]);
        // $api->delete('/{roleId:[0-9]+}', [
        //     'as' => 'guild.delete',
        //     'uses' => 'GuildController@delete'
        // ]);
    });

    $api->group(['prefix' => '/receiver', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        $api->get('/', [
            'as' => 'receiver.view',
            'uses' => 'ReceiverController@index'
        ]);
        $api->get('/{id:[0-9]+}', [
            'as' => 'receiver.view',
            'uses' => 'ReceiverController@detail'
        ]);
        $api->post('/', [
            'as' => 'receiver.create',
            'uses' => 'ReceiverController@create'
        ]);
        $api->put('/{id:[0-9]+}', [
            'as' => 'receiver.update',
            'uses' => 'ReceiverController@update'
        ]);
        $api->delete('/{id:[0-9]+}', [
            'as' => 'receiver.delete',
            'uses' => 'ReceiverController@delete'
        ]);
    });


    $api->group(['prefix' => '/tracking', 'namespace' => 'App\Http\Controllers',  'middleware' => ['auth']], function ($api) {
        $api->get('/', [
            'as' => 'tracking.view',
            'uses' => 'TrackingController@index'
        ]);
        $api->get('/{id:[0-9]+}', [
            'as' => 'tracking.view',
            'uses' => 'TrackingController@detail'
        ]);
        $api->post('/', [
            'as' => 'tracking.create',
            'uses' => 'TrackingController@create'
        ]);
        $api->put('/{id:[0-9]+}', [
            'as' => 'tracking.update',
            'uses' => 'TrackingController@update'
        ]);
        $api->delete('/{id:[0-9]+}', [
            'as' => 'tracking.delete',
            'uses' => 'TrackingController@delete'
        ]);
        $api->get('search', [
            'as' => 'tracking.search',
            'uses' => 'TrackingController@search'
        ]);
    });
});
