<?php

namespace Quazardous\Silex\Provider;

use Quazardous\Silex\Console\ConsoleEvents;
use Quazardous\Silex\Console\ConsoleEvent;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Quazardous\Silex\Command;

/**
 * 
 * Provides Assetic commands.
 *
 */
class PackCommandsProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
        // nothing
    }
    
    public function boot(Application $app) {
        $app['dispatcher']->addListener(ConsoleEvents::INIT, function (ConsoleEvent $event) use ($app) {
            $console = $event->getConsole();
        
            $commands = [
                new Command\PackSymlinksCommand(),
            ];
        
            foreach ($commands as $command) {
                $console->add($command);
            }
        });
    }
}
