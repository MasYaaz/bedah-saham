<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->addRedirect('/', 'stock'); // redirect ke stock
$routes->get('stock', 'StockController::index'); // Halaman Chart IHSG
$routes->get('stock/history/(:any)/(:any)', 'StockController::get_history/$1/$2');
$routes->get('stock/list', 'StockController::list'); // Halaman Tabel Emiten
$routes->get('stock/detail/(:segment)', 'StockController::detail/$1'); // Halaman Rincian per Emiten
$routes->get('stock/get_live_data', 'StockController::get_live_data');
$routes->get('stock/analyze-ai/(:any)', 'StockController::analyze_with_ai/$1');