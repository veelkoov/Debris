<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DStringMap;

/**
 * @extends DStringMap<bool>
 */
class StringToBool extends DStringMap
{
    use Enforce\BoolValuesTrait;
}
