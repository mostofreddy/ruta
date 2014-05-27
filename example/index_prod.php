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

$routeCollection = new \ruta\RouteCollectionCache();
$routeCollection->setDirCache(
    realpath(__DIR__.'/cache')
)->setName('rocketcode.ruta');

try {
    try {
        $routeCollection->load();
    } catch (\ruta\exceptions\CacheNoLoaded $e) {
        //set routes  
        \ruta\RouteBuilder::setSubdirectory('/fede/rocket/ruta/example/index_prod.php/');
        $routeCollection->append(
            \ruta\RouteBuilder::create()
                ->get('/', array("StaticClass", "callback"))
        )->append(
            \ruta\RouteBuilder::create()
                ->get('/work/\d+:id?', array(new ConcreteClass, "callback"))
                ->defaults(array('id' => 10))
        );
        $routeCollection->save();
    }

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
