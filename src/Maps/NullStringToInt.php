<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DMap;

/**
 * @extends DMap<?string, int>
 */
class NullStringToInt extends DMap
{
    use Enforce\IntValuesTrait;
    use Enforce\NullStringKeysTrait;
}
