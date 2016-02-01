<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Pimple\Container;

/**
 * 
 * Application will add entity mapping to orm.em.
 *
 */
interface EntitablePackInterface extends PackInterface
{
    /**
     * Should return a correct array of mapping as describe in composer require dflydev/doctrine-orm-service-provider.
     * It won't work if you use $app['orm.em'] before the $app->boot() or $app->boot() is not called.
     * 
     * @link https://github.com/dflydev/dflydev-doctrine-orm-service-provider
     * @return array
     */
    public function getEntityMappings(Container $app);
    
    /**
     * Returns an associative array mapping interface to real class.
     * 
     * @link https://github.com/doctrine/doctrine2/blob/master/docs/en/cookbook/resolve-target-entity-listener.rst
     * @return array
     */
    public function getTargetEntitesMapping(Container $app);
}