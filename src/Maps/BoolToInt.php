<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DMap;

/**
 * @extends DMap<bool, int>
 */
class BoolToInt extends DMap
{
    use Enforce\BoolKeysTrait;
    use Enforce\IntValuesTrait;
}
