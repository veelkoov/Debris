<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Vecs;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @extends DVec<int>
 */
class IntVec extends DVec
{
    use Enforce\IntValuesTrait;
}
