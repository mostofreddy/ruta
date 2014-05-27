<?php
/**
 * RouteCollectionCache
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

namespace ruta;

/**
 * RouteCollectionCache
 *
 * @category  Ruta
 * @package   Ruta
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class RouteCollectionCache extends \ruta\RouteCollection
{

    const ERR_CACHE_NO_EXISTS = 'Cache not exists';
    const ERR_CACHE_NO_ENABLED = 'Cache not enabled';
    const ERR_INVALID_CACHE_DIR = 'Invalid cache dir (no writable or not readable)';
    const ERR_CACHE_NO_SAVE = 'Cache not save';
    protected $dir = '';
    protected $name = 'routecollection';
    protected $enabled = true;
    
    /**
     * Set cache dir
     * 
     * @param string $dir directory to save cache
     *
     * @return self
     */
    public function setDirCache($dir)
    {
        $this->dir = rtrim($dir, '/').'/';
        return $this;
    }
    /**
     * Set cache name
     * 
     * @param string $name filename
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Turn on/off cache
     * Default: ON
     * 
     * @param bool $enabled turn off/on
     * 
     * @return self
     */
    public function enabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }
    /**
     * Return full path to cache
     * 
     * @return string
     */
    protected function getFullPathCache()
    {
        return $this->dir.$this->name.".cache";
    }
    /**
     * Cache load
     * 
     * @return self
     */
    public function load()
    {
        $this->loadValidation();        

        $data = file_get_contents($this->getFullPathCache());
        $this->routes = $this->dataDecode($data);
        return true;
    }
    /**
     * Load validation
     *
     * @throws \Exception if not load cache
     * @return true
     */
    protected function loadValidation()
    {
        if (false === $this->enabled) {
            throw new \ruta\exceptions\CacheNoLoaded(static::ERR_CACHE_NO_ENABLED);
        }
        
        $this->isValidDirPath();

        if (false === file_exists($this->getFullPathCache())) {
            throw new \ruta\exceptions\CacheNoLoaded(static::ERR_CACHE_NO_EXISTS);
        }
        return true;
    }
    /**
     * Valida el path en donde se guarda y recupera el cache
     *
     * @throws \Exception si el directorio de cache no es valido
     * @return bool
     */
    protected function isValidDirPath()
    {
        if (!is_readable($this->dir) || !is_writable($this->dir)) {
            throw new \Exception(static::ERR_INVALID_CACHE_DIR);
        }
        return true;
    }
    /**
     * Save in cache
     * 
     * @return self
     */
    public function save()
    {
        if (false === $this->enabled) {
            return false;
        }
        try {
            file_put_contents($this->getFullPathCache(), $this->dataEncode($this->routes));
        } catch (\Exception $e) {
            throw new \ruta\exceptions\CacheNoSaved(static::ERR_CACHE_NO_SAVE);
        }
        return $this;
    }
    /**
     * Encode routes data
     * 
     * @param mixed &$data data to encode
     * 
     * @return string
     */
    protected function dataEncode(&$data)
    {
        return base64_encode(serialize($data));
    }
    /**
     * Decode route data
     * 
     * @param string &$data data to decode
     * 
     * @return mixed
     */
    protected function dataDecode(&$data)
    {
        return unserialize(base64_decode($data));
    }
}
