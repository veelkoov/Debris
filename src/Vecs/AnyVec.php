<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Vecs;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @extends DVec<null|object|scalar>
 */
class AnyVec extends DVec
{
    use Enforce\AnyValuesTrait;
}
