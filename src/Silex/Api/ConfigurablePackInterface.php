<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;

/**
 * 
 * Application will inject the config files from the given path.
 * 
 * Each config file must be named <something>.config.php.
 * It must return a single associative array.
 * 
 * <code>
 * return [
 *     'key1' => 'value',
 *     'key2' => 'value',
 *     ...
 * ]
 * </code>
 * 
 * You can use the special key '_import' => 'foo' to include the foo.config.php. This can be usefull to specify an order for multiple configs.
 * 
 * All the keys will be injected into the application container.
 *
 */
interface ConfigurablePackInterface extends PackInterface
{
    /**
     * The path of the configs.
     */
    public function getConfigsPath();
    
}