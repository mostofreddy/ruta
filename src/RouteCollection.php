<?php
/**
 * RouteCollection
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Restty\Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */

namespace restty\ruta;

/**
 * RouteCollection
 *
 * @category  Ruta
 * @package   Restty\Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteCollection implements \Countable
{
    const ERR_NO_ROUTE = 'Route not found!';
    protected $routes = array();
    /**
     * Devuelve la cantidad de elementos en la coleccion
     * 
     * @return int
     */
    public function count()
    {
        return count($this->routes);
    }
    
    /**
     * append
     *
     * @param \restty\ruta\Route $route Custom route
     *
     * @access public
     * @return self
     */
    public function append(\restty\ruta\Route $route)
    {
        $this->routes[] = $route;
        return $this;
    }
    /**
     * Indica si la URI machea con alguna ruta seteada
     *
     * @param string $method metodo http del request
     * @param string $uri    uri
     *
     * @access public
     * @throws restty\ruta\NoRoute si la ruta no esta definida
     * @return restty\ruta\Route
     */
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            if (true === $route->match($method, $uri)) {
                return $route;
            }
        }
        throw new \restty\ruta\exceptions\RouteNotFound(static::ERR_NO_ROUTE);
    }

}
