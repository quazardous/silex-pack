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
            $vars['name'] = $app['acme_demo.name'];
            $vars['title'] = "Hello $you";
            return $app->renderView('@AcmeDemo/default/hello.html.twig', $vars);
        }
        return "hello $you";
    }
    
    public function foo(Application $app)
    {
       $vars = [];
       $vars['title'] = "Override test";
       return $app->renderView('@AcmeDemo/default/foo.html.twig', $vars);
    }
    
    public function item(Application $app, $id)
    {
        $vars = [];
        $vars['item'] = $app['orm.em']->getRepository('Acme\DemoPack\Entity\Item')->find($id);
        $vars['title'] = "Item $id";
        return $app->renderView('@AcmeDemo/default/item.html.twig', $vars);
    }
    
    public function items(Application $app)
    {
        $vars = [];
        $vars['items'] = $app['orm.em']->getRepository('Acme\DemoPack\Entity\Item')->findAll();
        $vars['title'] = "Items";
        return $app->renderView('@AcmeDemo/default/items.html.twig', $vars);
    }
}
