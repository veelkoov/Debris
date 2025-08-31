<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

/**
 * @template V of object|scalar|null
 *
 * @extends DScalarMap<int, V>
 */
class DIntMap extends DScalarMap
{
    use Enforce\IntKeysTrait;
}
