<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Internal\EnforceStringValuesTrait;

/**
 * @extends DIntMap<string>
 */
class IntToString extends DIntMap
{
    use EnforceStringValuesTrait;
}
