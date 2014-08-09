<?php
/**
 * Ejemplo de uso del componente Ruta
 *
 * PHP version 5.4
 *
 * @category  Ruta
 * @package   Ruta\Example
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
require_once "../vendor/autoload.php";

use mostofreddy\ruta\RouterFactory;

$routerFactory = new RouterFactory();
$router = $routerFactory->newInstance();

$router->add(
    'ruta/'
)->method(['GET']);

$router->add(
    'ruta/users'
);

$router->add(
    'ruta/user/\d+:id?',
    function ($params) {
        echo "<pre>".print_r($params, true)."</pre>";
    }
)->defaults(['id' => 0, 'name' => 'Fede']);

$router->add(
    'ruta/:controller/:action/:id?'
)->method(['POST', 'GET']);



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

$route = $router->match($uri, $_SERVER);

if ($route !== false) {
    echo "<pre>".print_r($route, true)."</pre>";
    $callback = $route->getCallback();
    if ($callback !== null) {
        $callback($route->getParams());
    }
}
