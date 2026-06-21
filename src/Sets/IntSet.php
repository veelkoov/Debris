<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DIntMap;
use Veelkoov\Debris\Sets\Base\DSet;

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
