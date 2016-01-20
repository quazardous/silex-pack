<?php

include __DIR__.'/../vendor/autoload.php';
use Acme\Application;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Acme\DemoPack\AcmeDemoPack;
use Silex\Provider\TwigServiceProvider;
use Quazardous\Silex\Provider\AsseticServiceProvider;
use Assetic\Filter\Yui\CssCompressorFilter;
use Acme\Command\AsseticCommand;
use Quazardous\Silex\Console\ConsoleEvent;
use Quazardous\Silex\Console\ConsoleEvents;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\TranslationServiceProvider;

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

// we register a main twig.path
// we will search for overriden template in app/views/<namespace> which is app/views/AcmeDemo for our little demo pack
$app->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/views']);

$app->register(new AsseticServiceProvider(),
    [
        'assetic.path_to_web' => __DIR__ . '/../web/assets',
        'assetic.options' => ['debug' => false],
    ]
);

$app->extend('assetic.filter_manager', function ($fm, $app) {
    $fm->set('yui_css', new CssCompressorFilter(__DIR__ . '/../vendor/bin/yuicompressor.jar'));
    
    return $fm;
});

// this will make use of the magic _locale url parameter
$app->register(new LocaleServiceProvider);

$app->register(new TranslationServiceProvider(), [
    'locale' => 'fr',
    'locale_fallbacks' => ['en'],
]);

$app['dispatcher']->addListener(ConsoleEvents::INIT, function (ConsoleEvent $event) use ($app) {
    $console = $event->getConsole();
    $console->add(new AsseticCommand());
});

// we register our demo pack:
// - this will mount all the controllers on the given prefix
// - this will register the pack with the given namespace in Twig
// - this will allow template override
// - this will expose the entities of the pack to Doctrine
// - this will add the pack's commands
// - this will add assetic stuff
// - this will ass translation stuff
$app->register(new AcmeDemoPack(), [
    'acme_demo.mount_prefix' => '/acme/demo',
]);

return $app;
