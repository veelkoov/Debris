<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceStringValuesTrait;

/**
 * @extends DStringMap<string>
 */
class StringToString extends DStringMap
{
    use EnforceStringValuesTrait;
}
