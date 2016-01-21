<?php

set_time_limit(0);

$app = include __DIR__.'/../app/bootstrap.php';

use Quazardous\Silex\Provider\ConsoleServiceProvider;
use Quazardous\Silex\Provider\MigrationsServiceProvider;
use Quazardous\Silex\Provider\AsseticCommandsProvider;
use Quazardous\Silex\Provider\PackCommandsProvider;

$app->register(new MigrationsServiceProvider());
$app->register(new AsseticCommandsProvider());
$app->register(new PackCommandsProvider());

// should be the last
$app->register(new ConsoleServiceProvider());

$app['console']->run();
