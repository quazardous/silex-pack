<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Silex\Api\ControllerProviderInterface;

/**
 * 
 * Will mount the ControllerProvider on the given prefix and the given host (optional).
 *
 */
interface MountablePackInterface extends PackInterface, ControllerProviderInterface
{
    /**
     * The path prefix where to mount the crontrollers returned by connect()
     * @return string
     */
    public function getMountPrefix();
    
    /**
     * A host to add to all the controllers.
     * @return string
     */
    public function getMountHost();
}