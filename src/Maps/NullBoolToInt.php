<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;
use Veelkoov\Debris\Base\Internal\EnforceNullBoolKeysTrait;

/**
 * @extends DMap<?bool, int>
 */
class NullBoolToInt extends DMap
{
    use EnforceIntValuesTrait;
    use EnforceNullBoolKeysTrait;
}
