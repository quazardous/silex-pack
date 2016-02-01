<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Pimple\Container;

/**
 * 
 * Application will register the given path with the pack name twig namespace.
 * 
 * @link http://twig.sensiolabs.org/doc/api.html
 *
 */
interface TwiggablePackInterface extends PackInterface
{
    /**
     * The path of the templates.
     * @return string
     */
    public function getTwigTemplatesPath(Container $app);
    
    /**
     * Return an array of Twig_SimpleFilter or Twig_SimpleFunction to register with Twig.
     * @return (\Twig_SimpleFilter|\Twig_SimpleFunction)[]
     */
    public function getTwigExtensions(Container $app);
}