<?php
namespace Werkint\Bundle\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Werkint\Bundle\CommandBundle\Service\Logger\IndentedLogger;
use Werkint\Bundle\CommandBundle\Service\Processor\Stuff\StuffProcessorInterface;

/**
 * StuffCommand.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StuffCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('werkint:stuff')
            ->addArgument('class')
            ->addOption('list')
            ->addOption('all')
            ->setDescription('Process site stuff');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->initHacks();

        if ($input->getOption('list')) {
            $list = $this->serviceStuff()->getList();
            $output->writeln('Available commands:');
            foreach ($list as $row) {
                $output->writeln('    ' . $row);
            }
        } else {
            $class = $input->getArgument('class');
            $logger = new IndentedLogger(function ($text) use ($output) {
                $output->write($text);
            });
            $this->serviceStuff()->process(
                $logger,
                $this,
                $class,
                $input->getOption('all') ? 1 : false
            );
        }
    }

    // -- Services ---------------------------------------

    /**
     * @return StuffProcessorInterface
     */
    protected function serviceStuff()
    {
        return $this->getContainer()->get('werkint.command.processor.stuff');
    }

    // -- Hacks ---------------------------------------

    protected function initHacks()
    {
        date_default_timezone_set($this->getContainer()->getParameter('timezone'));
        $this->getContainer()->enterScope('request');
        $this->getContainer()->set('request', new Request(), 'request');
    }
}
