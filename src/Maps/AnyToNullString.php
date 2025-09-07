<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DMap<null|object|scalar, ?string>
 */
class AnyToNullString extends DMap
{
    use Enforce\AnyKeysTrait;
    use Enforce\NullStringValuesTrait;
}
