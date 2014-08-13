<?php
/**
 * Route
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
 * Route
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Route
{
    const ERR_CALLBACK_TYPE = 'Callback is not callable';
    protected $pattern = '';
    protected $methods = [];
    protected $defaults = [];
    protected $patternCheck = null;
    protected $callback = null;
    /**
     * Set instance PatternCheck
     * 
     * @param \mostofreddy\rutaPattern\Check $pattern patterncheck
     * 
     * @return void
     */
    public function patternCheck(\mostofreddy\ruta\PatternCheck $pattern)
    {
        $this->patternCheck = $pattern;
    }
    /**
     * Set uri pattern
     * 
     * @param string   $pattern  uri pattern
     * @param Callable $callback function to call
     * 
     * @return self
     */
    public function add($pattern, $callback = null)
    {
        if ($callback!==null && !is_callable($callback)) {
            throw new \InvalidArgumentException(static::ERR_CALLBACK_TYPE);
        }
        $this->callback = $callback;
        $this->pattern = '/'.ltrim($pattern, '/');
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
     * Set default values
     * 
     * @param array $defaults defaults
     * 
     * @return self
     */
    public function defaults(array $defaults)
    {
        $this->defaults = $defaults;
        return $this;
    }
    /**
     * Return all params
     * 
     * @return array
     */
    public function getParams()
    {
        return array_merge(
            $this->defaults,
            $this->patternCheck->params()
        );
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
     * Chequea que el path machee con la ruta
     * 
     * @param string $uri    uri
     * @param array  $server datos de la variable $_SERVER
     * 
     * @return bool
     */
    public function match($uri, array $server)
    {
        return $this->isRoutale()
            && $this->isValidHttpMethod($server)
            && $this->isPatternMartch($uri);
    }
    /**
     * Verifica que la ruta no este vacia
     * 
     * @return boolean 
     */
    protected function isRoutale()
    {
        return ($this->pattern != '');
    }
    /**
     * Validate http method for route
     * 
     * @param array $server Route methods
     * 
     * @return boolean
     */
    protected function isValidHttpMethod(array $server)
    {
        if (empty($this->methods)) {
            return true;
        }
        if (isset($server['REQUEST_METHOD']) && in_array($server['REQUEST_METHOD'], $this->methods)) {
            return true;
        }
        return false;
    }
    /**
     * Match patter
     * 
     * @param string $uri uri
     * 
     * @return boolean
     */
    protected function isPatternMartch($uri)
    {
        return $this->patternCheck->match($uri, $this->pattern);
    }
}
