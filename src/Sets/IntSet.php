<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Base\Map;

/**
 * @extends DSet<int>
 */
class IntSet extends DSet
{
    use Enforce\IntValuesTrait;

    protected static function getNewInternalContainer(): Map
    {
        return new DIntMap();
    }
}
