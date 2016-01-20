<?php
namespace Quazardous\Assetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends Command
{

    protected function configure()
    {
        $this->setName('assetic:dump');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getContainer();
        
        // Register our filters to use
        if (isset($app['assetic.filters']) && is_callable($app['assetic.filters'])) {
            $app['assetic.filters']($app['assetic.filter_manager']);
        }
        
        // Boot assetic
        $app['assetic'];
        
        /**
         * 
         * @var \SilexAssetic\Assetic\Dumper $dumper
         */
        $dumper = $app['assetic.dumper'];
        if (isset($app['twig'])) {
            $dumper->addTwigAssets();
        }
        
        $dumper->dumpAssets();
    }
}
