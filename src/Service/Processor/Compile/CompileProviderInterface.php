<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Compile;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLoggerInterface;

/**
 * CompileProviderInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface CompileProviderInterface
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
