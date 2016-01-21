<?php
namespace Quazardous\Silex;

use Silex\Application;
use Quazardous\Silex\Api\MountablePackInterface;
use Quazardous\Silex\Api\TwiggablePackInterface;
use Quazardous\Silex\Api\EntitablePackInterface;
use Quazardous\Silex\Api\ConsolablePackInterface;
use Quazardous\Silex\Api\AssetablePackInterface;
use Quazardous\Silex\Api\TranslatablePackInterface;
use Quazardous\Silex\Api\OptionnablePackInterface;
use Pimple\ServiceProviderInterface;
use Quazardous\Silex\Console\ConsoleEvents;
use Quazardous\Silex\Console\ConsoleEvent;
use Quazardous\Silex\Api\ConfigurablePackInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Quazardous\Assetic\Factory\NamespaceAwareAssetFactory;
use Quazardous\Silex\Api\LinkablePackInterface;

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
                // handle pack's options
                $this->registerOptionnablePack($provider);
                // handle pack's entities
                // must be done before the console boot()
                $this->registerEntitablePack($provider);
                // handle pack's commands
                // must be done before the orm provider register()
                $this->registerConsolablePack($provider);
                // handle pack's assets for assetic
                $this->registerAssetablePack($provider);
            }
        }
        
        parent::boot();
        
        if (!$booted) {
            foreach ($this->providers as $provider) {
                // connect pack
                $this->registerMountablePack($provider);
                // handle twig pack
                $this->registerTwiggablePack($provider);
                // add namespace to assetic
                $this->postBootRegisterAssetablePack($provider);
                // find pack's translations
                $this->postBootRegisterTranslatablePack($provider);
            }
            // handle twig template override
            $this->addPackOverridingTemplatePathToTwig();
            
        }

    }
    
    public function register(ServiceProviderInterface $provider, array $values = array())
    {
        if ($provider instanceof ConfigurablePackInterface) {
            $this->registerOptionnablePack($provider);
            $values = \array_merge_recursive_config($this->mergeConfigsFromPath($provider->getConfigsPath()), $values);
        }
        parent::register($provider, $values);
        
        return $this;
    }
    
    public function mount($prefix, $controllers)
    {
        if ($controllers instanceof ControllerProviderInterface) {
            $connectedControllers = $controllers->connect($this);
            if (!$connectedControllers instanceof ControllerCollection) {
                throw new \LogicException(sprintf('The method "%s::connect" must return a "ControllerCollection" instance. Got: "%s"', get_class($controllers), is_object($connectedControllers) ? get_class($connectedControllers) : gettype($connectedControllers)));
            }

            if ($controllers instanceof MountablePackInterface) {
                $host = $controllers->getMountHost();
                if ($host !== null) {
                    $connectedControllers->host($host);
                }
            }
            
            $controllers = $connectedControllers;
        } elseif (!$controllers instanceof ControllerCollection) {
            throw new \LogicException('The "mount" method takes either a "ControllerCollection" or a "ControllerProviderInterface" instance.');
        }

        $this['controllers']->mount($prefix, $controllers);

        return $this;
    }
    
    protected function mergeConfigsFromPath($path) {
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
        
        if (count($configs) > 1) {
            $ids = \topological_sort($ids, $dependencies);
            if (empty($ids)) {
                throw new \RuntimeException('Cannot sort configs');
            }
        }
        
        // merge them in a logical order
        $config = [];
        foreach ($ids as $id) {
            $config = \array_merge_recursive_config($config, $configs[$id]);
        }
        
        return $config;
    }

    protected function registerOptionnablePack(ServiceProviderInterface $provider)
    {
        if ($provider instanceof OptionnablePackInterface) {
            $provider->setPackOptions($this);
        }
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
                $this['twig.loader.filesystem']->addPath($provider->getTwigTemplatesPath(), $provider->getName());
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
    
    protected function postBootRegisterTranslatablePack(ServiceProviderInterface $provider)
    {
        
        if ($provider instanceof TranslatablePackInterface) {
            if (isset($this['translator'])) {
                $path = $provider->getTranslationsPath();
                foreach (glob("$path/*.{php,xlf,yml}", GLOB_BRACE) as $filepath) {
                    $parts = pathinfo($filepath);
                    $tokens = explode('.', $parts['filename']);
                    if (count($tokens) >= 2) {
                        $locale = array_pop($tokens);
                        $domain = implode('.', $tokens);
                    } else {
                        $locale = $tokens[0];
                        $domain = null;
                    }
                    $formats = ['php' => 'array', 'xlf' => 'xliff', 'yml' => 'yaml'];
                    $format = $formats[$parts['extension']];
                    $resource = $filepath;
                    if ($format == 'array') {
                        $resource = include $filepath;
                    }
                    $this['translator']->addResource($format, $resource, $locale, $domain);
                }
            }
        }
    }
    
    protected function registerAssetablePack(ServiceProviderInterface $provider)
    {
        if ($provider instanceof AssetablePackInterface) {
            $formulae = $provider->getAsseticFormulae();
            foreach($formulae as &$formula) {
                foreach($formula[0] as &$input) {
                    if ($input[0] != '/') {
                        //relative path
                        $input = $provider->getAssetsPath() . '/' . $input;
                    }
                }
            }
            if (empty($this['assetic.formulae'])) {
                $this['assetic.formulae'] = [];
            }
            $this['assetic.formulae'] = array_merge($this['assetic.formulae'], $formulae);
        }
    }
    
    protected function postBootRegisterAssetablePack(ServiceProviderInterface $provider)
    {
        if ($provider instanceof AssetablePackInterface) {
            if (isset($this['assetic.factory'])) {
                $factory = $this['assetic.factory'];
                if ($factory instanceof NamespaceAwareAssetFactory) {
                    $factory->addNamespace($provider->getName(), $provider->getAssetsPath());
                }
            }
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
    
    public function createPackSymlinks($mode = 0755)
    {
        foreach ($this->providers as $provider) {
            if ($provider instanceof LinkablePackInterface) {
                $reflector = new \ReflectionClass($provider);
                $symlinks = $provider->getSymlinks();
                foreach ($symlinks as $source => $dest) {
                    if ($source[0] != '/') {
                        $source = dirname($reflector->getFileName()) . '/' . $source;
                    }
                    if ($dest[0] != '/') {
                        if (empty($this['path_to_web']) && empty($this['assetic.path_to_web'])) {
                            throw new \RuntimeException("Cannot determine the web folder");
                        }
                        $dest = (isset($this['path_to_web']) ? $this['path_to_web'] : $this['assetic.path_to_web']) . '/' . $dest;
                        
                        
                    }
                    mkdir(dirname($dest), $mode, true);
                    symlink($source, $dest);
                }
            }
        }
    }
}