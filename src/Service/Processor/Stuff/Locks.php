<?php
namespace Werkint\Bundle\CommandBundle\Service\Processor\Stuff;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Locks.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Locks
{
    protected $cacher;
    protected $ticks;

    public function __construct(
        CacheProvider $cacher
    ) {
        $this->cacher = $cacher;

        $this->ticks = (int)$this->cacher->fetch('ticks');
    }

    public function getTicks()
    {
        return $this->ticks;
    }

    public function addTick()
    {
        $this->ticks++;
        $this->cacher->save('ticks', $this->ticks);

        return $this->getTicks();
    }
}