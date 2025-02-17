<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Internal\EnforceStringValuesTrait;

/**
 * @extends DIntMap<string>
 */
class IntStringMap extends DIntMap
{
    use EnforceStringValuesTrait;
}
