<?php
/**
 * RouteBuilder
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Restty\Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */

namespace restty\ruta;

/**
 * RouteBuilder
 *
 * @category  Ruta
 * @package   Restty\Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteBuilder
{
    protected static $subdirectory = '';
    /**
     * Set subdirectory for all routes
     * 
     * @param string $sub subdirectory
     *
     * @return void
     */
    public static function setSubdirectory($sub)
    {
        static::$subdirectory = $sub;
    }
    /**
     * Factory class
     *
     * @return restty\ruta\Route
     */
    public static function create()
    {
        //cache optimization for new instances
        static $cache = null;
        if (null === $cache) {
            $cache = new \restty\ruta\Route();
        }
        $aux = clone $cache;
        return $aux->subdirectory(static::$subdirectory);
    }
}
