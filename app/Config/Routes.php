<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Routes d'authentification
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attempt');
$routes->get('/logout', 'AuthController::logout');

// Groupe pour les employés
$routes->group('employe', ['filter' => 'auth:employe,rh,admin'], static function ($routes) {
    $routes->get('/', 'EmployeController::index');
});

// Groupe pour les RH
$routes->group('rh', ['filter' => 'auth:rh,admin'], static function ($routes) {
    $routes->get('/', 'RhController::index');
});

// Groupe pour les administrateurs
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('/', 'AdminController::index');
});
