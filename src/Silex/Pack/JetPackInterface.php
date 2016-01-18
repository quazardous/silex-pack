<?php
namespace Quazardous\Silex\Pack;

use Quazardous\Silex\Api\ConsolablePackInterface;
use Quazardous\Silex\Api\EntitablePackInterface;
use Quazardous\Silex\Api\MountablePackInterface;
use Quazardous\Silex\Api\TwiggablePackInterface;
use Quazardous\Silex\Api\ConfigurablePackInterface;

/**
 * 
 * All in one interface.
 *
 */
interface JetPackInterface extends
    ConsolablePackInterface,
    EntitablePackInterface,
    MountablePackInterface,
    TwiggablePackInterface,
    ConfigurablePackInterface
{
}