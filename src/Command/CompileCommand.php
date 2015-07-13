<?php
namespace Werkint\Bundle\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Werkint\Bundle\CommandBundle\Service\Processor\Compile\CompileProcessorInterface;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLogger;

/**
 * CompileCommand.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CompileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('werkint:compile')
            ->addArgument('class')
            ->addOption('list')
            ->addOption('no-stuff')
            ->setDescription('Compile site resources');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        if ($input->getOption('list')) {
            $list = $this->serviceCompile()->getList();
            $output->writeln('Available commands:');
            foreach ($list as $row) {
                $output->writeln('    ' . $row);
            }
        } else {
            $class = $input->getArgument('class');
            $noStuff = $input->getOption('no-stuff');
            $logger = new IndentedLogger(function ($text) use ($output) {
                $output->write($text);
            }, $output->getVerbosity() === $output::VERBOSITY_VERBOSE);
            $this->serviceCompile()->process(
                $logger, $this, $class, (bool)$noStuff
            );
        }
    }

    // -- Services ---------------------------------------

    /**
     * @return CompileProcessorInterface
     */
    protected function serviceCompile()
    {
        return $this->getContainer()->get('werkint_command.compile');
    }
}
