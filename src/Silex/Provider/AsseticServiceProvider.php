<?php

namespace Quazardous\Silex\Provider;

use SilexAssetic\AsseticServiceProvider as BaseAsseticServiceProvider;

use Pimple\Container;

use Quazardous\Assetic\Factory\NamespaceAwareAssetFactory;
use Quazardous\Assetic\WatchingDumper;
use Assetic\Extension\Twig\AsseticExtension;

class AsseticServiceProvider extends BaseAsseticServiceProvider
{
    public function register(Container $app)
    {
        parent::register($app);
        
        // we need to inject an asset "namespace aware" factory
        $app['assetic.factory'] = function () use ($app) {
            $root = isset($app['assetic.path_to_source']) ? $app['assetic.path_to_source'] : $app['assetic.path_to_web'];
            $factory = new NamespaceAwareAssetFactory($root, $app['assetic.options']['debug']);
            $factory->setAssetManager($app['assetic.asset_manager']);
            $factory->setFilterManager($app['assetic.filter_manager']);

            return $factory;
        };
        
        // we need a dumper which can decide not to dump
        $app['assetic.dumper'] = function () use ($app) {
            $dumper = new WatchingDumper(
                $app['assetic.asset_manager'],
                $app['assetic.lazy_asset_manager'],
                $app['assetic.asset_writer'],
                $app['assetic.path_to_web']
                );
            if (isset($app['twig'])) {
                $dumper->setTwig($app['twig'], $app['twig.loader.filesystem']);
            }
            return $dumper;
        };
    }
}
