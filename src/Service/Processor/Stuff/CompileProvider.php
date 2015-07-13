<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\CommandBundle\Service\Processor\Compile\CompileProviderInterface;
use Werkint\Bundle\CommandBundle\Service\Processor\Stuff\Exception\NoProvidersException;
use Werkint\Bundle\FrameworkExtraBundle\Service\Logger\IndentedLoggerInterface;

/**
 * CompileProvider.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CompileProvider implements
    CompileProviderInterface
{
    protected $stuff;

    /**
     * @param StuffProcessorInterface $stuff
     */
    public function __construct(
        StuffProcessorInterface $stuff
    ) {
        $this->stuff = $stuff;
    }

    /**
     * {@inheritdoc}
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null
    ) {
        $logger->writeln('running update');
        $logger->indent();
        try {
            $this->stuff->process($logger, $command, null, false, 1);
        } catch (NoProvidersException $e) {
            $logger->writeln('Stuff skipped as not providers found' . PHP_EOL);
        }
        $logger->outdent();
    }
}
