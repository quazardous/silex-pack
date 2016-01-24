<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;

/**
 * 
 * Packs can add symlinks in the project.
 * The main use case is to add symlinks in the web folder.
 *
 */
interface LinkablePackInterface extends PackInterface
{
    /**
     * A list of symlinks to add.
     * <code>
     * [
     *     '/absolute/path/of/source' => '/path/of/destination',
     *     'relative/path/of/source' => '/path/of/destination',
     *     // if you provide a relative source path, it will be prefixed with the pack namespace path.
     *     'path/of/source' => 'relative/path/of/destination',
     *     //  if you provide a relative dest path, it will be prefixed with $app['path_to_web'] or $app['assetic.path_to_web'].
     * ]
     * </code>
     */
    public function getSymlinks();
    
    /**
     * If given, the public path will be automatically symlinked to $app['path_to_web']/packs/<pack_ns> or $app['assetic.path_to_web']/packs/<pack_ns>
     */
    public function getPublicPath();
}