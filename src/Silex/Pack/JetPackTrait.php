<?php
namespace Quazardous\Silex\Pack;

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
    
    /**
     * You can define the templates subpath. It's 'views' by default.
     * @const static::TWIG_TEMPLATES_SUBPATH
     */
    //const TWIG_TEMPLATES_SUBPATH = 'views';
    
    /**
     * You can define the entity subnamespace. It's 'Entity' by default.
     * @const static::ENTITY_SUBNAMESPACE
     */
    //const ENTITY_SUBNAMESPACE = 'Entity';

    /**
     * You can define the 'use_simple_annotation_reader'. It's true by default.
     * @const static::ENTITY_USE_SIMPLE_ANNOTATION
     * @link https://github.com/dflydev/dflydev-doctrine-orm-service-provider
     */
    //const ENTITY_USE_SIMPLE_ANNOTATION = true;
    
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
    public function getTwigTemplatePath()
    {
        static $path = null;
        if (empty($path)) {
            $subpath = defined('static::TWIG_TEMPLATES_SUBPATH') ? static::TWIG_TEMPLATES_SUBPATH : 'views';
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
    public function _ns($id, $decamelize = true, $separator = '.') {
        static $decamelized = null;
        if ($decamelize && empty($decamelized)) {
            $decamelized = static::decamelize($this->getName());
        }
        $ns = $decamelize ? $decamelized : $this->getName();
        return $ns . $separator . $id;
    }
    
    /**
     * Decamelize the given string.
     * @param string $input
     * @return string
     */
    public static function decamelize($input) {
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
            $subns = defined('static::ENTITY_SUBNAMESPACE') ? static::ENTITY_SUBNAMESPACE : 'Entity';
            $subns = trim($subns, '\\');
            $simple = defined('static::ENTITY_USE_SIMPLE_ANNOTATION') ? static::ENTITY_USE_SIMPLE_ANNOTATION : true;
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
}