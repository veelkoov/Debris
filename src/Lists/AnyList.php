<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Lists;

use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DList<null|object|scalar>
 */
class AnyList extends DList
{
    use Enforce\AnyValuesTrait;
}
