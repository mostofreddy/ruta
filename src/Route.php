<?php
/**
 * Route
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */

namespace mostofreddy\ruta;

/**
 * Route
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Route
{
    const ERR_CALLBACK_TYPE = 'Callback is not callable';
    protected $callback = null;
    protected $pattern = null;
    protected $methods = null;
    protected $defaults = array();
    protected $params = array();
    protected $subdirectory = '';
    /**
     * Set subdirectory 
     * 
     * @param string $sub subdirectory
     * 
     * @return self
     */
    public function subdirectory($sub)
    {
        $this->subdirectory = '/'.trim($sub, '/');
        return $this;
    }
    /**
     * Setea los valores defaults de las variables de la uri
     *
     * Ejemplo:
     * $r = new \ruta\Route();
     * $r->get('/user/\d+:id?', array(new ExampleObject, "callback")
     *     ->defaults(array('id' => 10))
     *
     * http://localhost/user => user = 10
     * htpp://localhost/user/5 => user = 5
     *
     * @param array $defaults array con los valores
     *
     * @access public
     * @return self
     */
    public function defaults(array $defaults = array())
    {
        $this->defaults = $defaults;
        return $this;
    }
    /**
     * Devuelve todos los parametros definidos y enviados en la ruta actual
     *
     * @param array $params devuelve las variables de la ruta
     *
     * @access public
     * @return array
     */
    public function getParams(array $params = array())
    {
        return $params + $this->params;
    }
    /**
     * Devuelve la función callback de la ruta
     *
     * @access public
     * @return Callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
    /**
     * Setea una ruta GET
     *
     * @param string   $pattern  pattern de la ruta
     * @param callable $callback función callback relacionada a la ruta
     *
     * @access public
     * @return self
     */
    public function get($pattern, $callback)
    {
        return $this->add($pattern, $callback)
            ->method(array('GET'));
    }

    /**
     * Setea una ruta POST
     *
     * @param string   $pattern  pattern de la ruta
     * @param callable $callback función callback relacionada a la ruta
     *
     * @access public
     * @return self
     */
    public function post($pattern, $callback)
    {
        return $this->add($pattern, $callback)
            ->method(array('POST'));
    }
    /**
     * Método interno para setear una ruta
     *
     * @param string   $pattern  pattern de la ruta
     * @param callable $callback función callback relacionada a la ruta
     *
     * @access protected
     * @return self
     */
    protected function add($pattern, $callback)
    {
        if ($callback!==null && !is_callable($callback)) {
            throw new \InvalidArgumentException(static::ERR_CALLBACK_TYPE);
        }
        $this->callback = $callback;
        // $this->pattern = $this->subdirectory.$pattern;
        $this->pattern = $this->subdirectory.'/'.trim($pattern, '/');
        return $this;
    }
    /**
     * Setea los methods http habilitados para la ruta
     *
     * @param array $methods metodos http habilitados para el pattern
     *
     * @access public
     * @return self
     */
    public function method(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * Indica si la URI machea con el pattern
     *
     * @param string $method methodo http del request
     * @param string $uri    uri
     *
     * @access public
     * @return bool
     */
    public function match($method, $uri)
    {
        if (!in_array($method, $this->methods)) {
            return false;
        }
        $matches = $this->assert($uri);

        if (empty($matches)) {
            return false;
        }

        $this->params = array_merge(
            $this->defaults,
            $this->cleanMatches($matches)
        );

        return true;
    }
    
    /**
     * Devuelve todas las variables que machee
     *
     * @param string $uri uri
     *
     * @access protected
     * @return array
     */
    protected function assert($uri)
    {
        $matches = array();
        var_dump($this->compile(), $uri);
        \preg_match($this->compile(), $uri, $matches);
        return $matches;
    }

    /**************************************************
     * Pattern compilation
     *************************************************/

    /**
     * Genera el regex para validar la uri
     *
     * @access protected
     * @return string
     */
    protected function compile()
    {
        if ($this->isStatic()) {
            return '~^'.$this->pattern.'$'.'~';
        }
        $segments = $this->getSegments($this->pattern);
        $compiled = $this->pattern;
        foreach ($segments as $segment) {
            $compiled = \str_replace($segment['token'], $segment['regex'], $compiled);
        }
        $compiled = "~^{$compiled}".'$'."~";
        return $compiled;
    }
    /**
     * Retorna true si el pattern no tiene variables, o sea, es estática
     *
     * @access protected
     * @return boolean
     */
    protected function isStatic()
    {
        return \strpos($this->pattern, ":") === false;
    }
    /**
     * getSegments
     *
     * @param mixed $pattern Description.
     *
     * @access protected
     * @return array
     */
    protected function getSegments($pattern)
    {
        $segments = array();
        $parts = \explode("/", ltrim($pattern, "/"));
        foreach ($parts as $part) {
            if (\strpos($part, ":") !== false) {
                $segments[] = $this->parseSegment($part);
            }
        }
        return $segments;
    }
    /**
     * Pulls out relevent information on a given segment.
     *
     * @param string $segment The segment
     *
     * @see https://github.com/Bistro/Router/blob/master/lib/Bistro/Router/Route.php
     * @return array ['segment' => (string), name' => (string), 'regex' => (string), 'optional' => (boolean)]
     */
    protected function parseSegment($segment)
    {
        if (\strpos($segment, ':') === false) {
            return array(
                'segment' => "",
                'token' => "",
                'name' => "",
                'regex' => "",
                'optional' => ""
            );
        }
        $optional = false;
        list($regex, $name) = \explode(":", $segment);
        if (\substr($name, -1) === "?") {
            $name = \substr($name, 0, -1);
            $optional = true;
        }
        if ($regex === "") {
            $regex = "[^\/]+";
        }
        $regex = "/(?P<{$name}>{$regex})";
        if ($optional) {
            $regex = "(?:{$regex})?";
        }
        return array(
            'segment' => $segment,
            'token' => "/".$segment,
            'name' => $name,
            'regex' => $regex,
            'optional' => $optional
        );
    }

    /**
     * Obtiene todas las variables de la query
     *
     * @param array $matches variables de la uri
     *
     * @return array
     */
    protected function cleanMatches(array $matches)
    {
        $aux = array();
        foreach ($matches as $k => $v) {
            if (!is_int($k)) {
                $aux[$k] = $v;
            }
        }
        return $aux;
    }
}
