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
    public function getMountPrefix();
    
    public function getMountHost();
}