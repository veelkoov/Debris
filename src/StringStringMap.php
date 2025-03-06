<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceStringValuesTrait;

/**
 * @extends DStringMap<string>
 */
class StringStringMap extends DStringMap
{
    use EnforceStringValuesTrait;
}
