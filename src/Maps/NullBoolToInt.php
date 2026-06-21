<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DMap;

/**
 * @extends DMap<?bool, int>
 */
class NullBoolToInt extends DMap
{
    use Enforce\IntValuesTrait;
    use Enforce\NullBoolKeysTrait;
}
