<?php
namespace Acme\DemoPack;

use Pimple\Container;
use Quazardous\Silex\Api\MountablePackInterface;
use Silex\Application;
use Acme\DemoPack\Controller\DefaultController;
use Quazardous\Silex\Api\TwiggablePackInterface;

class AcmeDemoPack implements MountablePackInterface, TwiggablePackInterface
{

    /**
     *
     * This name will be used to register the Twig namespace.
     *
     */
    public function getName()
    {
        return 'AcmeDemo';
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    public function register(Container $app)
    {
        $app['AcmeDemo.controller.default'] = function ($app) {
            return new DefaultController();
        };
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    public function getMountPrefix()
    {
        return '/acme/demo';
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->match('/hello/{you}/{useTemplate}', 'AcmeDemo.controller.default:hello')
            ->value('useTemplate', true)
            ->bind('AcmeDemo.hello');
        
        $controllers->match('/foo', 'AcmeDemo.controller.default:foo');
        
        return $controllers;
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    public function getTwigTemplatePath()
    {
        $reflector = new \ReflectionClass($this);
        return dirname($reflector->getFileName()) . '/views';
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    public function getTwigExtensions()
    {
        return [
            new \Twig_SimpleFilter('fooize', function ($string) {
                return $string . "foo";
            }),
            new \Twig_SimpleFunction('barize', function ($string) {
                return $string . "bar";
            }),
        ];
    }

}
