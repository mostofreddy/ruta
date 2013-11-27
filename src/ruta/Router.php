<?php
/**
 * Router
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace ruta;
/**
 * Router
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Router
{
    protected $routecache = null;
    protected $routes = [];
    protected $subDirectory = '';
    /**
     * setSubDirectory
     *
     * @param string $subDirectory Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function setSubDirectory($subDirectory)
    {
        $this->subDirectory = ($subDirectory !== "")?"/".trim($subDirectory, '/'):'';
        return $this;
    }
    /**
     * match
     *
     * @param mixed $method Description.
     * @param mixed $uri    Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route->isMatch($method, $uri)) {
                return $route;
            }
        }
        return false;
    }
    /**
     * add
     *
     * @param string   $pattern  Patron para machear la ruta
     * @param array    $methods  Método por el cual se validará
     * @param callable $callback Callback a invocar cuando al uri machea con una ruta
     *
     * @access protected
     * @return mixed Value.
     */
    protected function add($pattern, array $methods = array('GET', 'POST', 'PUT', 'DELETE'), $callback=null)
    {
        $route = $this->getCacheRoute()
            ->setPattern($this->subDirectory.$pattern)
            ->setRespondTo($methods)
            ->setCallback($callback);

        $this->routes[] = $route;
        return $route;
    }

    /**
     * Patter para un request POST
     *
     * @param string   $pattern  Patron para machear la ruta
     * @param callable $callback Callback a invocar cuando al uri machea con una ruta
     *
     * @access public
     * @return mixed Value.
     */
    public function post($pattern, $callback=null)
    {
        return $this->add($pattern, ['POST'], $callback);
    }
    /**
     * Patter para un request GET
     *
     * @param string   $pattern  Patron para machear la ruta
     * @param callable $callback Callback a invocar cuando al uri machea con una ruta
     *
     * @access public
     * @return mixed Value.
     */
    public function get($pattern, $callback=null)
    {
        return $this->add($pattern, ['GET'], $callback);
    }
    /**
     * Devuelve un objeto Route
     *
     * @access protected
     * @return \ruta\Route
     */
    protected function getCacheRoute()
    {
        if (is_null($this->routecache)) {
            $this->routecache = new \ruta\Route();
        }
        return clone $this->routecache;
    }
}
