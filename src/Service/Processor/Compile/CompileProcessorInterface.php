<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Compile;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLoggerInterface;

/**
 * CompileProcessorInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface CompileProcessorInterface
{
    /**
     * @param IndentedLoggerInterface $logger
     * @param ContainerAwareCommand   $command
     * @param null|string             $class
     * @param bool                    $noStuff
     * @return mixed
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null,
        $class = null,
        $noStuff = false
    );

    /**
     * @return array[]
     */
    public function getList();

    /**
     * @param CompileProviderInterface $provider
     * @param string                   $class
     * @param int                      $priority
     * @return mixed
     */
    public function addProvider(
        CompileProviderInterface $provider,
        $class,
        $priority
    );
}