<?php
namespace Quazardous\Silex;

use Silex\Application;
use Quazardous\Silex\Api\MountablePackInterface;
use Quazardous\Silex\Api\TwiggablePackInterface;

/**
 * Application which knows how to handle packs.
 */
class PackableApplication extends Application
{

    public function boot() {
        $booted = $this->booted;
        
        parent::boot();
        
        if ($booted) return;
        
        foreach ($this->providers as $provider) {
            // connect the controller provider
            if ($provider instanceof MountablePackInterface) {
                $this->mount($provider->getMountPrefix(), $provider);
            }
        
            // handle twig
            if ($provider instanceof TwiggablePackInterface) {
                if (isset($this['twig.loader.filesystem'])) {
                    $this['twig.loader.filesystem']->addPath($provider->getTwigTemplatePath(), $provider->getName());
                }
                if (isset($this['twig'])) {
                    foreach ($provider->getTwigExtensions() as $extension) {
                        if ($extension instanceof \Twig_SimpleFilter) {
                            $this['twig']->addFilter($extension);
                        } elseif ($extension instanceof \Twig_SimpleFunction) {
                            $this['twig']->addFunction($extension);
                        }
                    }
                }
            }
        }
        // handle twig template override
        if (isset($this['twig.loader.filesystem'])) {
            // for each path in the main Twig namespace we will search for sub folder with the name of the other namespaces
            // we will consider that as a possible folder of overriden templates...
            $namespaces = array_diff($this['twig.loader.filesystem']->getNamespaces(), [\Twig_Loader_Filesystem::MAIN_NAMESPACE]);
            foreach ($this['twig.loader.filesystem']->getPaths() as $path) {
                foreach ($namespaces as $ns) {
                    $dir = $path . '/' . $ns;
                    if (is_dir($dir)) {
                        $this['twig.loader.filesystem']->prependPath($dir, $ns);
                    }
                }
            }
        }
    }
}