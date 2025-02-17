<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DStringMap<int>
 */
class StringIntMap extends DStringMap
{
    use EnforceIntValuesTrait;
}
