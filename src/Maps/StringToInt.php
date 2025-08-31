<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DStringMap<int>
 */
class StringToInt extends DStringMap
{
    use Enforce\IntValuesTrait;
}
