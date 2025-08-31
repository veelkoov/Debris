<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DSet<null|object|scalar>
 */
class AnySet extends DSet
{
    use Enforce\AnyValuesTrait;
}
