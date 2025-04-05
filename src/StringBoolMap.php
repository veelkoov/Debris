<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceBoolValuesTrait;

/**
 * @extends DStringMap<bool>
 */
class StringBoolMap extends DStringMap
{
    use EnforceBoolValuesTrait;
}
