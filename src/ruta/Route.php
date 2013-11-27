<?php
/**
 * Route
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace ruta;
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
class Route
{
    const ERR_CALLBACK_TYPE = 'Callback is not callable';
    protected $pattern = null;
    protected $responseTo = null;
    protected $defaults = array();
    protected $params = array();
    protected $callback = null;

    /**
     * Verifica si el methodo http y la uri machean
     *
     * @param string $method Methodo http del request
     * @param string $uri    Uri del request
     *
     * @access public
     * @return boolean
     */
    public function isMatch($method, $uri)
    {
        if (!in_array($method, $this->responseTo)) {
            return false;
        }
        $matches = $this->match($uri);

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

    /**
     * Devuelve todas las variables que machee
     *
     * @param mixed $uri Description.
     *
     * @access protected
     * @return mixed Value.
     */
    protected function match($uri)
    {
        $matches = array();
        \preg_match($this->compile(), $uri, $matches);
        return $matches;
    }
    /**
     * Genera el regex para validar la uri
     *
     * @access protected
     * @return string
     */
    protected function compile()
    {
        $static = $this->isStatic();

        if ($this->isStatic()) {
            return '~^'.$this->pattern.'$~';
        }
        $segments = $this->getSegments($this->pattern);
        $compiled = $this->pattern;
        foreach ($segments as $segment) {
            $compiled = \str_replace($segment['token'], $segment['regex'], $compiled);
        }
        $compiled = "~^{$compiled}$~";
        return $compiled;
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
     * @see    https://github.com/Bistro/Router/blob/master/lib/Bistro/Router/Route.php
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
     * Setea el pattern para machear la uri
     *
     * @param string $pattern Pattern
     *
     * @access public
     * @return \ruta\Route
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }
    /**
     * Setea los methodos
     *
     * @param array $response array con los methodos http aceptados
     *
     * @access public
     * @return \ruta\Route
     */
    public function setRespondTo(array $response)
    {
        $this->responseTo = $response;
        return $this;
    }

    /**
     * Setea los valores defaults de las variables opcionales de la uri
     *
     * @param array $defaults Description.
     *
     * @access public
     * @return \ruta\Route
     */
    public function setDefaults(array $defaults = array())
    {
        $this->defaults = $defaults;
        return $this;
    }

    /**
     * Setea el callback que se puede llamar para una ruta
     *
     * @param mixed $callback callback para invocar para una ruta
     *
     * @access public
     * @return \ruta\Route
     */
    public function setCallback($callback)
    {
        if ($callback!==null && !is_callable($callback)) {
            throw new \Exception(static::ERR_CALLBACK_TYPE);
        }
        $this->callback = $callback;
        return $this;
    }

    /**
     * Invoca a la funcion callback y le pasa como parametro un array con las variables encontradas
     * en la uri
     *
     * @param mixed $params paramtros para pasar a la función de callback
     *
     * @access public
     * @return mixed
     */
    public function callback($params=null)
    {
        if ($this->callback !== null) {
            if ($params !== null) {
                $params = is_array($params)?$params:array($params);
                $params = $params + $this->params;
            } else {
                $params = $this->params;
            }
            $callback = $this->callback;
            return $callback($params);
        }
        return false;
    }
}
