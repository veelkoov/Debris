<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DIntMap<int>
 */
class IntIntMap extends DIntMap
{
    use EnforceIntValuesTrait;
}
