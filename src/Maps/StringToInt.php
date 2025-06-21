<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DStringMap<int>
 */
class StringToInt extends DStringMap
{
    use EnforceIntValuesTrait;
}
