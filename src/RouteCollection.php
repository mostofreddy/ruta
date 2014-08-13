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
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace mostofreddy\ruta;

/**
 * RouteCollection
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteCollection implements \Countable, \IteratorAggregate
{
    protected $routeFactory = null;
    protected $routes = [];
    /**
     * return total routes
     * 
     * @return int
     */
    public function count()
    {
        return count($this->routes);
    }
    /**
     * Return iterator
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }
    /**
     * Set factory route
     * 
     * @param \mostofreddy\ruta\RouteFactory $factory Route factory
     * 
     * @return void
     */
    public function routeFactory(\mostofreddy\ruta\RouteFactory $factory)
    {
        $this->routeFactory = $factory;
    }
    /**
     * Add Route
     * 
     * @param string   $path     route pattern
     * @param Callable $callback Callback function
     *
     * @return \mostofreddy\ruta\Route
     */
    public function add($path, $callback = null)
    {
        $route = $this->routeFactory->newInstance();
        $route->add($path, $callback);
        $this->routes[] = $route;
        return $route;
    }
}
