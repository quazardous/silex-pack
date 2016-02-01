<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Pimple\Container;

/**
 * 
 * Application will add the pack's commands.
 * 
 *
 */
interface ConsolablePackInterface extends PackInterface
{
    /**
     * Should return a correct array of mapping as describe in composer require dflydev/doctrine-orm-service-provider.
     * It won't work if $app->boot() is not called.
     * 
     * @link https://github.com/quazardous/silex-console
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands(Container $app);
}