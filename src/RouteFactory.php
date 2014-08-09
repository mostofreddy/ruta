<?php
/**
 * RouteFactory
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
 * RouteFactory
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteFactory
{
    /**
     * Create a Route
     *
     * @return \mostofreddy\ruta\Route
     */
    public function newInstance()
    {
         //cache optimization for new instances
        static $cache = null;
        if (null === $cache) {
            $cache = new \mostofreddy\ruta\Route();
            $cache->patternCheck(new PatternCheck);
        }
        return clone $cache;
    }
}
