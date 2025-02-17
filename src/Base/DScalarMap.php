<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\DMap;

/**
 * @template K of string|int
 * @template V of object|scalar|null
 *
 * @extends DMap<K, V>
 */
class DScalarMap extends DMap
{
    /**
     * @return array<K, V>
     */
    public function toArray(): array
    {
        return array_combine($this->getKeysArray(), $this->getValuesArray());
    }
}
