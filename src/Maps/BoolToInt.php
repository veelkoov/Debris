<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DMap<bool, int>
 */
class BoolToInt extends DMap
{
    use Enforce\BoolKeysTrait;
    use Enforce\IntValuesTrait;
}
