<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Vecs\StringVec;

/**
 * @extends DStringMap<StringVec>
 */
class StringToStringVec extends DStringMap
{
    use Enforce\StringVecValuesTrait;
}
