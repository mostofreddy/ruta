<?php
/**
 * RouteTest
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Ruta
 * @package    Resty
 * @subpackage Ruta/Tests
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace resty\ruta\tests;
/**
 * RouteTest
 *
 * @category  Ruta
 * @package   Ruta/Tests
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea el metodo defaults
     *
     * @access public
     * @return void
     */
    public function testDefaults()
    {
        $expected = ['id' => 5];
        $route = new \resty\ruta\Route();
        $route->defaults($expected);

        $this->assertAttributeEquals(
            $expected,
            'defaults',
            $route
        );
    }

    /**
     * Testea el metodo Method
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function testMethod()
    {
        $route = new \resty\ruta\Route();
        $expected = ['GET'];

        $ref = new \ReflectionMethod('\resty\ruta\Route', 'method');
        $ref->setAccessible(true);

        $ref->invokeArgs($route, array($expected));

        $this->assertAttributeEquals(
            $expected,
            'methods',
            $route
        );
    }
    /**
     * Testea el metodo add
     *
     * @access public
     * @return mixed Value.
     */
    public function testAdd()
    {
        $expected_pattern = '/home/';
        $expected_callback = function () {
        };
        $route = new \resty\ruta\Route();

        $ref = new \ReflectionMethod('\resty\ruta\Route', 'add');
        $ref->setAccessible(true);

        $ref->invokeArgs($route, array($expected_pattern, $expected_callback));

        $this->assertAttributeEquals(
            $expected_callback,
            'callback',
            $route
        );
        $this->assertAttributeEquals(
            $expected_pattern,
            'pattern',
            $route
        );
    }
    /**
     * Testea el metodo add pasandole un callback inválido
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Callback is not callable
     * @access public
     * @return mixed Value.
     */
    public function testAddInvalidCallback()
    {
        $expected_pattern = '/home/';
        $expected_callback = "Callback invalido";

        $route = new \resty\ruta\Route();

        $ref = new \ReflectionMethod('\resty\ruta\Route', 'add');
        $ref->setAccessible(true);

        $ref->invokeArgs($route, array($expected_pattern, $expected_callback));

        $this->assertAttributeEquals(
            $expected_callback,
            'callback',
            $route
        );
        $this->assertAttributeEquals(
            $expected_pattern,
            'pattern',
            $route
        );
    }

    /**
     * Testea el metodo get
     *
     * @access public
     * @depends testAdd
     * @depends testAddInvalidCallback
     * @depends testMethod
     * @return mixed Value.
     */
    public function testGet()
    {
        $expected_pattern = '/home/';
        $expected_callback = function () {
        };

        $route = new \resty\ruta\Route();
        $route->get($expected_pattern, $expected_callback);

        $this->assertAttributeEquals(
            $expected_callback,
            'callback',
            $route
        );
        $this->assertAttributeEquals(
            $expected_pattern,
            'pattern',
            $route
        );
        $this->assertAttributeEquals(
            ['GET'],
            'methods',
            $route
        );
    }

    /**
     * Testea el metodo post
     *
     * @access public
     * @depends testAdd
     * @depends testAddInvalidCallback
     * @depends testMethod
     * @return mixed Value.
     */
    public function testPost()
    {
        $expected_pattern = '/home/';
        $expected_callback = function () {
        };

        $route = new \resty\ruta\Route();
        $route->post($expected_pattern, $expected_callback);

        $this->assertAttributeEquals(
            $expected_callback,
            'callback',
            $route
        );
        $this->assertAttributeEquals(
            $expected_pattern,
            'pattern',
            $route
        );
        $this->assertAttributeEquals(
            ['POST'],
            'methods',
            $route
        );
    }

    /**
     * Testea el metodo getCallback
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function testGetCallback()
    {
        $expected = "Esto es un callback";
        $pattern = '/home/';
        $callback = function () use ($expected) {
            return $expected;
        };

        $route = new \resty\ruta\Route();
        $route->post($pattern, $callback);

        $c = $route->getCallback();
        $this->assertEquals(
            $expected,
            $c()
        );
    }
}
