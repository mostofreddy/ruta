<?php
/**
 * PatternCheckTest
 *
 * PHP version 5.4+
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta\Tests
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace mostofreddy\pjose\tests;

/**
 * PatternCheckTest
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta\Tests
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class PatternCheckTest extends \PHPUnit_Framework_TestCase
{
    public function testmatch()
    {
        $stub = $this->getMock('\mostofreddy\ruta\PatternCheck', ['compile']);

        $stub->method('compile')
             ->will($this->returnValue('~^/ruta/$~'));

        $this->assertTrue($stub->match("/ruta/", "/ruta/"));
        $this->assertFalse($stub->match('/ruta', "/ruta/"));
    }

    public function testmatch2()
    {
        $stub = $this->getMock('\mostofreddy\ruta\PatternCheck', ['compile']);

        $stub->method('compile')
             ->will($this->returnValue('~^/ruta/user(?:/(?P<id>\d+))?$~'));

        $this->assertTrue($stub->match("/ruta/user/1", "/ruta/user/\d+:id?"));
    }
}
