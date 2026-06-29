<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DMap;

/**
 * @extends DMap<null|object|scalar, string>
 */
class AnyToString extends DMap
{
    use Enforce\AnyKeysTrait;
    use Enforce\StringValuesTrait;
}
