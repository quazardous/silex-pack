<?php

/**
 * 
 * @var \Silex\Application $app
 */
$app = include __DIR__.'/../app/bootstrap.php';

use Silex\Provider\ServiceControllerServiceProvider;

$app->register(new ServiceControllerServiceProvider());

$app['assets_url'] = 'assets';

$app['twig'] = $app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        // implement whatever logic you need to determine the asset path
        $base = isset($app['request']) && $app['request'] ? $app['request']->getBasePath() . '/' : '';
        $base .= trim($app['assets_url'], '/') . '/';
        return sprintf('%s%s', $base, ltrim($asset, '/'));
    }));

    return $twig;
});

// Silex 1.x style
$app['request'] = $app->factory(function ($app) {
    return $app['request_stack']->getCurrentRequest();
});

return $app;
