<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Sets\Base\DSet;

/**
 * @extends DSet<null|object|scalar>
 */
class AnySet extends DSet
{
    use Enforce\AnyValuesTrait;
}
