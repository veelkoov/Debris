<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DMap<null|object|scalar, null|object|scalar>
 */
class AnyToAny extends DMap
{
    use Enforce\AnyKeysTrait;
    use Enforce\AnyValuesTrait;
}
