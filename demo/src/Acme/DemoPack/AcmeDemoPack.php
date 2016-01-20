<?php
namespace Acme\DemoPack;

use Pimple\Container;
use Silex\Application;
use Quazardous\Silex\Pack\JetPackTrait;
use Acme\DemoPack\Controller\DefaultController;
use Acme\DemoPack\Command\FixtureCommand;
use Quazardous\Silex\Pack\JetPackInterface;

class AcmeDemoPack implements JetPackInterface
{
    // default implementations of some needed functions for the pack interfaces
    use JetPackTrait;

    // a pack is a Silex service provider
    public function register(Container $app)
    {
        // provide your controller as usual
        // To prefix your ids you can use th _ns() function provided by JetPackTrait.
        $app[$this->_ns('controller.default')] = function ($app) {
            return new DefaultController();
        };
    }

    // a pack is a Silex controller provider
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->match('/hello/{you}/{useTemplate}', $this->_ns('controller.default:hello'))
            ->value('useTemplate', true)
            ->bind($this->_ns('hello'));
        
        $controllers->match('/{_locale}/hello/{you}', $this->_ns('controller.default:hello'))
            ->value('useTemplate', true)
            ->bind($this->_ns('hello_locale'));
        
        $controllers->match('/item/{id}', $this->_ns('controller.default:item'))
            ->bind($this->_ns('item'));
        
        $controllers->match('/items', $this->_ns('controller.default:items'));
            
        $controllers->match('/foo', $this->_ns('controller.default:foo'));
        
        return $controllers;
    }

    // helper function to add Twig filters and functions
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

    // returns commands to add to the console
    public function getConsoleCommands()
    {
        return [
            new FixtureCommand()
        ];
    }

}
