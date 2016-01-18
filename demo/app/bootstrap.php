<?php

include __DIR__.'/../vendor/autoload.php';
use Acme\Application;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Acme\DemoPack\AcmeDemoPack;

$app = new Application();

$app['debug'] = true;

$app->register(new DoctrineServiceProvider, [
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/db/acme_demo.db',
    ],
]);

$app->register(new DoctrineOrmServiceProvider, [
    'orm.proxies_dir' => __DIR__ . '/cache/orm/proxies',
]);

// we register our demo pack:
// - this will mount all the controllers on the given prefix
// - this will register the pack with the given namespace in Twig
// - this will allow template override
// - this will expose the entities of the pack to Doctrine
// - this will add the pack's commands
$app->register(new AcmeDemoPack());

return $app;
