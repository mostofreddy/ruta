<?php
/**
 * Router
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Ruta
 * @package    Resty
 * @subpackage Ruta
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace resty\ruta;
/**
 * Route
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
    protected $routes = [];
    protected $cacheRoute = null;
    protected $subDirectory = '';

    /**
     * Setea el objeto \resty\ruta\Route para creación de cada ruta.
     * Se crea un cache para luego en cada invocación de los metodos get/post se clone este objeto y
     * no haya que instanciarlo constantemente si se definen muchas rutas
     *
     * @param \resty\ruta\Route $route instancia de la clase \resty\ruta\Route
     *
     * @access public
     * @return self
     */
    public function cache(\resty\ruta\Route $route)
    {
        $this->cacheRoute = $route;
        return $this;
    }
    /**
     * Setea el subdirectorio para las rutas
     *
     * @param string $subDirectory subdirectorio.
     *
     * @access public
     * @return self
     */
    public function setSubDirectory($subDirectory)
    {
        $this->subDirectory .= ($subDirectory !== "")?"/".trim($subDirectory, '/'):'';
        return $this;
    }
    /**
     * append
     *
     * @param \resty\ruta\Route $route Description.
     *
     * @access public
     * @return self
     */
    public function append(\resty\ruta\Route $route)
    {
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Setea una ruta para GET
     *
     * @param string   $pattern  pattern
     * @param Callable $callback callback asociado a la ruta
     *
     * @access public
     * @return self
     */
    public function get($pattern, $callback)
    {
        $route = clone $this->cacheRoute;
        $route->get($this->subDirectory.$pattern, $callback);
        return $this->append($route);
    }
    /**
     * Setea una ruta para POST
     *
     * @param string   $pattern  pattern
     * @param Callable $callback callback asociado a la ruta
     *
     * @access public
     * @return self
     */
    public function post($pattern, $callback)
    {
        $route = clone $this->cacheRoute;
        $route->post($this->subDirectory.$pattern, $callback);
        return $this->append($route);
    }
    /**
     * Indica si la URI machea con alguna ruta seteada
     *
     * @param string $method methodo http del request
     * @param string $uri    uri
     *
     * @access public
     * @return self|false devuelve la instancia de la ruta o false si no machea con ninguna
     */
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            $r = $route->match($method, $uri);
            if ($r !== false) {
                return $r;
            }
        }
        return false;
    }

}
