<?php
namespace Quazardous\Silex;

use Silex\Application;
use Quazardous\Silex\Api\MountablePackInterface;
use Quazardous\Silex\Api\TwiggablePackInterface;
use Quazardous\Silex\Api\EntitablePackInterface;
use Quazardous\Silex\Api\ConsolablePackInterface;
use Pimple\ServiceProviderInterface;
use Quazardous\Silex\Console\ConsoleEvents;
use Quazardous\Silex\Console\ConsoleEvent;
use Quazardous\Silex\Api\ConfigurablePackInterface;

/**
 * Application which knows how to handle packs.
 */
class PackableApplication extends Application
{

    public function boot()
    {
        $booted = $this->booted;
        
        if (!$booted) {
            foreach ($this->providers as $provider) {
                // handle pack's entities
                // must be done before the console boot()
                $this->registerEntitablePack($provider);
                // handle pack's commands
                // must be done before the orm provider register()
                $this->registerConsolablePack($provider);
            }
        }
        
        parent::boot();
        
        if (!$booted) {
            foreach ($this->providers as $provider) {
                // connect pack
                $this->registerMountablePack($provider);
                // handle twig pack
                $this->registerTwiggablePack($provider);
            }
            // handle twig template override
            $this->addPackOverridingTemplatePathToTwig();
        }

    }
    
    public function register(ServiceProviderInterface $provider, array $values = array())
    {
        if ($provider instanceof ConfigurablePackInterface) {
            $values = \array_merge_recursive_distinct($this->mergeConfigs($provider->getConfigsPath()), $values);
        }
        parent::register($provider, $values);
        
        return $this;
    }
    
    protected function mergeConfigs($path) {
        $configs = [];
        $ids = [];
        $dependencies = [];
        
        // read the differents config files
        foreach (glob($path . '/*.config.php') as $configFile) {
            $id = basename($configFile, '.config.php');
            $configs[$id] = include $configFile;
            $ids[] = $id;
            if (isset($configs[$id]['_import'])) {
                $dependencies[] = [$configs[$id]['_import'], $id];
                unset($configs[$id]['_import']);
            }
        }
        
        $ids = \topological_sort($ids, $dependencies);
        if (empty($ids)) {
            throw new \RuntimeException('Cannot sort configs');
        }
        
        // merge them in a logical order
        $config = [];
        foreach ($ids as $id) {
            $config = \array_merge_recursive_distinct($config, $configs[$id]);
        }
        
        return $config;
    }

    protected function registerMountablePack(ServiceProviderInterface $provider)
    {
        if ($provider instanceof MountablePackInterface) {
            $this->mount($provider->getMountPrefix(), $provider);
        }
    }

    protected function registerTwiggablePack(ServiceProviderInterface $provider)
    {
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
    
    protected function registerEntitablePack(ServiceProviderInterface $provider)
    {
        if ($provider instanceof EntitablePackInterface) {
            if (empty($this['orm.em.options'])) {
                $options = [];
            } else {
                $options = $this['orm.em.options'];
            }
            if (empty($options['mappings'])) {
                $options['mappings'] = [];
            }
            $options['mappings'] += $provider->getEntityMappings();
            $this['orm.em.options'] = $options;
        }
    }
    
    protected function registerConsolablePack(ServiceProviderInterface $provider)
    {
        if ($provider instanceof ConsolablePackInterface) {
            $this['dispatcher']->addListener(ConsoleEvents::INIT, function (ConsoleEvent $event) use($provider) {
                $console = $event->getConsole();
                
                foreach ($provider->getConsoleCommands() as $command) {
                    $console->add($command);
                }
            });
        }
    }
    
    protected function addPackOverridingTemplatePathToTwig()
    {
        if (isset($this['twig.loader.filesystem'])) {
            // for each path in the main Twig namespace we will search for sub folder with the name of the other namespaces
            // we will consider that as a possible folder of overriden templates...
            $namespaces = array_diff($this['twig.loader.filesystem']->getNamespaces(), [
                \Twig_Loader_Filesystem::MAIN_NAMESPACE
            ]);
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