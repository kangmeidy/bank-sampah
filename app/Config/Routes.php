<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Login & logout
$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');
$routes->get('/logout', 'Login::logout');

// Halaman yang dilindungi auth
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/nasabah', 'Nasabah::index', ['filter' => 'auth']);
$routes->get('/sampah', 'Sampah::index', ['filter' => 'auth']);
$routes->get('/laporan/saldo-nasabah', 'Laporan::saldoNasabah', ['filter' => 'auth']);
$routes->get('/penjualan-detail/belum-ada', 'PenjualanDetail::belumAda', ['filter' => 'auth']);

// Optional pages (placeholders)

$routes->get('/jenis', 'Jenis::index', ['filter' => 'auth']);
$routes->get('/penjualan', 'Penjualan::index', ['filter' => 'auth']);
$routes->get('/tarikdana', 'Tarikdana::index', ['filter' => 'auth']);

// Setoran routes
// $routes->get('/setoran', 'Setoran::create', ['filter' => 'auth']);            // This is the main form
// $routes->get('/setoran/create', 'Setoran::create', ['filter' => 'auth']);
// $routes->post('/setoran/store', 'Setoran::store', ['filter' => 'auth']);
// $routes->get('/setoran/edit/(:any)', 'Setoran::edit/$1', ['filter' => 'auth']);
// $routes->post('/setoran/update/(:any)', 'Setoran::update/$1', ['filter' => 'auth']);
// $routes->get('/setoran/delete/(:any)', 'Setoran::delete/$1', ['filter' => 'auth']);
// $routes->get('/setoran/detail', 'Setoran::detail', ['filter' => 'auth']);

// $routes->get('/setoran/cetak/(:any)', 'Setoran::cetakBukti/$1', ['filter' => 'auth']);



$routes->get('/setoran', 'Setoran::create', ['filter' => 'auth']);
$routes->get('/setoran/create', 'Setoran::create', ['filter' => 'auth']);
$routes->post('/setoran/store', 'Setoran::store', ['filter' => 'auth']);
$routes->get('/setoran/edit/(:any)', 'Setoran::edit/$1', ['filter' => 'auth']);
$routes->post('/setoran/update/(:any)', 'Setoran::update/$1', ['filter' => 'auth']);
$routes->get('/setoran/delete/(:any)', 'Setoran::delete/$1', ['filter' => 'auth']);
$routes->get('/setoran/detail', 'Setoran::detail', ['filter' => 'auth']);
$routes->get('/setoran/cetak/(:any)', 'Setoran::cetakBukti/$1', ['filter' => 'auth']);




// Tarik dana nasabah
$routes->get('/tarikdana', 'TarikDana::index', ['filter' => 'auth']);
$routes->get('/tarikdana/create', 'TarikDana::create', ['filter' => 'auth']);
$routes->post('/tarikdana/store', 'TarikDana::store', ['filter' => 'auth']);
$routes->get('/tarikdana/edit/(:any)', 'TarikDana::edit/$1', ['filter' => 'auth']);
$routes->post('/tarikdana/update/(:any)', 'TarikDana::update/$1', ['filter' => 'auth']);
$routes->get('/tarikdana/delete/(:any)', 'TarikDana::delete/$1', ['filter' => 'auth']);
$routes->get('/tarikdana/cetak/(:any)', 'TarikDana::cetakBukti/$1', ['filter' => 'auth']);



$routes->get('/tarikdana/getSaldo/(:any)', 'TarikDana::getSaldo/$1', ['filter' => 'auth']);