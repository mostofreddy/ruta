<?php
/**
 * Route
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Ruta
 * @package    Restty
 * @subpackage Ruta
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace restty\ruta;
/**
 * Route
 *
 * @category   Ruta
 * @package    Restty
 * @subpackage Ruta
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class Route
{
    const ERR_CALLBACK_TYPE = 'Callback is not callable';
    protected $callback = null;
    protected $pattern = null;
    protected $methods = null;
    protected $defaults = array();
    protected $params = array();
    /**
     * Setea los valores defaults de las variables opcionales de la uri
     *
     * @param array $defaults Description.
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
     * @param array $params Description.
     *
     * @access public
     * @return array
     */
    public function getParams(array $params=array())
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
     * @return self|false devuelve false si no machea el pattern
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

        return $this;
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
        $this->pattern = $pattern;
        return $this;
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
        \preg_match($this->compile(), $uri, $matches);
        return $matches;
    }

    /**************************************************
     * Compilacion del pattern
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
