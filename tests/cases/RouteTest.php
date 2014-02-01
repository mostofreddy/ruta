<?php
/**
 * RouteTest
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Test
 * @package    Ruta
 * @subpackage Tests\Cases
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace ruta\tests\cases;
/**
 * Test unitario de la clase RouteTest
 *
 * @category   Test
 * @package    Ruta
 * @subpackage Tests\Cases
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea el metodo setDefaults
     *
     * @access public
     * @return void
     */
    public function testSetDefaults()
    {
        $expected = ['id' => 5];
        $route = new \ruta\Route();
        $route->setDefaults($expected);

        $this->assertAttributeEquals(
            $expected,
            'defaults',
            $route
        );
    }
    /**
     * Testea el metodo setRespondoTo
     *
     * @access public
     * @return void
     */
    public function testSetRespondTo()
    {
        $expected = ['GET'];
        $route = new \ruta\Route();
        $route->setRespondTo($expected);

        $this->assertAttributeEquals(
            $expected,
            'responseTo',
            $route
        );
    }
    /**
     * Testea el metodo setRespondoTo
     *
     * @access public
     * @return void
     */
    public function testSetPattern()
    {
        $expected = '/example/test/:id';
        $route = new \ruta\Route();
        $route->setPattern($expected);

        $this->assertAttributeEquals(
            $expected,
            'pattern',
            $route
        );
    }

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
     * Testea el metodo isStatic que devuelva false
     *
     * @param string $expected expected
     *
     * @dataProvider providerDinamicRoutes
     * @return void
     */
    public function testIsStaticFalse($expected)
    {
        $aux = new \ReflectionMethod('\ruta\Route', 'isStatic');
        $aux->setAccessible(true);

        $route = new \ruta\Route();
        $route->setPattern($expected);

        $this->assertFalse($aux->invoke($route));
    }
    /**
     * Testea el metodo isStatic que devuelva true
     *
     * @param string $expected expected
     *
     * @dataProvider providerStaticRoutes
     * @return void
     */
    public function testIsStaticTrue($expected)
    {
        $aux = new \ReflectionMethod('\ruta\Route', 'isStatic');
        $aux->setAccessible(true);

        $route = new \ruta\Route();
        $route->setPattern($expected);

        $this->assertTrue($aux->invoke($route));
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

        $aux = new \ReflectionMethod('\ruta\Route', 'parseSegment');
        $aux->setAccessible(true);

        $route = new \ruta\Route();
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

        $aux = new \ReflectionMethod('\ruta\Route', 'parseSegment');
        $aux->setAccessible(true);

        $route = new \ruta\Route();
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

        $aux = new \ReflectionMethod('\ruta\Route', 'parseSegment');
        $aux->setAccessible(true);

        $route = new \ruta\Route();
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
        $aux = new \ReflectionMethod('\ruta\Route', 'getSegments');
        $aux->setAccessible(true);

        $route = new \ruta\Route();
        $data = $aux->invokeArgs($route, array($pattern));
        var_dump($data);
        $this->assertEquals(array(), $data);
    }
}
