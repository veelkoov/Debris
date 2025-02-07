<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\StringSet;

/**
 * @template V of object|scalar|null
 *
 * @extends DMap<string, V>
 *
 * @implements \IteratorAggregate<string, V>
 */
class DStringMap extends DMap implements \IteratorAggregate
{
    public function getKeys(): StringSet
    {
        return new StringSet(parent::getKeysArray());
    }

    /**
     * @return array<string, V>
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
