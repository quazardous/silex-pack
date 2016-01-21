<?php
namespace Quazardous\Silex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackSymlinksCommand extends Command
{

    protected function configure()
    {
        $this->setName('pack:symlinks');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getContainer();
        
        $app->createPackSymlinks();
    }
}
