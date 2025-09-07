<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DStringMap<?string>
 */
class StringToNullString extends DStringMap
{
    use Enforce\NullStringValuesTrait;
}
