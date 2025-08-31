<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Sets\StringSet;

/**
 * @template V of object|scalar|null
 *
 * @extends DScalarMap<string, V>
 */
class DStringMap extends DScalarMap
{
    use Enforce\StringKeysTrait;

    #[\Override]
    public function getKeys(): StringSet
    {
        return new StringSet(parent::getKeysArray());
    }
}
