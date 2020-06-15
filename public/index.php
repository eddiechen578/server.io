<?php

/**
 * Front controller
 *
 * PHP version 7.3
 */

/**
 * Composer
 */
require '../vendor/autoload.php';


if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
     {
        array_map(function ($value) {
            if (class_exists( \Symfony\Component\VarDumper\Dumper\CliDumper::class)) {

                $dumper = 'cli' === PHP_SAPI ?
                    new \Symfony\Component\VarDumper\Dumper\CliDumper :
                    new \Symfony\Component\VarDumper\Dumper\HtmlDumper;
                $dumper->dump((new \Symfony\Component\VarDumper\Cloner\VarCloner)->cloneVar($value));
            } else {
                var_dump($value);
            }
        }, func_get_args());
        die(1);
    }
}


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

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');

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
