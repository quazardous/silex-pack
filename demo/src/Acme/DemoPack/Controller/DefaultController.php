<?php

namespace Acme\DemoPack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController
{
    public function hello(Application $app, Request $request, $you, $useTemplate)
    {
        if ($useTemplate) {
            $vars = [];
            $vars['you'] = $you;
            return $app->renderView('@AcmeDemo/default/hello.html.twig', $vars);
        }
        return "hello $you";
    }
    
    public function foo(Application $app)
    {
       return $app->renderView('@AcmeDemo/default/foo.html.twig');
    }
    
    
}
