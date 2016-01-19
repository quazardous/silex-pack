<?php

namespace Quazardous\Silex\Provider;

use SilexAssetic\AsseticServiceProvider as BaseAsseticServiceProvider;

use Pimple\Container;

use Quazardous\Assetic\Factory\NamespaceAwareAssetFactory;

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
    }
}
