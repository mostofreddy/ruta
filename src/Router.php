<?php
/**
 * Router
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace mostofreddy\ruta;

/**
 * Router
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Router
{
    protected $routes = null;
    /**
     * Set route factory
     * 
     * @param \mostofreddy\ruta\RouteCollection $collection route collection
     * 
     * @return void
     */
    public function routeCollection(RouteCollection $collection)
    {
        $this->routes = $collection;
    }
    /**
     * Return routes
     * 
     * @return \mostofreddy\ruta\RouteCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->routes, $method), $args);
    }


    public function match($uri, array $server)
    {
        foreach ($this->routes as $route) {
            $match = $route->match($uri, $server);
            if ($match) {
                return $route;
            }
        }
        return false;
    }
}
