<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\EnforceIntKeysTrait;

/**
 * @template V of object|scalar|null
 *
 * @extends DScalarMap<int, V>
 */
class DIntMap extends DScalarMap
{
    use EnforceIntKeysTrait;
}
