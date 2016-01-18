<?php
// config doctrine console

$app = include __DIR__ . '/app/bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$app->boot();

return ConsoleRunner::createHelperSet($app['orm.em']);
