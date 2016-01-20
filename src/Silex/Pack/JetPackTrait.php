<?php
namespace Quazardous\Silex\Pack;

use Pimple\Container;
use Silex\Application;

/**
 * 
 * Add some basic function to implement needed pack interface.
 *
 */
trait JetPackTrait
{
    /**
     * You can define the pack suffix. It's 'Pack' by default.
     * @const static::PACK_SUFFIX
     */
    //const PACK_SUFFIX = 'Pack';
    
    protected $packOptions = [
        'twig_templates_subpath' => 'views',
        'entity_subnamespace' => 'Entity',
        'entity_use_simple_annotation' => true,
        'configs_subpath' => 'configs',
        'assets_subpath' => 'assets',
        'translations_subpath' => 'locale',
        'mount_prefix' => '/',
        'mount_host' => null,
    ];
    public function setPackOptions(Container $app) {
        foreach ($this->packOptions as $key => &$value) {
            $key = $this->_ns($key);
            if (isset($app[$key])) {
                $value = $app[$key];
            }
        }
    }
    /**
     * 
     * @return \ReflectionClass
     */
    protected function getReflector() {
        static $reflector = null;
        if (empty($reflector)) {
            $reflector = new \ReflectionClass($this);
        }
        return $reflector;
    }
    
    /**
     * Return the name of the pack. It's the short class name without the 'Pack' suffix.
     * @return string
     */
    public function getName()
    {
        static $name = null;
        if (empty($name)) {
            $name = $this->getReflector()->getShortName();
            $suffix = defined('static::PACK_SUFFIX') ? static::PACK_SUFFIX : 'Pack';
            if (strrpos($name, $suffix) == (strlen($name) - strlen($suffix))) {
                $name = substr($name, 0, strlen($name) - strlen($suffix));
            }
        }
        return $name;
    }
    
    /**
     * Return the path for the Twig templates. By default, it's a 'views' folder in the pack class namespace path.
     * @return string
     */
    public function getTwigTemplatesPath()
    {
        static $path = null;
        if (empty($path)) {
            $subpath = $this->packOptions['twig_templates_subpath'];
            $path = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $path;
    }
    
    /**
     * Helper function  to prefix ids with the pack name as namespace.
     * ie. for a pack named 'MyFooPack', the  id 'bar' becomes 'MyFoo.bar' or 'my_foo.bar' (with decamelize).
     * 
     * @param string $id an id to namespace
     * @param boolean $decamelize
     * @param string $separator
     * @return string the pack namespaced id
     */
    protected function _ns($id = null, $decamelize = true, $separator = '.') {
        static $decamelized = null;
        if ($decamelize && empty($decamelized)) {
            $decamelized = static::decamelize($this->getName());
        }
        $ns = $decamelize ? $decamelized : $this->getName();
        return $ns . ($id ? $separator . $id : '');
    }

    /**
     * Decamelize the given string.
     * 
     * @param string $input            
     * @return string
     */
    protected static function decamelize($input)
    {
        $matches = null;
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
    
    /**
     * Return a default mapping for the pack's entities.
     * @return array
     */
    public function getEntityMappings()
    {
        static $mapping = null;
        if (empty($mapping)) {
            $subns = $this->packOptions['entity_subnamespace'];
            $subns = trim($subns, '\\');
            $simple = $this->packOptions['entity_use_simple_annotation'];
            $ns = $this->getReflector()->getNamespaceName() . '\\' . $subns;
            $subpath = str_replace('\\', '/', $subns);
            $path = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
            $mapping = [
                'type' => 'annotation',
                'namespace' => $ns,
                'path' => $path,
                'use_simple_annotation_reader' => $simple,
            ];
        }
        return [$mapping];
    }
    
    /**
     * The path of the configs.
     * @return string
     */
    public function getConfigsPath()
    {
        static $path = null;
        if (empty($path)) {
            $subpath = $this->packOptions['configs_subpath'];
            $path = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $path;
    }
    
    /**
     * The path of the assets.
     * @return string
     */
    public function getAssetsPath()
    {
        static $path = null;
        if (empty($path)) {
            $subpath = $this->packOptions['assets_subpath'];
            $path = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $path;
    }
    
    /**
     * No assetic formulae by default.
     */
    public function getAsseticFormulae()
    {
        return [];
    }
    
    /**
     * The path of the translations.
     * @return string
     */
    public function getTranslationsPath()
    {
        static $path = null;
        if (empty($path)) {
            $subpath = $this->packOptions['translations_subpath'];
            $path = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $path;
    }
    
    /**
     * By default mount the pack on '/'.
     */
    public function getMountPrefix() {
        return $this->packOptions['mount_prefix'];
    }
    
    /**
     * No specific host by default.
     */
    public function getMountHost() {
        return $this->packOptions['mount_host'];;
    }
    
    /**
     * No controllers by default.
     */
    public function connect(Application $app)
    {
        return $app['controllers_factory'];
    }
    
    /**
     * No Twig extension by default.
     */
    public function getTwigExtensions()
    {
        return [];
    }
    
    /**
     * No commands by default.
     */
    public function getConsoleCommands()
    {
        return [];
    }
}