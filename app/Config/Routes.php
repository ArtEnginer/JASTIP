<?php

use App\Controllers\Api\DetailTransaksiController;
use App\Controllers\Api\KategoriController;
use App\Controllers\Api\ProdukController;
use App\Controllers\Api\TransaksiController;
use App\Controllers\Api\UserController;
use App\Controllers\Frontend\Manage;
use App\Controllers\Migrate;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;


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
    // halaman untuk transaksi barang
    $routes->get('transaksi-barang', [Manage::class, 'transaksiBarang']);
    $routes->get('produk', [Manage::class, 'produk']);
    $routes->get('kategori', [Manage::class, 'kategori']);
    $routes->get('transaksi', [Manage::class, 'transaksi']);
    $routes->get('detail-transaksi', [Manage::class, 'detailTransaksi']);
    $routes->get('user', [Manage::class, 'user']);
});

$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->group('v2', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
        $routes->post('register', [Home::class, 'register']);
        $routes->get('produk', 'ProdukController::index');
        $routes->get('source/storage/(:any)', 'SourceController::storage/$1');
    });

    $routes->resource('produk', ['namespace' => '', 'controller' => ProdukController::class, 'websafe' => 1]);
    $routes->resource('kategori', ['namespace' => '', 'controller' => KategoriController::class, 'websafe' => 1]);

    $routes->post('transaksi/save', [TransaksiController::class, 'saveTransaksi']);
    $routes->post('transaksi/update-status', [TransaksiController::class, 'updateStatus']);
    $routes->resource('transaksi', ['namespace' => '', 'controller' => TransaksiController::class, 'websafe' => 1]);
    $routes->resource('detail-transaksi', ['namespace' => '', 'controller' => DetailTransaksiController::class, 'websafe' => 1]);


    // midtrans / token
    $routes->post('midtrans/token', [TransaksiController::class, 'midtransToken']);
    $routes->post('midtrans/notification', [TransaksiController::class, 'midtransNotification']);
    $routes->post('user/activate', [UserController::class, 'activate']);
    $routes->post('user/deactivate', [UserController::class, 'deactivate']);
    $routes->post('user/update/(:uuid)', [UserController::class, 'update']);
    $routes->resource('user', ['namespace' => '', 'controller' => UserController::class]);
});


service('auth')->routes($routes);
