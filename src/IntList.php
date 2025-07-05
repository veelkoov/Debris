<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\Internal\EnforceIntValuesTrait;

/**
 * @extends DList<int>
 */
class IntList extends DList
{
    use EnforceIntValuesTrait;
}
