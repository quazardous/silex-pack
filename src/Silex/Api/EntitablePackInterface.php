<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;

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
    public function getEntityMappings();
}