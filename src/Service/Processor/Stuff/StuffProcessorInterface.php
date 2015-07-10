<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\CommandBundle\Service\Logger\IndentedLoggerInterface;

/**
 * StuffProcessorInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface StuffProcessorInterface
{
    /**
     * @param IndentedLoggerInterface $logger
     * @param ContainerAwareCommand   $command
     * @param null                    $class
     * @param bool                    $tickin
     * @return mixed
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null,
        $class = null,
        $tickin = false
    );

    /**
     * @return array[]
     */
    public function getList();

    /**
     * @param StuffProviderInterface $provider
     * @param string                 $class
     * @param int                    $priority
     * @param int                    $tick
     * @return mixed
     */
    public function addProvider(
        StuffProviderInterface $provider,
        $class,
        $priority,
        $tick
    );

}