<?php
/**
 * ConcreteClass
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta\Example
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
/**
 * ConcreteClass
 *
 * @category  Ruta
 * @package   Ruta\Example
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class ConcreteClass
{
    /**
     * [callback description]
     *
     * @param array $params parametros
     *
     * @return void
     */
    public function callback(array $params=array())
    {
        echo "Concrete Class Callback";
        var_dump($params);
        echo "<br/>";
    }
}
