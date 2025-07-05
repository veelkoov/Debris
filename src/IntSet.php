<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DSet<int>
 */
class IntSet extends DSet
{
    use EnforceIntValuesTrait;
}
