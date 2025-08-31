<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DMap<?string, int>
 */
class NullStringToInt extends DMap
{
    use Enforce\IntValuesTrait;
    use Enforce\NullStringKeysTrait;
}
