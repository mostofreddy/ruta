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

$routeCollection = new \mostofreddy\ruta\RouteCollection();
$routeCollection->subdirectory('ruta');


var_dump(\mostofreddy\ruta\RouteBuilder::$subdirectory);
