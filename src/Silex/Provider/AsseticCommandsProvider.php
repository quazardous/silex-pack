<?php

namespace Quazardous\Silex\Provider;

use Quazardous\Silex\Console\ConsoleEvents;
use Quazardous\Silex\Console\ConsoleEvent;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Quazardous\Assetic\Command;

/**
 * 
 * Provides Assetic commands.
 *
 */
class AsseticCommandsProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
        // nothing
    }
    
    public function boot(Application $app) {
        $app['dispatcher']->addListener(ConsoleEvents::INIT, function (ConsoleEvent $event) use ($app) {
            $console = $event->getConsole();
        
            $commands = [
                new Command\DumpCommand(),
                new Command\WatchCommand(),
            ];
        
            foreach ($commands as $command) {
                $console->add($command);
            }
        });
    }
}
