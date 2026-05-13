<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attempt');
$routes->get('logout', 'AuthController::logout');

$routes->group('employe', ['filter' => 'auth:employe'], static function ($routes) {
	$routes->get('/', 'EmployeController::dashboard');
	$routes->get('dashboard', 'EmployeController::dashboard');
	$routes->get('conges', 'EmployeController::index');
	$routes->get('conges/create', 'EmployeController::create');
	$routes->post('conges', 'EmployeController::store');
	$routes->post('conges/(:num)/cancel', 'EmployeController::cancel/$1');
	$routes->get('profil', 'EmployeController::profil');
	$routes->post('profil', 'EmployeController::updateProfil');
});

$routes->group('rh', ['filter' => 'auth:rh'], static function ($routes) {
	$routes->get('/', 'RhController::dashboard');
	$routes->get('dashboard', 'RhController::dashboard');
	$routes->get('demandes', 'RhController::index');
});

$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
	$routes->get('/', 'AdminController::dashboard');
	$routes->get('dashboard', 'AdminController::dashboard');
	$routes->get('employes', 'AdminController::employes');
	$routes->get('departements', 'AdminController::departements');
	$routes->get('types-conge', 'AdminController::typesConges');
	$routes->get('soldes', 'AdminController::soldes');
});
