<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DIntMap<int>
 */
class IntToInt extends DIntMap
{
    use EnforceIntValuesTrait;
}
