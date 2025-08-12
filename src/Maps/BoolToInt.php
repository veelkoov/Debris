<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\EnforceBoolKeysTrait;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DMap<bool, int>
 */
class BoolToInt extends DMap
{
    use EnforceBoolKeysTrait;
    use EnforceIntValuesTrait;
}
