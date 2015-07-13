<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLoggerInterface;

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
