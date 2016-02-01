<?php
namespace Quazardous\Silex\Api;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * 
 * Very simple bundle like interface.
 *
 */
interface PackInterface extends ServiceProviderInterface
{
    /**
     * @return string the relatively unique pack name
     */
    public function getName();
}