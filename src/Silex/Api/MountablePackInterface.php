<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Silex\Api\ControllerProviderInterface;

/**
 * 
 * Will mount the ControllerProvider on the given prefix.
 *
 */
interface MountablePackInterface extends PackInterface, ControllerProviderInterface
{
    public function getMountPrefix();
}