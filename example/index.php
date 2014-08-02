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
require_once 'ConcreteClass.php';
require_once 'StaticClass.php';

ini_set('error_reporting', true);

$routeCollection = new \restty\ruta\RouteCollection();

\restty\ruta\RouteBuilder::setSubdirectory('ruta');

//set routes
$routeCollection->append(
    \restty\ruta\RouteBuilder::create()
        ->get('/', array("StaticClass", "callback"))
)->append(
    \restty\ruta\RouteBuilder::create()
        ->get('/work/\d+:id?', array(new ConcreteClass, "callback"))
        ->defaults(array('id' => 10))
)->append(
    \restty\ruta\RouteBuilder::create()
        ->get(
            '/users', function () {
                echo "users!";
            }
        )
);

try {
    $uri = $_SERVER["REQUEST_URI"];
    $method = $_SERVER["REQUEST_METHOD"];
    $route = $routeCollection->match(
        $method,
        $uri
    );
    $callback = $route->getCallback();
    $callback($route->getParams());
} catch (\Exception $e) {
    echo $e->getMessage();
}


echo "<br/>Done!";
