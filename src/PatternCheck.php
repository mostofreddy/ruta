<?php
/**
 * PatternCheck
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
 * PatternCheck
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class PatternCheck
{
    protected $matches = [];
    /**
     * Return clean matches
     * 
     * @return array
     */
    public function params()
    {
        $this->cleanMatches();
        return $this->matches;
    }
    /**
     * Check uri & pattern
     * 
     * @param string $uri     uri/path
     * @param string $pattern pattern
     * 
     * @return bool
     */
    public function match($uri, $pattern)
    {
        $this->pattern = $pattern;
        \preg_match($this->compile(), $uri, $this->matches);
        return !empty($this->matches);
    }
    /**
     * Compile pattern
     * 
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
     * @return void
     */
    protected function cleanMatches()
    {
        $aux = array();
        foreach ($this->matches as $k => $v) {
            if (!is_int($k)) {
                $aux[$k] = $v;
            }
        }
        $this->matches = $aux;
    }
}
