<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

/**
 * @template V of object|scalar|null
 *
 * @extends DMap<int, V>
 */
class DIntMap extends DMap
{
    /**
     * @return array<int, V>
     */
    public function toArray(): array
    {
        return array_combine($this->getKeysArray(), $this->getValuesArray());
    }
}
