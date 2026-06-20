<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Vecs;

use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DVec<null|object|scalar>
 */
class AnyVec extends DVec
{
    use Enforce\AnyValuesTrait;
}
