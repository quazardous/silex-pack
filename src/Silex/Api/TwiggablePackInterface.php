<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Silex\Api\ControllerProviderInterface;

/**
 * 
 * Application will register the given path with the pack name twig namespace.
 * 
 * @link http://twig.sensiolabs.org/doc/api.html
 *
 */
interface TwiggablePackInterface extends PackInterface, ControllerProviderInterface
{
    /**
     * The path of the templates.
     */
    public function getTwigTemplatePath();
    
    /**
     * Return an array of Twig_SimpleFilter or Twig_SimpleFunction to register with Twig.
     */
    public function getTwigExtensions();
}