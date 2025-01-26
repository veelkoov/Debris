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
    public function getKeys(): StringList
    {
        return new StringList(array_keys($this->items));
    }
}
