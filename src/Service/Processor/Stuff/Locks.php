<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Werkint\Bundle\CacheBundle\Service\Annotation\CacheAware;
use Werkint\Bundle\CacheBundle\Service\Contract\CacheAwareInterface;
use Werkint\Bundle\CacheBundle\Service\Contract\CacheAwareTrait;

/**
 * TODO: remove
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @CacheAware(namespace="werkint_stuff.locks")
 */
class Locks implements
    CacheAwareInterface
{
    use CacheAwareTrait;

    protected $ticks = null;

    public function getTicks()
    {
        if ($this->ticks === null) {
            $this->ticks = (int)$this->cacheProvider->fetch('ticks');
        }

        return $this->ticks;
    }

    public function addTick()
    {
        $this->ticks++;
        $this->cacheProvider->save('ticks', $this->ticks);

        return $this->getTicks();
    }
}