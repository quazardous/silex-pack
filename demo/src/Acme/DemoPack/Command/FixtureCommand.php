<?php

namespace Acme\DemoPack\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class FixtureCommand extends Command
{
    protected function configure()
    {
        $this->setName('acme:demo:fixture')
            ->setDescription('Will purge and load some data.');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('The database will be purged before loading. Continue?', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $app = $this->getApplication()->getContainer();
        
        $loader = new Loader();
        
        $dir = __DIR__ . '/../fixtures';

        $fixtures = $loader->loadFromDirectory($dir);
        
        $purger = new ORMPurger();
        $executor = new ORMExecutor($app['orm.em'], $purger);
        $executor->execute($fixtures, false);
    }
}
