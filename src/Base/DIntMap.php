<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

/**
 * @template V of object|scalar|null
 *
 * @extends DMap<int, V>
 *
 * @implements \IteratorAggregate<int, V>
 */
class DIntMap extends DMap implements \IteratorAggregate
{
    /**
     * @return array<int, V>
     */
    public function toArray(): array
    {
        return array_combine($this->getKeysArray(), $this->getValuesArray());
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->toArray());
    }
}
