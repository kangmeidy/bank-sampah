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
$routes->get('/laporan/saldo-nasabah', 'Laporan::saldoNasabah', ['filter' => 'auth']);
$routes->get('/penjualan-detail/belum-ada', 'PenjualanDetail::belumAda', ['filter' => 'auth']);

// Optional pages (placeholders)
$routes->get('/sampah', 'Sampah::index', ['filter' => 'auth']);
$routes->get('/jenis', 'Jenis::index', ['filter' => 'auth']);
$routes->get('/penjualan', 'Penjualan::index', ['filter' => 'auth']);
$routes->get('/tarikdana', 'Tarikdana::index', ['filter' => 'auth']);

// Setoran routes
$routes->get('/setoran', 'Setoran::create', ['filter' => 'auth']);            // This is the main form
$routes->get('/setoran/create', 'Setoran::create', ['filter' => 'auth']);
$routes->post('/setoran/store', 'Setoran::store', ['filter' => 'auth']);
$routes->get('/setoran/edit/(:any)', 'Setoran::edit/$1', ['filter' => 'auth']);
$routes->post('/setoran/update/(:any)', 'Setoran::update/$1', ['filter' => 'auth']);
$routes->get('/setoran/delete/(:any)', 'Setoran::delete/$1', ['filter' => 'auth']);
$routes->get('/setoran/detail', 'Setoran::detail', ['filter' => 'auth']);