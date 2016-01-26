<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;
use Pimple\Container;

/**
 * 
 * Makes the pack nearly fully configurable from application container.
 *
 */
interface OptionnablePackInterface extends PackInterface
{
    /**
     * Try to get the pack options from the application container.
     * @param \Pimple\Container $app
     */
    public function setPackOptions(Container $app);

}