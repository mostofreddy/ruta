<?php
/**
 * RouteInternalsTest
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
 * RouteInternalsTest
 *
 * @category  Ruta
 * @package   Ruta/Tests
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteInternalsTest extends \PHPUnit_Framework_TestCase
{
     /**
    * providerDinamicRoutes
    *
    * @access public
    * @return array
    */
    public function providerDinamicRoutes()
    {
        return array(
            array('/test/:controller/:id?'),
            array('/:module/:controller'),
            array('/test/dinamic/:id')
        );
    }
    /**
    * providerDinamicRoutes
    *
    * @access public
    * @return array
    */
    public function providerStaticRoutes()
    {
        return array(
            array('/test/controller'),
            array('/test/controller/1'),
            array('/test/controller/1?')
        );
    }
    /**
    * Testea el metodo isStatic con patterns dinamicos por lo cual el metood debe devolver false
    *
    * @param string $expected expected
    *
    * @dataProvider providerDinamicRoutes
    * @return void
    */
    public function testIsStaticFalse($expected)
    {
        $route = new \resty\ruta\Route();

        $rMethod = new \ReflectionMethod('\resty\ruta\Route', 'isStatic');
        $rMethod->setAccessible(true);

        $rAttr = new \ReflectionProperty('\resty\ruta\Route', 'pattern');
        $rAttr->setAccessible(true);
        $rAttr->setValue($route, $expected);

        $this->assertFalse($rMethod->invoke($route));
    }
    /**
    * Testea el metodo isStatic con patterns estaticos por lo cual el metood debe devolver true
    *
    * @param string $expected expected
    *
    * @dataProvider providerStaticRoutes
    * @return void
    */
    public function testIsStaticTrue($expected)
    {
        $route = new \resty\ruta\Route();

        $rMethod = new \ReflectionMethod('\resty\ruta\Route', 'isStatic');
        $rMethod->setAccessible(true);

        $rAttr = new \ReflectionProperty('\resty\ruta\Route', 'pattern');
        $rAttr->setAccessible(true);
        $rAttr->setValue($route, $expected);

        $this->assertTrue($rMethod->invoke($route));
    }
    /**
     * Testea el metodo parseSegment pasandole un valor valido no opcional como parametro
     *
     * @access public
     * @return void
     */
    public function testParseSegmentValid()
    {
        $uri = ':controller';
        $expected = array(
            'segment' => ':controller',
            'token' => '/:controller',
            'name' => 'controller',
            'regex' => '/(?P<controller>[^\/]+)',
            'optional' => false
        );

        $aux = new \ReflectionMethod('\resty\ruta\Route', 'parseSegment');
        $aux->setAccessible(true);

        $route = new \resty\ruta\Route();
        $data = $aux->invokeArgs($route, array($uri));

        $this->assertEquals($expected, $data);
    }
    /**
     * Testea el metodo parseSegment pasandole un valor valido opcional como parametro
     *
     * @access public
     * @return void
     */
    public function testParseSegmentOptional()
    {
        $uri = ':controller?';
        $expected = array(
            'segment' => ':controller?',
            'token' => '/:controller?',
            'name' => 'controller',
            'regex' => '(?:/(?P<controller>[^\/]+))?',
            'optional' => true
        );

        $aux = new \ReflectionMethod('\resty\ruta\Route', 'parseSegment');
        $aux->setAccessible(true);

        $route = new \resty\ruta\Route();
        $data = $aux->invokeArgs($route, array($uri));

        $this->assertEquals($expected, $data);
    }
    /**
     * Testea el metodo parseSegment pasandole un valor no valido como parametro
     *
     * @access public
     * @return void
     */
    public function testParseSegment2()
    {
        $uri = 'controller';
        $expected = array(
            'segment' => "",
            'token' => "",
            'name' => "",
            'regex' => "",
            'optional' => ""
        );

        $aux = new \ReflectionMethod('\resty\ruta\Route', 'parseSegment');
        $aux->setAccessible(true);

        $route = new \resty\ruta\Route();
        $data = $aux->invokeArgs($route, array($uri));
        $this->assertEquals($expected, $data);
    }
    /**
     * testGetSegments
     *
     * @access public
     * @return mixed Value.
     */
    public function testGetSegments()
    {
        $pattern = '/test/:action/:id';
        $expected = array(
            array(
                'segment' => ':action',
                'token' => '/:action',
                'name' => 'action',
                'regex' => '/(?P<action>[^\/]+)',
                'optional' => false
            ),
            array(
                'segment' => ':id',
                'token' => '/:id',
                'name' => 'id',
                'regex' => '/(?P<id>[^\/]+)',
                'optional' => false
            )
        );
        $aux = new \ReflectionMethod('\resty\ruta\Route', 'getSegments');
        $aux->setAccessible(true);

        $route = new \resty\ruta\Route();
        $data = $aux->invokeArgs($route, array($pattern));
        $this->assertEquals($expected, $data);
    }


    /**
     * providerCompileRoutes
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function providerCompileRoutes()
    {
        return array(
            array('/user/edit', '~^/user/edit$~'),
            array('/user/edit/:id', '~^/user/edit/(?P<id>[^\/]+)$~'),
            array('/:controller/:action/:id', '~^/(?P<controller>[^\/]+)/(?P<action>[^\/]+)/(?P<id>[^\/]+)$~')
        );
    }
    /**
     * Testea compilar una ruta
     *
     * @param string $pattern  pattern
     * @param string $expected expected
     *
     * @access public
     * @dataProvider providerCompileRoutes
     * @return mixed Value.
     */
    public function testCompile($pattern, $expected)
    {
        $route = new \resty\ruta\Route();

        $rAttr = new \ReflectionProperty('\resty\ruta\Route', 'pattern');
        $rAttr->setAccessible(true);
        $rAttr->setValue($route, $pattern);

        $aux = new \ReflectionMethod('\resty\ruta\Route', 'compile');
        $aux->setAccessible(true);

        $result = $aux->invoke($route);

        $this->assertEquals($expected, $result);
    }

    /**
     * Testea el metodo assert
     *
     * @access public
     * @depends testCompile
     * @return mixed Value.
     */
    public function testAssert()
    {
        $route = new \resty\ruta\Route();
        $uri = "/user/edit/5";
        $pattern = '/user/edit/:id';
        $callback = function () {
        };
        $expected = array(
            '/user/edit/5',
            'id' => '5',
            '5'
        );

        $route->get($pattern, $callback);

        $aux = new \ReflectionMethod('\resty\ruta\Route', 'assert');
        $aux->setAccessible(true);

        $result = $aux->invokeArgs($route, array($uri));
        $this->assertEquals($expected, $result);
    }


    /**
     * providerMatch
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function providerMatch()
    {
        return array(
            array('/user/edit/:id', '/user/edit/5', 'GET', true),
            array('/user/edit/:id', '/user/edit/5', 'POST', false),
            array('/user/edit/:id', '/user/create', 'GET', false)
        );
    }
    /**
     * Testea el metodo match
     *
     * @param string $pattern  pattern
     * @param string $uri      uri
     * @param string $method   metodo
     * @param bool   $validate flag de tipo de validacion
     *
     * @access public
     * @depends testAssert
     * @dataProvider providerMatch
     * @return mixed Value.
     */
    public function testMatch($pattern, $uri, $method, $validate)
    {
        $callback = function () {
        };

        $route = new \resty\ruta\Route();
        $route->get($pattern, $callback);
        $result = $route->match($method, $uri);
        if ($validate) {
            //si es true => tengo que validar que la ruta es macheo y por eso me devuelve un objeto
            $this->assertInstanceOf('\resty\ruta\Route', $result);
        } else {
            //si es false => tengo que validar que la ruta NO machea y devuelve false
            $this->assertFalse($result);
        }
    }

    /**
     * Testea el metodo getParams
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function testGetParams()
    {
        $pattern = '/user/edit/:id';
        $callback = function () {
        };
        $method = 'GET';
        $uri = '/user/edit/5';
        $expected = array(
            'id' => 5,
            'editby' => 'mosto'
        );

        $route = new \resty\ruta\Route();
        $route->get($pattern, $callback)
            ->defaults(array("editby"=>'mosto'));
        $result = $route->match($method, $uri);

        $this->assertEquals($expected, $result->getParams());
    }


}
