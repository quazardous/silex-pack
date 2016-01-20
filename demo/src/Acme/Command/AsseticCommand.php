<?php
namespace Acme\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AsseticCommand extends Command
{

    protected function configure()
    {
        $this->setName('assetic:dump');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getContainer();
        
        /**
         * 
         * @var \SilexAssetic\Assetic\Dumper $dumper
         */
        $dumper = $app['assetic.dumper'];
//         if (isset($app['twig'])) {
//             $dumper->setTwig($app['twig'], $app['twig.loader.filesystem']);
//             $dumper->addTwigAssets();
//         }
        
        $dumper->dumpAssets();
    }
}
