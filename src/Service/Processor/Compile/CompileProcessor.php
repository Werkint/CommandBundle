<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Compile;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Werkint\Bundle\CommandBundle\Service\Logger\IndentedLoggerInterface;
use Werkint\Bundle\CommandBundle\Service\Processor\Compile\Exception\ProviderNotFoundException;

/**
 * CompileProcessor.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CompileProcessor implements
    CompileProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null,
        $class = null,
        $noStuff = false
    ) {
        uasort($this->providers, function ($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return ($a['priority'] > $b['priority']) ? -1 : 1;
        });
        if ($class) {
            if (!isset($this->providers[$class])) {
                throw new ProviderNotFoundException('Provider not found: ' . $class);
            }
            $provider = $this->providers[$class];
            $list = [$class => $provider];
        } else {
            $list = $this->providers;
        }
        foreach ($list as $class => $row) {
            if ($class == 'stuff' && $noStuff) {
                $logger->writeln('Stuff skipped');
                continue;
            }
            $provider = $row['provider'];
            /** @var CompileProviderInterface $provider */
            $logger->write('Processing ' . $class . ': ');
            $provider->process($logger, $command);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        return array_keys($this->providers);
    }

    // -- Providers ---------------------------------------

    protected $providers = [];

    /**
     * {@inheritdoc}
     */
    public function addProvider(
        CompileProviderInterface $provider,
        $class,
        $priority
    ) {
        $this->providers[$class] = [
            'priority' => $priority,
            'provider' => $provider,
        ];
    }
}
