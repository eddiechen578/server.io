<?php

function dd($data)
{
    echo '<pre>' . var_export($data, true) . '</pre>';
}
/**
 * Front controller
 *
 * PHP version 7.3
 */

/**
 * Composer
 */
require '../vendor/autoload.php';

//$pdo = (new \Lib\Pdo(\Config\Database::settings()['vue_app']));
//dd($pdo->fetchColumn('SELECT @@wait_timeout;'));
//exit;
/**
 * Twig
 */
//Twig_Autoloader::register();

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Lib\Error::errorHandler');
set_exception_handler('Lib\Error::exceptionHandler');


\App\Models\Log\DB::$table = \App\Models\Log\DB::$database . '.' . date('Ymd');

/**
 * Routing
 */
$router = new Core\Router();
// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('api/{controller}/{action}');

$router->add('api/{controller}/{id:\d+}/{action}');

$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);


$router->dispatch($_SERVER['QUERY_STRING']);
