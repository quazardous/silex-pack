<?php

/**
 * 
 * @var \Silex\Application $app
 */
$app = include __DIR__.'/../app/bootstrap.php';

use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

// we register a main twig.path
// we will search for overriden template in app/views/<namespace> which is app/views/AcmeDemo for our little demo pack
$app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/views']);
$app->register(new ServiceControllerServiceProvider());

return $app;
