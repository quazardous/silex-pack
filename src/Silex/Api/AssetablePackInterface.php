<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\TwiggablePackInterface;
use Pimple\Container;

/**
 * Inject the given formulae to assetic.
 * 
 * You will need to use the provided Assetic provider because we have to tweak the assetic factory to handle @MyNamespace in assets.
 * 
 * @see Quazardous\Silex\Provider\AsseticServiceProvider
 * 
 * @link https://github.com/mheap/Silex-Assetic
 */
interface AssetablePackInterface extends TwiggablePackInterface
{
    /**
     * The formulae will be append to $app['assetic.formulae'] and added to the LazyAssetManager.
     * <code>
     * [
     *    ...
     *    'my_pack_css' => [
     *        ['/path/to/assets/css/*.css'], // inputs
     *        ['yui_css'], // filters
     *        ['output' => 'css/my'], // options
     *     ],
     *     ...
     * ]
     * </code>
     * @return array[name]array list of formula
     */
    public function getAsseticFormulae(Container $app);
    
    /**
     * If you return relative input paths in the formulae, the application will prefix them with getAssetsPath().
     * @return array
     */
    public function getAssetsPath(Container $app);
    
}