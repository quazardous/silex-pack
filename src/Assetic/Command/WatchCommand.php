<?php

namespace Quazardous\Assetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Quazardous\Assetic\WatchingDumper;

class WatchCommand extends Command
{
    protected function configure()
    {
        $this->setName('assetic:watch');
    }

    protected function execute(InputInterface $input, OutputInterface $stdout)
    {
        $app = $this->getApplication()->getContainer();
        
        // Register our filters to use
        if (isset($app['assetic.filters']) && is_callable($app['assetic.filters'])) {
            $app['assetic.filters']($app['assetic.filter_manager']);
        }
        
        // Boot assetic
        $assetic = $app['assetic'];
        
        /**
         *
         * @var \Quazardous\Assetic\WatchingDumper $dumper
         */
        $dumper = $app['assetic.dumper'];
        if (isset($app['twig'])) {
            $dumper->addTwigAssets();
        }
        if (!$dumper instanceof WatchingDumper) {
            throw new \RuntimeException("Dumper must extends Quazardous\\Assetic\\WatchingDumper");
        }
        $dumper->watch($stdout);
        
        while (true) {
            $dumper->dumpAssets();
            clearstatcache();
            gc_collect_cycles();
            sleep(5);
            
            // try to avoid memory leak...
            $app['assetic.lazy_asset_manager']->clear();
            $class = new \ReflectionClass($app['assetic.lazy_asset_manager']);
            $property = $class->getProperty("resources");
            $property->setAccessible(true);
            $property->setValue($app['assetic.lazy_asset_manager'], []);
            
            // reload templates
            if (isset($app['twig'])) {
                $dumper->addTwigAssets();
            }
        }
    }
}
