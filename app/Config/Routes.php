<?php

use App\Controllers\Api\JastipController;
use App\Controllers\Api\PengaturanController;
use App\Controllers\Api\UserController;
use App\Controllers\Api\ShipmentController;
use App\Controllers\DemoController;
use App\Controllers\Frontend\Manage;
use App\Controllers\Frontend\PublicTrackingController;
use App\Controllers\Home;
use App\Controllers\Migrate;
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('tracking', [PublicTrackingController::class, 'index']);
$routes->get('shipment/(:num)/delivery-note', 'ShipmentController::deliveryNote/$1');
$routes->addPlaceholder('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


$routes->environment('development', static function ($routes) {
    $routes->get('migrate', [Migrate::class, 'index']);
    $routes->get('migrate/(:any)', [Migrate::class, 'execute']);
});

$routes->group('panel', static function (RouteCollection $routes) {
    $routes->get('', [Manage::class, 'index']);
    $routes->get('dashboard', [Manage::class, 'dashboard']);
    $routes->get('jastip', [Manage::class, 'jastip']);
    $routes->get('tracking', [Manage::class, 'tracking']);
    $routes->get('pengajuan', [Manage::class, 'pengajuan']);
    $routes->get('riwayat', [Manage::class, 'riwayat']);
    $routes->get('user', [Manage::class, 'user']);
    $routes->get('pengaturan', [Manage::class, 'pengaturan']);
    $routes->get('shipment', 'ShipmentController::index');
});

$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->group('v2', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
        $routes->post('register', [Home::class, 'register']);
        $routes->get('source/storage/(:any)', 'SourceController::storage/$1');
        $routes->get('jastip/track/(:any)', [JastipController::class, 'trackPackage']);
    });
    // buat route traking dengan parameter nomor resi
    $routes->resource('jastip', ['namespace' => '', 'controller' => JastipController::class, 'websafe' => 1]);
    $routes->resource('pengaturan', ['namespace' => '', 'controller' => PengaturanController::class, 'websafe' => 1]);

    // Shipment routes
    $routes->get('shipment/available-packages', [ShipmentController::class, 'availablePackages']);
    $routes->post('shipment/(:num)/add-packages', [ShipmentController::class, 'addPackages/$1']);
    $routes->delete('shipment/(:num)/remove-package/(:num)', [ShipmentController::class, 'removePackage/$1/$2']);
    $routes->post('shipment/(:num)/process', [ShipmentController::class, 'processShipment/$1']);
    $routes->get('shipment/(:num)/delivery-note', [ShipmentController::class, 'deliveryNote/$1']);
    $routes->resource('shipment', ['namespace' => '', 'controller' => ShipmentController::class, 'websafe' => 1]);

    $routes->post('user/activate', [UserController::class, 'activate']);
    $routes->post('user/deactivate', [UserController::class, 'deactivate']);
    $routes->post('user/update/(:uuid)', [UserController::class, 'update']);
    $routes->resource('user', ['namespace' => '', 'controller' => UserController::class]);
});


service('auth')->routes($routes);
