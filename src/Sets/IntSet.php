<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;

/**
 * @extends DIntOrStringSet<int>
 */
class IntSet extends DIntOrStringSet
{
    use Enforce\IntValuesTrait;
}
