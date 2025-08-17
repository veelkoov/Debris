<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;
use Veelkoov\Debris\Base\Internal\EnforceNullStringKeysTrait;

/**
 * @extends DMap<?string, int>
 */
class NullStringToInt extends DMap
{
    use EnforceNullStringKeysTrait;
    use EnforceIntValuesTrait;
}
