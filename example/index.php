<?php
/**
 * Ejemplo de uso del componente Ruta
 *
 * PHP version 5.4
 *
 * @category   Ruta
 * @package    Restty
 * @subpackage Ruta/Example
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
require_once "../vendor/autoload.php";
require_once "StaticClass.php";
require_once "ConcreteClass.php";

$router = new \restty\ruta\Router();
$router->setSubDirectory('ruta')
    ->cache(new \restty\ruta\Route());

$router->get(
    '/',
    function ($params) {
        echo "root<br/>";
        echo "params: ".json_encode($params);
        echo "<br/>";
    }
)->defaults(array("nombre" => "mostofreddy"));

$router->get(
    '/lambda',
    function ($params) {
        echo "Lambda callback<br/>";
        echo "<br/>";
    }
);

$router->get(
    '/static',
    array('StaticClass', 'callback')
);

$router->get(
    '/concrete',
    array(new ConcreteClass, 'callback')
);

try {
    $uri = $_SERVER["REQUEST_URI"];
    $method = $_SERVER["REQUEST_METHOD"];
    echo $method." - ".$uri."<br/>";
    $route = $router->match(
        $method,
        $uri
    );
    if ($route !== false) {
        $callback = $route->getCallback();
        $callback($route->getParams());
    }
    echo "<br/>Done!";
} catch (\Exception $e) {
    echo $e->getMessage();
}
