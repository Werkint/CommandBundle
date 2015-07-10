<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Werkint\Bundle\CommandBundle\Service\Logger\IndentedLoggerInterface;

/**
 * StuffProviderInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface StuffProviderInterface
{
    /**
     * @param IndentedLoggerInterface $logger
     * @param ContainerAwareCommand   $command
     * @return mixed
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null
    );
}
