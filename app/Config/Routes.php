<?php

use App\Controllers\Api\JastipController;
use App\Controllers\Api\UserController;
use App\Controllers\Frontend\Manage;
use App\Controllers\Migrate;
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->addPlaceholder('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


$routes->environment('development', static function ($routes) {
    $routes->get('migrate', [Migrate::class, 'index']);
    $routes->get('migrate/(:any)', [Migrate::class, 'execute']);
});

$routes->group('panel', static function (RouteCollection $routes) {
    $routes->get('', [Manage::class, 'index']);
    $routes->get('dashboard', [Manage::class, 'dashboard']);
    $routes->get('jastip', [Manage::class, 'jastip']);
    $routes->get('user', [Manage::class, 'user']);
});

$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->group('v2', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
        $routes->get('source/storage/(:any)', 'SourceController::storage/$1');
    });
    $routes->post('jastip', [JastipController::class, 'addJastip']);
    $routes->resource('jastip', ['namespace' => '', 'controller' => JastipController::class, 'websafe' => 1]);
    $routes->post('user/activate', [UserController::class, 'activate']);
    $routes->post('user/deactivate', [UserController::class, 'deactivate']);
    $routes->post('user/update/(:uuid)', [UserController::class, 'update']);
    $routes->resource('user', ['namespace' => '', 'controller' => UserController::class]);
});


service('auth')->routes($routes);
