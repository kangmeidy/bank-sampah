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


$routes->get('/sampah', 'Sampah::index', ['filter' => 'auth']);
$routes->get('/jenis', 'Jenis::index', ['filter' => 'auth']);
$routes->get('/setoran', 'Setoran::index', ['filter' => 'auth']);
$routes->get('/penjualan', 'Penjualan::index', ['filter' => 'auth']);

// 

