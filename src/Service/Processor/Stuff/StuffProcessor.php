<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Werkint\Bundle\CommandBundle\Service\Logger\IndentedLoggerInterface;
use Werkint\Bundle\CommandBundle\Service\Processor\Stuff\Exception\NoProvidersException;
use Werkint\Bundle\CommandBundle\Service\Processor\Stuff\Exception\ProviderNotFoundException;
use Werkint\Bundle\MutexBundle\Service\MutexManagerInterface;

/**
 * StuffProcessor.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StuffProcessor implements
    StuffProcessorInterface
{
    const LOCK_PREFIX = 'werkint_framework_extra.stuff.';
    const LOCK_TIMEOUT = 4;

    protected $locks;
    protected $ticker;

    /**
     * @param MutexManagerInterface $locks
     * @param Locks                 $ticker
     */
    public function __construct(
        MutexManagerInterface $locks,
        Locks $ticker
    ) {
        $this->locks = $locks;
        $this->ticker = $ticker;
    }

    /**
     * {@inheritdoc}
     */
    public function process(
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null,
        $class = null,
        $tickin = false
    ) {
        $tick = $tickin;

        if (!count($this->providers)) {
            throw new NoProvidersException('No providers found');
        }

        if (!$class) {
            if (!$tick) {
                $tick = $this->ticker->addTick();
            }
            if (!$tickin) {
                $logger->writeln('Current tick is ' . $tick);
            } else {
                $logger->writeln('Forcing tick to ' . $tick);
            }
        }

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
            $list = [$class => $this->providers[$class]];
        } else {
            $list = $this->providers;
        }

        foreach ($list as $pclass => $provider) {
            if (!$class && $provider['tick'] && $tick > 1 && ($tick % $provider['tick']) !== 0) {
                continue;
            }

            $lockName = static::LOCK_PREFIX . $pclass;
            if (!$this->locks->lock($lockName, static::LOCK_TIMEOUT)) {
                $logger->writeln(sprintf('%s is locked, skipping', $pclass));
                continue;
            }

            $provider = $provider['service'];
            /** @var StuffProviderInterface $provider */

            try {
                $this->processProvider($provider, $logger, $command);
            } catch (\Exception $e) {
                $logger->writeln('EXCEPTION: ' . $e->getMessage() . ', skipping');
                // TODO: log exceptions
            }

            $this->locks->unlock($lockName);
        }
    }

    /**
     * @param StuffProviderInterface     $provider
     * @param IndentedLoggerInterface    $logger
     * @param ContainerAwareCommand|null $command
     */
    protected function processProvider(
        StuffProviderInterface $provider,
        IndentedLoggerInterface $logger,
        ContainerAwareCommand $command = null
    ) {
        $provider->process(
            $logger,
            $command
        );
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
        StuffProviderInterface $provider,
        $class,
        $priority,
        $tick
    ) {
        $this->providers[$class] = [
            'service'  => $provider,
            'priority' => $priority,
            'tick'     => $tick,
        ];
    }

}
