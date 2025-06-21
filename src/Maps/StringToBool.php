<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceBoolValuesTrait;

/**
 * @extends DStringMap<bool>
 */
class StringToBool extends DStringMap
{
    use EnforceBoolValuesTrait;
}
