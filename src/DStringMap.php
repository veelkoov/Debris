<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

/**
 * @template T
 *
 * @extends DScalarMap<string, T>
 */
class DStringMap extends DScalarMap
{
    public function getKeys(): StringSet
    {
        return new StringSet(array_keys($this->items));
    }
}
