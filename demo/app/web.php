<?php

/**
 * 
 * @var \Silex\Application $app
 */
$app = include __DIR__.'/../app/bootstrap.php';

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Acme\DemoPack\AcmeDemoPack;

// we register a main twig.path
// we will search for overriden template in app/views/<namespace> which is app/views/AcmeDemo for our little demo pack
$app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/views']);
$app->register(new ServiceControllerServiceProvider());

// we register our demo pack:
// - this will mount all the controllers on the given prefix
// - this will register the pack with the given namespace in Twig
// - this will allow template override
$app->register(new AcmeDemoPack());

return $app;
