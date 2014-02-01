<?php
/**
 * Demo
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Demo
 * @package   Ruta
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */

$path = realpath(__DIR__."/../vendor/")."/autoload.php";
require_once $path;

$router = new \mostofreddy\ruta\Router();
$router->setSubDirectory('ruta')
    ->cache(new \mostofreddy\ruta\Route());

$router->get(
    '/sub/',
    array(new Sub(), 'subrutas'),
    true
);

$router->get(
    '/foo/statics/:search?',
    array("Foo", 'statics')
)->defaults(array("search" => "nada =("));

$router->get(
    '/foo/concrete/:search?',
    array(new \Foo(), 'concrete')
)->defaults(array("search" => "nada =("));

$router->get(
    '/',
    function ($params) {
        echo "root<br/>";
        echo "params: ".json_encode($params);
        echo "<br/>";
    }
)->defaults(array("nombre" => "mostofreddy"));

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

/**
 * Demo
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Demo
 * @package   Ruta
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Sub
{
    /**
     * subrutas
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function subrutas()
    {
        $router = new \mostofreddy\ruta\Router();
        $router->setSubDirectory('ruta/sub')
            ->cache(new \mostofreddy\ruta\Route());

        $router->get(
            '/index',
            array("Sub", "index")
        );

        return $router;
    }

    /**
     * index
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function index()
    {
        echo "sub > index<br/>";
    }
}

/**
 * Demo
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Demo
 * @package   Ruta
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Foo
{
    /**
     * concrete
     *
     * @param mixed $params Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function concrete($params)
    {
        echo "concrete<br/>";
        echo "params: ".json_encode($params);
        echo "<br/>";
    }
    /**
     * static
     *
     * @param mixed $params Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    static public function statics($params)
    {
        echo "static<br/>";
        echo "params: ".json_encode($params);
        echo "<br/>";
    }
}
