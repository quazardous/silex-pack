<?php

/**
 * 
 * @var \Silex\Application $app
 */
$app = include __DIR__.'/../app/bootstrap.php';

use Silex\Provider\ServiceControllerServiceProvider;

$app->register(new ServiceControllerServiceProvider());

return $app;
