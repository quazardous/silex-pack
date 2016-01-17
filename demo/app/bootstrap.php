<?php

include __DIR__.'/../vendor/autoload.php';
use Acme\Application;

$app = new Application();

$app['debug'] = true;

return $app;
