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
        'public_subpath' => 'public',
        'assets_subpath' => 'assets',
        'translations_subpath' => 'locale',
        'mount_prefix' => '/',
        'mount_host' => null,
    ];
    
    /**
     * Set the pack's options from the app container.
     * @see \Quazardous\Silex\Api\OptionnablePackInterface::setPackOptions()
     * @param Container $app
     */
    public function setPackOptions(Container $app) {
        foreach ($this->packOptions as $key => &$value) {
            $key = $this->_ns($key);
            if (isset($app[$key])) {
                $value = $app[$key];
            }
        }
    }
    
    /**
     * Return the name of the pack. It's the short class name without the 'Pack' suffix.
     * @see \Quazardous\Silex\Api\PackInterface::getName()
     * @return string
     */
    public function getName()
    {
        static $names = [];
        $me = get_class($this);
        if (empty($names[$me])) {
            $names[$me] = $this->getReflector()->getShortName();
            $suffix = defined('static::PACK_SUFFIX') ? static::PACK_SUFFIX : 'Pack';
            if (strrpos($names[$me], $suffix) == (strlen($names[$me]) - strlen($suffix))) {
                $names[$me] = substr($names[$me], 0, strlen($names[$me]) - strlen($suffix));
            }
        }
        return $names[$me];
    }
    
    /**
     * Return the path for the Twig templates. By default, it's a 'views' folder in the pack class namespace path.
     * @see \Quazardous\Silex\Api\TwiggablePackInterface::getTwigTemplatesPath()
     * @return string
     */
    public function getTwigTemplatesPath()
    {
        static $paths = [];
        $me = get_class($this);
        if (empty($paths[$me])) {
            $subpath = $this->packOptions['twig_templates_subpath'];
            $paths[$me] = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $paths[$me];
    }
    
    /**
     * Return a default mapping for the pack's entities.
     * @see \Quazardous\Silex\Api\EntitablePackInterface::getEntityMappings()
     * @return array
     */
    public function getEntityMappings()
    {
        static $mappings = [];
        $me = get_class($this);
        if (empty($mappings[$me])) {
            $subns = $this->packOptions['entity_subnamespace'];
            $subns = trim($subns, '\\');
            $simple = $this->packOptions['entity_use_simple_annotation'];
            $ns = $this->getReflector()->getNamespaceName() . '\\' . $subns;
            $subpath = str_replace('\\', '/', $subns);
            $path = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
            if (is_dir($path)) {
                $mappings[$me] = [
                    'type' => 'annotation',
                    'namespace' => $ns,
                    'path' => $path,
                    'use_simple_annotation_reader' => $simple,
                ];
            }
        }
        if (empty($mappings[$me])) {
            return [];
        }
        return [$mappings[$me]];
    }
    
    /**
     * The path of the configs.
     * @see \Quazardous\Silex\Api\ConfigurablePackInterface::getConfigsPath()
     * @return string
     */
    public function getConfigsPath()
    {
        static $paths = [];
        $me = get_class($this);
        if (empty($paths[$me])) {
            $subpath = $this->packOptions['configs_subpath'];
            $paths[$me] = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $paths[$me];
    }
    
    /**
     * The path of the assets.
     * @see \Quazardous\Silex\Api\AssetablePackInterface::getAssetsPath()
     * @return string
     */
    public function getAssetsPath()
    {
        static $paths = [];
        $me = get_class($this);
        if (empty($paths[$me])) {
            $subpath = $this->packOptions['assets_subpath'];
            $paths[$me] = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $paths[$me];
    }
    
    /**
     * No assetic formulae by default.
     * @see \Quazardous\Silex\Api\AssetablePackInterface::getAsseticFormulae()
     * @return array
     */
    public function getAsseticFormulae()
    {
        return [];
    }
    
    /**
     * The path of the translations.
     * @see \Quazardous\Silex\Api\TranslatablePackInterface::getTranslationsPath()
     * @return string
     */
    public function getTranslationsPath()
    {
        static $paths = [];
        $me = get_class($this);
        if (empty($paths[$me])) {
            $subpath = $this->packOptions['translations_subpath'];
            $paths[$me] = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $paths[$me];
    }
    
    /**
     * By default mount the pack on '/'.
     * @see \Quazardous\Silex\Api\MountablePackInterface::getMountPrefix()
     * @return string
     */
    public function getMountPrefix() {
        return $this->packOptions['mount_prefix'];
    }
    
    /**
     * No specific host by default.
     * @see \Quazardous\Silex\Api\MountablePackInterface::getMountHost()
     * @return string
     */
    public function getMountHost() {
        return $this->packOptions['mount_host'];;
    }
    
    /**
     * No controllers by default.
     * @see Silex\Api\ControllerProviderInterface::connect()
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        return $app['controllers_factory'];
    }
    
    /**
     * No Twig extension by default.
     * @see \Quazardous\Silex\Api\TwiggablePackInterface::getTwigExtensions()
     * @return (\Twig_SimpleFilter|\Twig_SimpleFunction)[]
     */
    public function getTwigExtensions()
    {
        return [];
    }
    
    /**
     * No commands by default.
     * @see \Quazardous\Silex\Api\ConsolablePackInterface::getConsoleCommands()
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands()
    {
        return [];
    }
    
    /**
     * A list of symlinks to add.
     * @see \Quazardous\Silex\Api\LinkablePackInterface::getSymlinks()
     * @return array
     */
    public function getSymlinks() 
    {
        $symlinks = [];
        if ($this->getPublicPath()) {
            $symlinks[$this->getPublicPath()] = 'packs/' . $this->_ns();
        }
        return $symlinks;
    }
    
    /**
     * By default, public is the public folder.
     * @see \Quazardous\Silex\Api\LinkablePackInterface::getPublicPath()
     * @return string
     */
    public function getPublicPath() {
        static $paths = [];
        $me = get_class($this);
        if (empty($paths[$me])) {
            $subpath = $this->packOptions['public_subpath'];
            $paths[$me] = dirname($this->getReflector()->getFileName()) . '/' . $subpath;
        }
        return $paths[$me];
    }
    
    /**
     * Returns the the pack namespace path.
     * @return string
     */
    public function getPackPath() {
        static $paths = [];
        $me = get_class($this);
        if (empty($paths[$me])) {
            $paths[$me] = dirname($this->getReflector()->getFileName());
        }
        return $paths[$me];
    }
    
    /**
     * 
     * @return \ReflectionClass
     */
    protected function getReflector() {
        static $reflectors = [];
        $me = get_class($this);
        if (empty($reflectors[$me])) {
            $reflectors[$me] = new \ReflectionClass($this);
        }
        return $reflectors[$me];
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
        static $decamelizeds = [];
        $me = get_class($this);
        if ($decamelize && empty($decamelizeds[$me])) {
            $decamelizeds[$me] = static::decamelize($this->getName());
        }
        $ns = $decamelize ? $decamelizeds[$me] : $this->getName();
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
    
}
