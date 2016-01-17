<?php
namespace Quazardous\Silex\Api;

use Pimple\ServiceProviderInterface;

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